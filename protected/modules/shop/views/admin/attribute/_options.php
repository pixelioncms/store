<?php
//Yii::app()->getClientScript()
//        ->registerScriptFile($this->module->assetsUrl . '/admin/attribute.options.js', CClientScript::POS_END);
Yii::app()->getClientScript()
    ->registerScriptFile($this->module->assetsUrl . '/admin/attribute.options.new.js', CClientScript::POS_END);
?>

    <style type="text/css">

        table.optionsEditTable input[type="text"] {
            width: 200px;
        }

        tr.copyMe {
            display: none;
        }

    </style>
    <div class="panel-body-static text-right">
        <a class="plusOne btn btn-success" style="color:#fff" href="javascript:void(0)">
            <i class="icon-add"></i> Добавить опцию
        </a>
    </div>
    <table>
        <tr class="copyMe">
            <td class="text-center">N/A</td>
            <?php foreach (Yii::app()->languageManager->languages as $k => $l) { ?>
                <td>
                    <input name="sample" type="text" class="value form-control input-lang"
                           style="background-image:url(/uploads/language/<?= $k ?>.png">
                </td>
            <?php } ?>
            <td class="text-center">N/A</td>

            <td>
                <input name="spec" type="text" class="value-spec form-control" >
            </td>
            <td class="text-center">N/A</td>
            <td class="text-center">
                <a href="javascript:void(0);" class="deleteRow btn btn-sm btn-danger"><i class="icon-delete"></i></a>
            </td>
        </tr>
    </table>
<?php
$columns = array();
$columns[] = array(
    'class' => 'ext.sortable.SortableColumn',
    'url' => '/admin/shop/attribute/sortableAttributes'
);
$data = array();
$data2 = array();
$test = array();
foreach ($model->options as $k => $o) {
    $data2['primaryKey'] = $o->id;
    $data2['delete'] = '<a href="#" class="deleteRow btn btn-sm btn-danger"><i class="icon-delete"></i></a>';
    foreach (Yii::app()->languageManager->languages as $k => $l) {

        $o->option_translate = ShopAttributeOptionTranslate::model()->findByAttributes(array(
            'object_id' => $o->id,
            'language_id' => $l->id
        ));
        if ($o->option_translate) {
            $data2['name' . $k] = Html::textField('options[' . $o->id . '][]', CHtml::encode($o->option_translate->value), array('class' => 'form-control input-lang', 'style' => 'background-image:url(/uploads/language/' . $k . '.png);'));
        }
    }
    //$data2['products'] = Html::link($o->productsCount,array('/admin/shop/products/index?ShopProduct[eav]['.$model->name.']='.$o->id),array('target'=>'_blank'));
    $data2['products'] = Html::link($o->productsCount, array('/admin/shop/products/index', 'ShopProduct[eav][' . $model->name . ']' => $o->id), array('target' => '_blank'));
    $data2['spec'] = Html::textArea('options[' . $o->id . '][spec]', CHtml::encode($o->spec), array('class' => 'form-control'));

    $data2['date_create'] = CMS::date($o->date_create);
    $data[] = (object)$data2;
}


foreach (Yii::app()->languageManager->languages as $k => $l) {
    $columns[] = array(
        'header' => $l->name,
        'name' => 'name' . $k,
        'type' => 'raw',
        //  'value' => '$data->name'
    );
    $sortAttributes[] = 'name' . $k;
}
$columns[] = array(
    'header' => Yii::t('ShopModule.admin', 'PRODUCT_COUNT'),
    'name' => 'products',
    'type' => 'raw',
    'htmlOptions' => array('class' => 'text-center'),
);
$columns[] = array(
    'header' => Yii::t('ShopModule.admin', 'SPEC'),
    'name' => 'spec',
    'type' => 'raw',
    'htmlOptions' => array('class' => 'text-center'),
);


$columns[] = array(
    'header' => Yii::t('app', 'DATE'),
    'name' => 'date_create',
    'type' => 'html',
    'htmlOptions' => array('class' => 'text-center'),

);
$columns[] = array(
    'header' => Yii::t('app', 'OPTIONS'),
    'name' => 'delete',
    'type' => 'html',
    'class' => 'IdColumn',
    'htmlOptions' => array('class' => 'text-center'),
    'filter' => 'adssad'
);


$data_db = new CArrayDataProvider($data, array(
        'keyField' => false,
        'sort' => array(
            'attributes' => $sortAttributes,
            //   'defaultOrder' => array('filename' => false),
        ),
        'pagination' => false,
    )
);


//print_r($columns);


//$model = new ShopAttributeOption('search');

//if (!empty($_GET['ShopAttributeOption']))
//    $model->attributes = $_GET['ShopAttributeOption'];


//$dataProvider = $model->search();

$this->widget('ext.adminList.GridView', array(
        'itemsCssClass' => 'table table-striped table-bordered optionsEditTable',
        'dataProvider' => $data_db,
        'selectableRows' => false,
        'enableHeader' => false,
        'autoColumns' => false,
        'enablePagination' => true,
        'columns' => $columns
    )
//array('class' => 'ext.sortable.SortableColumn'), 
);
?>