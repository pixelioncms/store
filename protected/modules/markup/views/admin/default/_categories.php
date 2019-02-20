<?php
Yii::import('mod.shop.models.ShopCategory');
Yii::import('mod.shop.models.ShopCategoryNode');
?>
<div class="form-group">
    <?= Yii::app()->tpl->alert('info', Yii::t('app', "Здесь вы можете указать категории, для которых будет доступна скидка."), false); ?>
</div>

<div class="form-group">
    <div class="col-xs-4"><label class="control-label" for="search-markup-category"><?php echo Yii::t('app', 'Поиск:') ?></label></div>
    <div class="col-xs-8"><input class="form-control" id="search-markup-category" type="text" onkeyup='$("#ShopMarkupCategoryTree").jstree("search", $(this).val());' />
    </div>
</div>


<?php
// Create jstree
$this->widget('ext.jstree.JsTree', array(
    'id' => 'ShopMarkupCategoryTree',
    'data' => ShopCategoryNode::fromArray(ShopCategory::model()->findAllByPk(1)),
    'options' => array(
        'core' => array(
            'check_callback' => true,
            "themes" => array("stripes" => true, 'responsive' => true),
        ),
        'plugins' => array('search', 'checkbox'),
        'checkbox' => array(
            'three_state'=>false,
            "keep_selected_style" => false,
            'tie_selection' => false,
        ),
    ),
));

// Check tree nodes
foreach ($model->categories as $id) {
    Yii::app()->getClientScript()->registerScript("checkNode{$id}", "$('#ShopMarkupCategoryTree').checkNode({$id});");
}
?>
