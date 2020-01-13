<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('dyndns');
sendVarToJS('eqType', 'dyndns');
$eqLogics = eqLogic::byType('dyndns');
?>

<div class="row row-overflow">
   <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay">
<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>	   
    <div class="eqLogicThumbnailContainer">
     	<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
	    </div>
	    
	    <legend>{{Mes dyndns}}</legend>
	   	<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
<?php
foreach ($eqLogics as $eqLogic) {
		$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
				echo '<br/>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
}
?>
</div>

</div>

<div class="col-lg-12 eqLogic" style="display:none;">
	<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
    <form class="form-horizontal">
        <fieldset>
            <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}  <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Nom de l'équipement dyndns}}</label>
                <div class="col-sm-3">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement dyndns}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                <div class="col-sm-3">
                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                        <option value="">{{Aucun}}</option>
                        <?php
foreach (jeeObject::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
                   </select>
               </div>
           </div>
           <div class="form-group">
           <label class="col-sm-3 control-label">{{Catégorie}}</label>
              <div class="col-sm-8">
                <?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	echo '<label class="checkbox-inline">';
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	echo '</label>';
}
?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" ></label>
            <div class="col-sm-9">
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
            </div>
        </div>
		 <div class="form-group">
        <label class="col-sm-3 control-label">{{Service}}</label>
        <div class="col-sm-3">
            <select class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="type">
                <option value="nonde">{{Aucun}}</option>
                <option value="dyndnsorg">www.dyndns.org</option>
                <option value="noipcom">www.noip.com</option>
				<option value="ovhcom">www.ovh.com</option>
                <option value="duckdns">www.duckdns.org</option>
                <option value="stratocom">www.strato.com</option>
            </select>
        </div>
    </div>
    <div class="serviceType dyndnsorg noipcom ovhcom duckdns stratocom">
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Hostname}}</label>
            <div class="col-sm-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="hostname" />
            </div>
        </div>
        <div class="form-group serviceType dyndnsorg noipcom ovhcom stratocom">
            <label class="col-sm-3 control-label">{{Nom d'utilisateur}}</label>
            <div class="col-sm-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username" />
            </div>
        </div>
        <div class="form-group serviceType dyndnsorg noipcom ovhcom stratocom">
            <label class="col-sm-3 control-label">{{Mot de passe}}</label>
            <div class="col-sm-3">
                <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" />
            </div>
        </div>
				<div class="form-group serviceType duckdns">
            <label class="col-sm-3 control-label">{{Token}}</label>
            <div class="col-sm-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="token" />
            </div>
        </div>
    </div>
</fieldset>
</form>

<legend>{{Commandes}}</legend>
<table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th style="max-width : 200px;">{{Nom}}</th><th>{{Type}}</th><th>{{Action}}</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
</div>

<?php include_file('desktop', 'dyndns', 'js', 'dyndns');?>
<?php include_file('core', 'plugin.template', 'js');?>
