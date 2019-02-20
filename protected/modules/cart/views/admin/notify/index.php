
    <?php
    // print_r($dataProvider);
    // die();
    $this->widget('ext.adminList.GridView', array(
        'dataProvider' => $dataProvider,
        'selectableRows' => false,
        'name'=>$this->pageName,
        'autoColumns'=>false,
        'enableHeader'=>true,
        'columns' => array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->product->name), array("/shop/admin/products/update", "id"=>$data->product->id))',
            ),
            array(
                'name' => 'product_availability',
                'type' => 'raw',
                'value' => 'Html::encode($data->product->availabilityItems[$data->product->availability])',
            ),
            array(
                'name' => 'product_quantity',
                'type' => 'raw',
                'value' => 'Html::encode($data->product->quantity)',
            ),
            array(
                'name' => 'totalEmails'
            ),
            array(
                'class' => 'CLinkColumn',
                'label' => 'Отправить письмо',
                'urlExpression' => 'Yii::app()->createUrl("shop/admin/notify/send", array("product_id"=>$data->product_id))',
                'linkHtmlOptions' => array(
                    'confirm' => Yii::t('CartModule.default', 'Вы уверены?')
                )
            ),
            array(
                'class' => 'ButtonColumn',
                'template' => '{delete}',
            ),
        ),
    ));
    ?>

