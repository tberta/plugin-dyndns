<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class dyndns extends eqLogic {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public static function getExternalIPJeedom() {
		$url = config::byKey('service::cloud::url').'/service/myip';
      		$request_http = new com_http($url);
      		$request_http->setHeader(array('Content-Type: application/json','Autorization: '.sha512(mb_strtolower(config::byKey('market::username')).':'.config::byKey('market::password'))));
      		$data = $request_http->exec(30,1);
		$result = is_json($data, $data);
		if(isset($result['state']) && $result['state'] != 'ok'){
		      throw new \Exception(__('Erreur lors de la requete au serveur cloud Jeedom : ',__FILE__).$data);
		}
		if(isset($result['data']) && isset($result['data']['ip'])){
			//log::add('dyndns','debug','getExternalIP  result: ' . $result['data']['ip']);
			return $result['data']['ip'];
		}
		throw new \Exception(__('impossible de recuperer votre ip externe : ',__FILE__).$data);
	}

	public static function getExternalIP() {
		Try{
			$request_http = new com_http("http://checkip.dyndns.com");
			$data = $request_http->exec(8,1);
			preg_match('/\b((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)[.]){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))\b/', $data, $match);
			if (isset($match[1])) {
				log::add('dyndns','debug','getExternalIP  result: ' . $match[1]);
				return $match[1];
			}
		} catch (Exception $e) {
			throw new \Exception(__('impossible de recuperer votre ip externe : ',__FILE__).$data);
		}
	}

	public static function getExternalIP6() {
		try {
			$request_http = new com_http('http://checkipv6.dyndns.com');
			$externalContent = $request_http->exec(8, 1);
			preg_match('/(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4})/', $externalContent, $m);
			//log::add('dyndns','debug','Match: ---' . $m[1] .'+++' );
			if (isset($m[1])) {
				return $m[1];
			}
		} catch (Exception $e) {

		}
		$request_http = new com_http('http://ip1.dynupdate6.no-ip.com/');
		return $request_http->exec(8, 1);
	}

	public static function cron15($_eqLogic_id = null, $_force = false) {
		if ($_eqLogic_id == null) {
			$eqLogics = self::byType('dyndns',true);
		} else {
			$eqLogics = array(self::byId($_eqLogic_id));
		}
		$current_externalIP = self::getExternalIP();
		foreach ($eqLogics as $eqLogic) {
			$externalIP = $eqLogic->getCmd(null, 'externalIP');
			if (!is_object($externalIP)) {
				continue;
			}
			$flagUpdate = 0;
			$ip = $externalIP->execCmd();
			if ($_force || $ip != $externalIP->formatValue($current_externalIP)) {
				log::add('dyndns','debug', __('IP sauvee: ',__FILE__) .$ip . __(', IP courante: ',__FILE__) . $externalIP->formatValue($current_externalIP) );
				$externalIP->setCollectDate('');
				$externalIP->event($current_externalIP);
				log::add('dyndns','debug',__('Mise à jour de l\'adresse IP:',__FILE__) );
				$flagUpdate = 1;
			}

			if ($eqLogic->getConfiguration("ipv6") > 0){
				$current_externalIP6 = self::getExternalIP6();
				$externalIP6 = $eqLogic->getCmd(null, 'externalIP6');
				$ipv6 = $externalIP6->execCmd();
				if ($_force || $ipv6 != $externalIP6->formatValue($current_externalIP6)) {
					log::add('dyndns','debug',  __('IPv6 sauvee: ',__FILE__) . $ipv6 .  __(', IPv6 courante: ',__FILE__) . $externalIP6->formatValue($current_externalIP6) );
					$externalIP6->setCollectDate('');
					$externalIP6->event($current_externalIP6);
					log::add('dyndns','debug',__('Mise à jour de l\'adresse IPv6:',__FILE__) );
					$flagUpdate = 1;
				}
			}

			if ($flagUpdate > 0 ) {
				$eqLogic->updateIP();
			}			
		}
	}

	public static function testip($_eqLogic_id = null) {
		if ($_eqLogic_id == null) {
			$eqLogics = self::byType('dyndns');
		} else {
			$eqLogics = array(self::byId($_eqLogic_id));
		}

		$current_externalIP = self::getExternalIP();
		log::add('dyndns','debug',__('External IPv4: ',__FILE__) . $current_externalIP);

		if ((self::byId($_eqLogic_id))->getConfiguration("ipv6") > 0){
			$current_externalIP6 = self::getExternalIP6();
			log::add('dyndns','debug',__('External IPv6: ',__FILE__) . $current_externalIP6);
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function postSave() {
		$testip = $this->getCmd(null, 'testip');
		if (!is_object($testip)) {
			$testip = new dyndnsCmd();
			$testip->setLogicalId('testip');
			$testip->setIsVisible(1);
			$testip->setName(__('Tester IP', __FILE__));
		}
		$testip->setType('action');
		$testip->setSubType('other');
		$testip->setEqLogic_id($this->getId());
		$testip->save();

		$update = $this->getCmd(null, 'update');
		if (!is_object($update)) {
			$update = new dyndnsCmd();
			$update->setLogicalId('update');
			$update->setIsVisible(1);
			$update->setName(__('Mettre à jour', __FILE__));
		}
		$update->setType('action');
		$update->setSubType('other');
		$update->setEqLogic_id($this->getId());
		$update->save();

		$externalIP = $this->getCmd(null, 'externalIP');
		if (!is_object($externalIP)) {
			$externalIP = new dyndnsCmd();
			$externalIP->setLogicalId('externalIP');
			$externalIP->setIsVisible(1);
			$externalIP->setName(__('IP', __FILE__));
		}
		$externalIP->setType('info');
		$externalIP->setSubType('string');
		$externalIP->setEqLogic_id($this->getId());
		$externalIP->save();

		$externalIP6 = $this->getCmd(null, 'externalIP6');
		if (!is_object($externalIP6)) {
			$externalIP6 = new dyndnsCmd();
			$externalIP6->setLogicalId('externalIP6');
			$externalIP6->setIsVisible(1);
			$externalIP6->setName(__('IPv6', __FILE__));
		}
		$externalIP6->setType('info');
		$externalIP6->setSubType('string');
		$externalIP6->setEqLogic_id($this->getId());
		$externalIP6->save();

		self::cron15($this->getId());
	}


	public function updateIP() {
		$externalIP = $this->getCmd(null, 'externalIP');
		if (!is_object($externalIP)) {
			throw new Exception(__('Commande externalIP inexistante', __FILE__));
		}
		$ip = $externalIP->execCmd(null, 2);
		$flagipv6 = $this->getConfiguration('ipv6');
		if ($flagipv6) {
			$externalIP6 = $this->getCmd(null, 'externalIP6');
			if (!is_object($externalIP6)) {
				throw new Exception(__('Commande externalIP6 inexistante', __FILE__));
			}
			$ip6 = $externalIP6->execCmd(null, 2);
		}
		switch ($this->getConfiguration('type')) {
			case 'dyndnsorg':
				$url = 'https://' . urlencode($this->getConfiguration('username')) . ':' . urlencode($this->getConfiguration('password')) . '@members.dyndns.org/nic/update?hostname=' . $this->getConfiguration('hostname') . '&myip=' . $ip;
				$request_http = new com_http($url);
				$request_http->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12');
				$result = $request_http->exec();
				if (strpos($result, 'good') === false) {
					throw new Exception(__('Erreur de mise à jour de dyndns.org : ', __FILE__) . $result);
				}
				break;
			case 'noipcom':
				if ($flagipv6){
					$url = 'https://dynupdate.no-ip.com/nic/update?hostname=' . $this->getConfiguration('hostname') . '&myip=' . $ip . ',' . $ip6;
				} else {
					$url = 'https://dynupdate.no-ip.com/nic/update?hostname=' . $this->getConfiguration('hostname') . '&myip=' . $ip;
				}
				log::add('dyndns', 'debug', $url);
				$request_http = new com_http($url,$this->getConfiguration('username'),$this->getConfiguration('password'));           	
				$request_http->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12');
				$result = $request_http->exec();
				if (strpos($result, 'good') === false && strpos($result, 'nochg') === false) {
					throw new Exception(__('Erreur de mise à jour de noip.com : ', __FILE__) . $result);
				}
				break;
			case 'ovhcom':
				$url = 'https://' . urlencode($this->getConfiguration('username')) . ':' . urlencode($this->getConfiguration('password')) . '@www.ovh.com/nic/update?system=dyndns&hostname=' . $this->getConfiguration('hostname') . '&myip=' . $ip;
				$request_http = new com_http($url);
				$request_http->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12');
				$result = $request_http->exec();
				if (strpos($result, 'good') === false && strpos($result, 'nochg') === false) {
					throw new Exception(__('Erreur de mise à jour de ovh.com : ', __FILE__) . $result);
				}
				break;
      		case 'duckdns':
				if ($flagipv6){
					$url = 'https://www.duckdns.org/update?domains=' . $this->getConfiguration('hostname') . '&token=' . $this->getConfiguration('token') .'&myip=' . $ip . '&ipv6=' . $ip6;
				} else {
					$url = 'https://www.duckdns.org/update?domains=' . $this->getConfiguration('hostname') . '&token=' . $this->getConfiguration('token') .'&myip=' . $ip;
				}
				log::add('dyndns', 'debug', $url);
				$request_http = new com_http($url);
				$request_http->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12');
				$result = $request_http->exec();
				if (strpos($result, 'OK') === false) {
					throw new Exception(__('Erreur de mise à jour de duckdns : ' . $url, __FILE__) . $result);
				}
				break;
      		case 'stratocom':
      			$url = 'https://' . urlencode($this->getConfiguration('username')) . ':' . urlencode($this->getConfiguration('password')) . '@dyndns.strato.com/nic/update?system=dyndns&hostname=' . $this->getConfiguration('hostname') . '&myip=' . $ip;
      			$request_http = new com_http($url);
      			$request_http->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12');
      			$result = $request_http->exec();
      			if (strpos($result, 'good') === false && strpos($result, 'nochg') === false) {
      				throw new Exception(__('Erreur de mise à jour de strato.com : ', __FILE__) . $result);
      			}
      			break;
     		case 'gandinet':
				$url = 'https://dns.api.gandi.net/api/v5/domains/' . $this->getConfiguration('domainname') . '/records/' . $this->getConfiguration('hostname') .'/A';
				$payload = array('rrset_type'=>'A','rrset_ttl'=>'3600','rrset_name'=>$this->getConfiguration('hostname'),'rrset_values'=>array($ip));
				$payload_json = json_encode($payload);
				$request_http = new com_http($url);
				$request_http->setUserAgent('Jeedom dyndns plugin');
				$request_http->setHeader(array('Content-Type: application/json', 'Content-Length: ' . strlen($payload_json), 'X-API-Key:' . $this->getConfiguration('token')));
				$request_http->setPut($payload_json);
				$result = $request_http->exec();
				if (strpos($result, 'error') !== false) {
					throw new Exception(__('Erreur de mise à jour de gandinet : ' . $url, __FILE__) . $result);
				}
				break;
			case 'infomaniak':
				$url = 'https://' . urlencode($this->getConfiguration('username')) . ':' . urlencode($this->getConfiguration('password')) . '@infomaniak.com/nic/update?hostname=' . $this->getConfiguration('hostname') .'&myip=' . $ip;
				log::add('dyndns', 'debug', $url);
				$request_http = new com_http($url);
				$request_http->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12');
				$result = $request_http->exec();
				if (strpos($result, 'good') === false && strpos($result, 'nochg') === false) {
					throw new Exception(__('Erreur de mise à jour de infomaniak.com : ', __FILE__) . $result);
				}
				break;
			case 'dedyn':
				if ($flagipv6){
					$url = 'https://update.dedyn.io/?' . 'myipv4=' . $ip . '&myipv6=' . $ip6;
				} else {
					$url = 'https://update.dedyn.io/?' . 'myipv4=' . $ip;
				}
				log::add('dedyn', 'debug', $url);
				//$request_http = new com_http($url);
				$request_http = new com_http($url,$this->getConfiguration('hostname'),$this->getConfiguration('token'));
				$request_http->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12');
				$result = $request_http->exec();
				if (strpos($result, 'good') === false && strpos($result, 'nochg') === false) {
					throw new Exception(__('Erreur de mise à jour de dedyn.io : ', __FILE__) . $result);
				}
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}

class dyndnsCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = array()) {
		if ($this->getLogicalId() == 'update') {
			dyndns::cron15($this->getEqLogic()->getId(), true);
		}
		if ($this->getLogicalId() == 'testip') {
			dyndns::testip($this->getEqLogic()->getId(), true);
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>