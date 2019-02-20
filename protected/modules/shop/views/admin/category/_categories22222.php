<?php
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/category.js', CClientScript::POS_END);
Yii::app()->tpl->openWidget(array(
    'title' => 'Каталог',
    'htmlOptions' => array('class' => '')
));
?>


<div class="form-group">
    <div class="col-xs-12">
        <input class="form-control" placeholder="Поиск..." type="text" onkeyup='$("#ShopCategoryTree").jstree("search", $(this).val());' />
    </div>
</div>
<div class="col-xs-12">
    <?php
    Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', "Используйте 'drag-and-drop' для сортировки категорий."), false);
    ?>
</div>
<div class="col-xs-12">
    <?php

    $this->widget('mod.shop.widgets.jstree.JsTree', array(
        'id' => 'ShopCategoryTree',
        'data' => ShopCategoryNode::fromArray(ShopCategory::model()->language(1)->findAllByPk(1), array('switch' => true)),
        'options' => array(
            "themes" => array("stripes" => true),
            'core' => array('initially_open' => 'ShopCategoryTreeNode_1'),
            'plugins' => array('themes', 'html_data', 'ui', 'dnd', 'crrm', 'search', 'cookies', 'contextmenu'),
            'crrm' => array(
                'move' => array('check_move' => 'js: function(m){
				// Disallow categories without parent.
				// At least each category must have `root` category as parent.
				var p = this._get_parent(m.r);
				if (p == -1) return false;
				return true;
			}')
            ),
            'dnd' => array(
                'drag_finish' => 'js:function(data){
				//alert(data);
			}',
            ),
            'cookies' => array(
                'save_selected' => false,
            ),
            'ui' => array(
                'initially_select' => array('#ShopCategoryTreeNode_' . (int) Yii::app()->request->getParam('id'))
            ),
            'contextmenu' => array(
                'items' => array(
                    'view' => array(
                        'label' => Yii::t('ShopModule.admin', 'Перейти'),
                        'action' => 'js:function(obj){ CategoryRedirectToFront(obj); }'
                    ),
                    'products' => array(
                        'label' => Yii::t('ShopModule.admin', 'Продукты'),
                        'action' => 'js:function(obj){ CategoryRedirectToAdminProducts(obj); }',
                        'icon' => 'icon-cart-3'
                    ),
                    //'create'=>false,
                    'create' => array(
                        'label' => Yii::t('app', 'CREATE', 1),
                        'action' => 'js:function(obj){ CategoryRedirectToParent(obj); }',
                        'icon' => 'icon-add'
                    ),
                    'rename' => false,
                    'remove' => array(
                        'label' => Yii::t('app', 'DELETE'),
                        'icon' => 'icon-trashcan'
                    //'action'=>'js:function(obj){ CategoryRename(obj); }'
                    ),
                    'switch' => array(
                        'label' => Yii::t('app', 'SWITCH'),
                        'icon' => 'icon-eye'
                    //'action'=>'js:function(obj){ CategoryStatus(obj); }'
                    ),
                    'ccp' => false,
                )
            )
        ),
    ));
    ?>
</div>
<div class="clearfix"></div>

<?php
Yii::app()->tpl->closeWidget();



