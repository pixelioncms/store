<?php
$plugins = array();
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/category.js', CClientScript::POS_END);
Yii::app()->tpl->openWidget(array(
    'title' => Yii::t('ShopModule.admin', "CATALOG"),
    'htmlOptions' => array('class' => '')
));
?>
<div class="col-xs-12 pr-3 pl-3 pt-3">
    <?php
    if (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.MoveNode'))) {
        Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', "INFO_USE_DND"), false);
        $plugins[] = 'dnd';
    }
    ?>
</div>

<div class="form-group pr-3 pl-3">
    <div class="col-xs-12">
        <input class="form-control" placeholder="<?= Yii::t('app', 'SEARCH'); ?>" type="text" onkeyup='$("#ShopCategoryTree").jstree(true).search($(this).val())' />
    </div>
</div>



<?php
$plugins[] = 'search';
$plugins[] = 'contextmenu';
$plugins[] = 'wholerow';
$plugins[] = 'state';

$this->widget('ext.jstree.JsTree', array(
    'id' => 'ShopCategoryTree',
    'data' => ShopCategoryNode::fromArray(ShopCategory::model()->findAllByPk(1), array('switch' => true)),
    'options' => array(
        /*  "panix" => 'js:function (node) {
          console.log(node);
          return node.text === "Насосное оборудование" ? true : false;
          }', */
        'core' => array(
            'force_text' => true,
            'animation' => 0,
            'strings' => array('Loading ...' => Yii::t('app', 'LOADING')),
            'check_callback' => true,
            "themes" => array("stripes" => true, 'responsive' => true),
            "check_callback" => 'js:function (operation, node, parent, position, more) {
                    console.log(operation);
                    if(operation === "copy_node" || operation === "move_node"){

                    } else if (operation === "delete_node"){
                    
                    } else if (operation === "rename_node"){
                      
                    } else if (operation === "remove_node"){
                       //common.notify("dsa","success");
                    } else {
                        return true; // allow everything else
                    }
                }
        '),
        'plugins' => $plugins,
        'contextmenu' => array(
            'items' => 'js:function($node) {
                var tree = $("#ShopCategoryTree").jstree(true);
                return {
                    "Switch": {
                        "icon":"icon-eye",
                        "label": "' . Yii::t('app', 'SWITCH') . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.SwitchNode')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.SwitchNode')) ? Yii::t('app', 'Скрыть показать') : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                            $node = tree.get_node($node);
                           // console.log($node);
                            categorySwitch($node);
                        }
                    }, 
                    "Add": {
                        "icon":"icon-add",
                        "label": "' . Yii::t('app', 'CREATE', 0) . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.CreateNode', 'Shop.Category.Create')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.CreateNode', 'Shop.Category.Create')) ? Yii::t('app', 'CREATE', 0) : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                            $node = tree.get_node($node);
                             window.location = "'.Yii::app()->createUrl('/admin/shop/category/create/parent_id').'/"+$node.id.replace("node_", "");
                        }
                    }, 
                    "Edit": {
                        "icon":"icon-edit",
                        "label": "' . Yii::t('app', 'UPDATE', 0) . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.Update')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.Update')) ? Yii::t('app', 'UPDATE', 0) : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                            $node = tree.get_node($node);
                           window.location = "'.Yii::app()->createUrl('/admin/shop/category/update/id').'/"+$node.id.replace("node_", "");
                        }
                    },  
                    "Rename": {
                        "icon":"icon-rename",
                        "label": "' . Yii::t('app', 'RENAME') . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.RenameNode')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.RenameNode')) ? Yii::t('app', 'RENAME') : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                            tree.edit($node);
                        }
                    },                         
                    "Remove": {
                        "icon":"icon-trashcan",
                        "label": "' . Yii::t('app', 'DELETE') . '",
                        "_disabled":' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.Delete')) ? "false" : "true") . ',
                        "title":"' . (Yii::app()->user->openAccess(array('Shop.Category.*', 'Shop.Category.Delete')) ? Yii::t('app', 'DELETE') : Yii::t('error', '401')) . '",
                        "action": function (obj) {
                            if (confirm("' . Yii::t('ShopModule.admin', 'CONFIRM_DELETE_CATEGORY') . '")) {
                                tree.delete_node($node);
                            }
                        }
                    }
                };
            }'
        )
    ),
));
?>


<?php
Yii::app()->tpl->closeWidget();



