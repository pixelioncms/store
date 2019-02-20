<div class="form-group">
    <div class="col-xs-4"><label class="control-label" for="search-type-category"><?php echo Yii::t('app', 'Поиск:') ?></label></div>
    <div class="col-xs-8"><input class="form-control" id="search-type-category" type="text" onkeyup='$("#ShopTypeCategoryTree").jstree("search", $(this).val());' />
    </div>
</div>


<?php
// Create jstree
$this->widget('ext.jstree.JsTree', array(
    'id' => 'ShopTypeCategoryTree',
    'data' => ShopCategoryNode::fromArray(ShopCategory::model()->findAllByPk(1)),
    'options' => array(
        'core' => array(
            'strings' => array('Loading ...' => 'Please wait ...'),
            'check_callback' => true,
            "themes" => array("stripes" => true, 'responsive' => true),
        ),
        'plugins' => array('search', 'checkbox'),
        'checkbox' => array(
            'three_state' => false,
            'tie_selection' => false,
            'whole_node' => false,
            "keep_selected_style" => true
        ),
    ),
));

// Check tree nodes
$categories = unserialize($model->categories_preset);
if (!is_array($categories))
    $categories = array();

foreach ($categories as $id) {

    Yii::app()->getClientScript()->registerScript("checkNode{$id}", "
		$('#ShopTypeCategoryTree').checkNode({$id});
	");
}


echo Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', "Здесь вы можете указать категории, которые будут автоматически выбраны при создании продукта."));
echo Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', "Нажмите на название категории, чтобы сделать её главной."));
?>
