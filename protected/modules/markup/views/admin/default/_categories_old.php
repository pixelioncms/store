<?php

Yii::import('mod.shop.models.ShopCategory');
Yii::import('mod.shop.models.ShopCategoryNode');
echo Yii::app()->tpl->alert('info',Yii::t('app',"Здесь вы можете указать категории, для которых будет доступна скидка."));
?>


	<?php echo Yii::t('app', 'Поиск:') ?> <input type="text" onkeyup='$("#ShopDiscountCategoryTree").jstree("search", $(this).val());' />


<?php
// Create jstree
$this->widget('mod.shop.widgets.jstree.JsTree', array(
	'id'=>'ShopDiscountCategoryTree',
	'data'=>ShopCategoryNode::fromArray(ShopCategory::model()->findAllByPk(1)),
	'options'=>array(
		'core'=>array(
			// Open root
			'initially_open'=>'ShopDiscountCategoryTreeNode_1',
		),
		'plugins'=>array('themes','html_data','ui','crrm', 'search','checkbox'),
		'checkbox'=>array(
			'two_state'=>true,
		),
	),
));

// Check tree nodes
foreach($model->categories as $id)
{
	Yii::app()->getClientScript()->registerScript("checkNode{$id}", "
		$('#ShopDiscountCategoryTree').checkNode({$id});
	");
}

?>
