
<h1><?php echo $this->pageName; ?></h1>
<div class=" table-responsive">
<?php
Yii::import('ext.adminList.columns.TelColumn');
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'ordersListGrid',
    'dataProvider' => $orders->search(),
    'itemsCssClass' => 'table',
    'template' => '{items}',
    'columns' => array(
        array(
            'header' => Yii::t('CartModule.Order', '№'),
            'name' => 'id',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => '$data->id',
        ),
        /*array(
            'class' => 'TelColumn',
            'name' => 'user_phone',
            'value' => '$data->user_phone',
        ),*/

        array(
            'name' => 'delivery_id',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => CHtml::listData(ShopDeliveryMethod::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '$data->delivery_name'
        ),
        array(
            'name' => 'payment_id',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => CHtml::listData(ShopPaymentMethod::model()->findAll(), 'id', 'name'),
            'value' => '$data->payment_name'
        ),
        /*array(
            'name' => 'date_create',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'CMS::date($data->date_create)'
        ),*/
        array(
            'name' => 'paid',
            'type' => 'html',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => array(1 => Yii::t('app', 'YES'), 0 => Yii::t('app', 'NO')),
            'value' => '$data->paid ? "<span class=\"badge badge-success\">".Yii::t("app", "YES")."</span>" : "<span class=\"badge badge-danger\">".Yii::t("app", "NO")."</span>"'
        ),
        array(
            'name' => 'status_id',
            'type' => 'html',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => CHtml::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '"<span class=\"badge badge-secondary\" style=\"background-color:#$data->status_color\">".$data->status_name."</span>"'
        ),
        array(
            'header' => Yii::t('CartModule.Order', 'FULL_PRICE'),
            'type' => 'raw',
            'name' => 'full_price',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'Yii::app()->currency->number_format($data->full_price)." ".Yii::app()->currency->active->symbol',
        ),

        
        array(
                'class' => 'ext.adminList.columns.ButtonColumn',
            'htmlOptions'=>array('style'=>'width:100px;'),
                'template' => '{print}{view}',
                'buttons' => array(
                    'print' => array(
                        'icon' => 'icon-print',
                        'label' => Yii::t('CartModule.default', 'PDF_ORDER'),
                        'url' => 'Yii::app()->createUrl("/cart/default/print", array("secret_key"=>$data->secret_key))',
                    ),
                    'view' => array(
                        'icon' => 'icon-eye',
                        'options' => array('class' => 'btn btn-info'),
                        'label' => Yii::t('CartModule.default', 'Просмотр'),
                        'url' => 'Yii::app()->createUrl("/cart/default/view", array("secret_key"=>$data->secret_key))',
                    ),
                ),
            ),
    ),
));
?>
</div>