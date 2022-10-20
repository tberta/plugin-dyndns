<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('dyndns');
sendVarToJS('eqType', 'dyndns');
$eqLogics = eqLogic::byType('dyndns');
?>

<div class="row row-overflow">
    <div class="col-lg-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction logoPrimary" data-action="add">
                <i class="fas fa-plus-circle"></i>
                <br>
                <span>{{Ajouter}}</span>
            </div>
        </div>

        <legend>{{Mes dyndns}}</legend>
        <?php
        if (count($eqLogics) == 0) {
            echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement Template trouvé, cliquer sur "Ajouter" pour commencer}}</div>';
        } else {
            // Champ de recherche
            echo '<div class="input-group" style="margin:5px;">';
            echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
            echo '<div class="input-group-btn">';
            echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
            echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
            echo '</div>';
            echo '</div>';
            // Liste des équipements du plugin
            echo '<div class="eqLogicThumbnailContainer">';
            foreach ($eqLogics as $eqLogic) {
                $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
                echo '<img src="' . $plugin->getPathImgIcon() . '">';
                echo '<br>';
                echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                echo '<span class="hiddenAsCard displayTableRight hidden">';
                echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
                echo '</span>';
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
    </div>

    <div class="col-lg-12 eqLogic" style="display:none;">
        <div class="input-group pull-right" style="display:inline-flex">
            <span class="input-group-btn">
                <a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
            </span>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
            <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <form class="form-horizontal">
                    <fieldset>
                        <div class="col-lg-8">
                            <legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Nom de l'équipement}}</label>
                                <div class="col-sm-6">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display:none;">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Objet parent}}</label>
                                <div class="col-sm-6">
                                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                        <option value="">{{Aucun}}</option>
                                        <?php
                                        $options = '';
                                        foreach ((jeeObject::buildTree(null, false)) as $object) {
                                            $options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
                                        }
                                        echo $options;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Catégorie}}</label>
                                <div class="col-sm-8">
                                    <?php
                                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                        echo '<label class="checkbox-inline">';
                                        echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
                                        echo '</label>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Options}}</label>
                                <div class="col-sm-6">
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
                                </div>
                            </div>

                            <legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Service}}</label>
                                <div class="col-sm-6">
                                    <select class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="type">
                                        <option value="nonde">{{Aucun}}</option>
                                        <option value="dyndnsorg">www.dyndns.org</option>
                                        <option value="noipcom">www.noip.com</option>
                                        <option value="ovhcom">www.ovh.com</option>
                                        <option value="duckdns">www.duckdns.org</option>
                                        <option value="stratocom">www.strato.com</option>
                                        <option value="gandinet">www.gandi.net</option>
                                        <option value="infomaniak">www.infomaniak.com</option>
                                    </select>
                                </div>
                            </div>
                            <div class="serviceType dyndnsorg noipcom ovhcom duckdns stratocom gandinet infomaniak">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{Hostname}}</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="hostname" />
                                    </div>
                                </div>
                                <div class="form-group serviceType gandinet">
                                    <label class="col-sm-4 control-label">{{Domainname}}</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="domainname" />
                                    </div>
                                </div>
                                <div class="form-group serviceType dyndnsorg noipcom ovhcom stratocom infomaniak">
                                    <label class="col-sm-4 control-label">{{Nom d'utilisateur}}</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username" />
                                    </div>
                                </div>
                                <div class="form-group serviceType dyndnsorg noipcom ovhcom stratocom infomaniak">
                                    <label class="col-sm-4 control-label">{{Mot de passe}}</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" />
                                    </div>
                                </div>
                                <div class="form-group serviceType duckdns gandinet">
                                    <label class="col-sm-4 control-label">{{Token}}</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="token" />
                                    </div>
                                </div>
                                <div class="form-group serviceType noipcom duckdns">
                                    <label class="col-sm-4 control-label">{{IPv6}}</label>
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="ipv6" /> &nbsp;
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="commandtab">
                <div class="table-responsive">
                    <table id="table_cmd" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th class="hidden-xs" style="min-width:50px;width:70px;">ID</th>
                                <th style="min-width:200px;width:350px;">{{Nom}}</th>
                                <th style="min-width:260px;">{{Options}}</th>
                                <th>{{Etat}}</th>
                                <th style="min-width:80px;width:140px;">{{Actions}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include_file('desktop', 'dyndns', 'js', 'dyndns'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>