<?php
if (isset($this->module->adminMenu['system'])) {
    echo Html::openTag('ul', array('class' => 'middleNavA'));
    foreach ($this->module->adminMenu['system']['items'] as $key => $item) {
        echo Html::openTag('li');
        echo Html::link('<span class="iconb ' . $item['icon'] . '"></span><span>' . $item['label'] . '</span>', $item['url']);
        echo Html::closeTag('li');
    }
    echo Html::closeTag('ul');
    ?>
    <div class="divider"><span></span></div>
    <?php
}
?>

<?php
$items = $this->module->getAdminMenu();
if (isset($items)) {
    ?>
    <div class="row">
        <?php
        foreach ($items['shop']['items'] as $key => $item) {
            if (isset($item['count'])) {
                $badge = '<span class="badge badge-success">' . $item['count'] . '</span>';
            } else {
                $badge = '';
            }
            $visible = (isset($item['visible'])) ? $item['visible'] : true;
            if ($visible) {
                $html = $badge . '<span class="size-x5">' . $item['icon'] . '</span>';
                $html .= '<h4>' . $item['label'] . '</h4>';
                ?>
                <div class="col-xs-12 col-md-2 col-sm-4 col-lg-2 main-icon">
                    <?= Html::link($html, $item['url'], array('class' => 'small-thumbnail text-center')); ?>


                </div>
                <?php
            }
        }
        ?>
    </div>
    <?php
}
?>



<?php
//print_r($_POST);
echo Html::form('', 'post', array('class' => '', 'id' => 'orderUpdateForm'));

$this->widget('ext.jstree.JsTree', array(
    'id' => 'ShopCategoryTreeFilter',
    'options' => array(
        "core" => array(
            "animation" => 0,
            "check_callback" => true,
            "themes" => array("stripes" => true, 'responsive' => true),
            'data' => array(
                'url' => Yii::app()->createUrl('/admin/shop/default/ajaxRoot'),
                'data' => 'js:function (node) {
                        return { "id" : 1};
                    }'
            ),
        ),
        'plugins' => array('dnd', 'search', 'contextmenu', 'checkbox', 'changed'),
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
                    'icon' => 'icon-plus'
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
            )
        )
    )
));
?>
<div class="form-group text-center">
<?= Html::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
</div>
<?= Html::endForm(); ?>