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

	public static function getExternalIP() {
		$url = config::byKey('service::cloud::url').'/service/myip';
      		$request_http = new com_http($url);
      		$request_http->setHeader(array('Content-Type: application/json','Autorization: '.sha512(mb_strtolower(config::byKey('market::username')).':'.config::byKey('market::password'))));
      		$data = $request_http->exec(30,1);
		$result = is_json($data, $data);
		if(isset($result['state']) && $result['state'] != 'ok'){
		      throw new \Exception(__('Erreur lors de la requete au serveur cloud Jeedom : ',__FILE__).$data);
		}
		if(isset($result['data']) && isset($result['data']['ip'])){
			return $result['data']['ip'];
		}
		throw new \Exception(__('impossible de recuperer votre ip externe : ',__FILE__).$data);
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
			if ($_force || $externalIP->execCmd() != $externalIP->formatValue($current_externalIP)) {
				$externalIP->setCollectDate('');
				$externalIP->event($current_externalIP);
				$eqLogic->updateIP();
			}
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function postSave() {
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
		self::cron15($this->getId());
	}

	public function updateIP() {
		$externalIP = $this->getCmd(null, 'externalIP');
		if (!is_object($externalIP)) {
			throw new Exception(__('Commande externalIP inexistante', __FILE__));
		}
		$ip = $externalIP->execCmd(null, 2);
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
				$url = 'https://dynupdate.no-ip.com/nic/update?hostname=' . $this->getConfiguration('hostname') . '&myip=' . $ip;
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
				$url = 'https://www.duckdns.org/update?domains=' . $this->getConfiguration('hostname') . '&token=' . $this->getConfiguration('token') . '&ip=' . $ip;
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
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
