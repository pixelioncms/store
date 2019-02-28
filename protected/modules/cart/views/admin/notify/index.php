<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'name' => $this->pageName,
    'autoColumns' => false,
    'enableHeader' => true,
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->product->name), array("/shop/admin/products/update", "id"=>$data->product->id))',
        ),
        array(
            'name' => 'product_availability',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'Html::encode($data->product->availabilityItems[$data->product->availability])',
        ),
        array(
            'name' => 'product_quantity',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'Html::encode($data->product->quantity)',
        ),
        array(

            'name' => 'totalEmails',
            'htmlOptions' => array('class' => 'text-center'),
        ),
        array(
            'class' => 'CLinkColumn',
            'label' => Yii::t('CartModule.admin', 'NOTIFY_SEND'),
            'htmlOptions' => array('class' => 'text-center'),
            'urlExpression' => 'Yii::app()->createUrl("/admin/cart/notify/send", array("product_id"=>$data->product_id))',
            'linkHtmlOptions' => array(
                'class' => 'btn btn-success',
                'confirm' => Yii::t('CartModule.admin', 'CONFIRM_NOTIFY_SEND')
            )
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{delete}',
        ),
    ),
));
