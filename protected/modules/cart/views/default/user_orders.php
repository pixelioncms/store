
<h1><?php echo $this->pageName; ?></h1>

<?php
Yii::import('ext.adminList.columns.TelColumn');
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'ordersListGrid',
    'dataProvider' => $orders->search(),
           'itemsCssClass' => 'table table-striped',
    'template' => '{items}',
    'columns' => array(
        array(
        'header' => Yii::t('CartModule.Order', '№'),
            'name' => 'id',
               'htmlOptions' => array('class' => 'text-center'),
            'value' => '$data->id',
        ),
        array(
            'class' => 'TelColumn',
            'name' => 'user_phone',
            'value' => '$data->user_phone',
        ),
        array(
            'name' => 'paid',
            'type' => 'html',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => array(1 => Yii::t('app', 'YES'), 0 => Yii::t('app', 'NO')),
            'value' => '$data->paid ? "<span class=\"label label-success\">".Yii::t("app", "YES")."</span>" : "<span class=\"label label-danger\">".Yii::t("app", "NO")."</span>"'
        ),
        array(
            'name' => 'status_id',
            'type' => 'html',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => CHtml::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '"<span class=\"label label-default\" style=\"background-color:#$data->status_color\">".$data->status_name."</span>"'
        ),
        array(
            'name' => 'delivery_id',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => CHtml::listData(ShopDeliveryMethod::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '$data->delivery_name'
        ),
        array(
            'header' => Yii::t('CartModule.Order', 'FULL_PRICE'),
            'type' => 'raw',
            'name' => 'full_price',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'Yii::app()->currency->number_format($data->full_price)." ".Yii::app()->currency->active->symbol',
        ),
        array(
            'header' => Yii::t('CartModule.Order', 'Заказ'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => 'CHtml::link("Просмотр заказа", array("/cart/default/view", "secret_key"=>$data->secret_key),array("class"=>"btn btn-info btn-xs"))',
        ),
    ),
));
?>