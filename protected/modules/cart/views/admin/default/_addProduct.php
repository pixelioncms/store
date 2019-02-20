<div style="padding-bottom:15px;">
    <?php
    /**
     * Add new product to order.
     * Display products list.
     */
    if (!isset($dataProvider))
        $dataProvider = new ShopProduct('search');

// Fix sort url
    $dataProvider = $dataProvider->search();
    $dataProvider->sort->route = 'addProductList';
    $dataProvider->pagination->route = 'addProductList';



    $columns = array();
    $columns[] = array(
        'class' => 'IdColumn',
        'name' => 'id',
        'type' => 'text',
        'value' => '$data->id',
        'filter' => false
    );
    $columns[] = array(
        'type' => 'raw',
        'value' => 'Html::link(Html::image($data->getMainImageUrl("50x50"),$data->name,array("class"=>"img-thumbnail")))'
    );
    $columns[] = array(
        'name' => 'name',
        'type' => 'raw',
    );
    $columns[] = array(
        'name' => 'sku',
        'value' => '$data->sku',
    );
    $columns[] = array(
        'type' => 'raw',
        'name' => 'price',
        'value' => 'Html::textField("price_{$data->id}", $data->price, array("class"=>"form-control","style"=>"text-align:center;width:80px;border:1px solid silver;padding:1px;"))',
    );
    $columns[] = array(
        'type' => 'raw',
        'value' => 'Html::textField("count_{$data->id}", 1, array("class"=>"spinner form-control"))',
        'header' => Yii::t('CartModule.OrderProduct', 'QUANTITY'),
    );
    if (Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.AddProduct'))) {
        $columns[] = array(
            'class' => 'CLinkColumn',
            'header' => '',
            'linkHtmlOptions' => array('class' => 'btn btn-success'),
            //'type' => 'raw',
            'label' => '<i class="icon-add"></i>',
            // 'value' => 'Html::link("<i class=\"icon-add\"></i>", "#", array("class"=>"btn btn-success","onclick"=>"addProductToOrder(this, ' . $model->id . ');"))',
            'urlExpression' => '$data->id',
            'htmlOptions' => array(
                'class' => 'addProductToOrder',
                'onClick' => 'return addProductToOrder(this, ' . $model->id . ');'
            ),
        );
    }
    $this->widget('ext.adminList.GridView', array(
        'filter' => $dataProvider->model,
        'enableHeader' => false,
        'autoColumns' => false,
        'dataProvider' => $dataProvider,
        //'ajaxType'=>'POST',
        'ajaxUrl' => Yii::app()->createUrl('/cart/admin/default/addProductList', array('id' => $model->id)),
        'selectableRows' => 0,
        'columns' => $columns,
        'template' => '{items}',
    ));
    ?>
</div>
