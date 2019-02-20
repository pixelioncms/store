<?php
/**
 * Confirutable products tab
 *
 * @var Controller $this
 * @var ShopProduct $product Current product
 * @var ShopProduct $model
 */
Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/products.configurations.js');

// For grid view we use new products instance
$model = new ShopProduct(); 

if (isset($_GET['ConfProduct']))
    $model->attributes = $_GET['ConfProduct'];

$columns = array(
    array(
        'class' => 'CheckBoxColumn',
        'checked' => (!empty($product->configurations) && !isset($clearConfigurations) && !$product->isNewRecord) ? 'true' : 'false'
    ),
    array(
        'name' => 'id',
        'type' => 'text',
        'value' => '$data->id',
        'filter' => Html::textField('ConfProduct[id]', $model->id)
    ),
    array(
        'name' => 'name',
        'type' => 'raw',
        'value' => 'Html::link(Html::encode($data->name), array("update", "id"=>$data->id), array("target"=>"_blank"))',
        'filter' => Html::textField('ConfProduct[name]', $model->name)
    ),
    array(
        'name' => 'sku',
        'value' => '$data->sku',
        'filter' => Html::textField('ConfProduct[sku]', $model->sku)
    ),
    array(
        'name' => 'price',
        'value' => '$data->price',
        'filter' => Html::textField('ConfProduct[price]', $model->price)
    ),
);

// Process attributes
$eavAttributes = array();
$attributeModels = ShopAttribute::model();
$attributeModels->setTableAlias('ShopAttribute');
$attributeModels = $attributeModels->findAllByPk($product->configurable_attributes);

foreach ($attributeModels as $attribute) {
    $selected = null;

    if (isset($_GET['eav'][$attribute->name]) && !empty($_GET['eav'][$attribute->name])) {
        $eavAttributes[$attribute->name] = $_GET['eav'][$attribute->name];
        $selected = $_GET['eav'][$attribute->name];
    }
    else
        array_push($eavAttributes, $attribute->name);

    $columns[] = array(
        'name' => 'eav_' . $attribute->name,
        'header' => $attribute->title,
        'htmlOptions' => array('class' => 'eav'),
        'filter' => CHtml::dropDownList('eav[' . $attribute->name . ']', $selected, CHtml::listData($attribute->options, 'id', 'value'), array(
            'empty' => '---',
        ))
    );
}

if (!empty($eavAttributes))
    $model = $model->withEavAttributes($eavAttributes);

// On edit display only saved configurations
$cr = new CDbCriteria;
if (!empty($product->configurations) && !isset($clearConfigurations) && !$product->isNewRecord)
    $cr->addInCondition('t.id', $product->configurations);

$model->exclude = $product->id;
$model->use_configurations = false;

$dataProvider = $model->search(array(), $cr);
$dataProvider->pagination->pageSize = 100;

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'ajaxUrl' => Yii::app()->createUrl('/admin/shop/products/ApplyConfigurationsFilter', array(
        'product_id' => $product->id,
        'configurable_attributes' => isset($_GET['ShopProduct']['configurable_attributes']) ? $_GET['ShopProduct']['configurable_attributes'] : $product->configurable_attributes,
    )),
    'genId' => false,
    'id' => 'ConfigurationsProductGrid',
    'template' => '{items}{summary}',//{pager}
    'enableCustomActions' => false,
    'enableSorting' => false,
    'autoColumns' => false,
    'enableHeader' => false,
    'selectionChanged' => 'js:function(id){processConfigurableSelection(id)}',
    'selectableRows' => 2,
    'filter' => $model,
    'columns' => $columns,
));
?>

<script type="text/javascript">
    initConfigurationsTable();
</script>