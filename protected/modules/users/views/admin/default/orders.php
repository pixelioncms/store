

<?php

$petrovich = new Petrovich(Petrovich::GENDER_MALE);

$firstname = "Андрей";
$middlename = "Анатольевтич";
$lastname = "Семенов";

//echo $petrovich->detectGender(Petrovich::GENDER_MALE);	// Petrovich::GENDER_FEMALE (см. пункт Пол)

echo '<br /><strong>Родительный падеж:</strong><br />';
echo $petrovich->firstname($firstname, Petrovich::CASE_DATIVE) . '<br />'; //	Александра
echo $petrovich->middlename($middlename, Petrovich::CASE_DATIVE) . '<br />'; //	Сергеевича
echo $petrovich->lastname($lastname, Petrovich::CASE_DATIVE) . '<br />'; //	


Yii::import('ext.adminList.columns.TelColumn');
$this->widget('ext.adminList.GridView', array(
    'id' => 'ordersListGrid',
    'dataProvider' => $model->search(),
    'template' => '{items}',
    'autoColumns' => false,
    'enableCustomActions'=>false,
    'name' => "Список заказов пользователя №{$model->user_id}",
    'columns' => array(
        array(
            'header' => Yii::t('CartModule.Order', '№'),
            'name' => 'id',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => '$data->id',
        ),
        array(
            'name' => 'user_name',
            'value' => '$data->user_name',
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
            'value' => '$data->paid ? "<span class=\"badge badge-success\">".Yii::t("app", "YES")."</span>" : "<span class=\"badge badge-danger\">".Yii::t("app", "NO")."</span>"'
        ),
        array(
            'name' => 'status_id',
            'type' => 'html',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => CHtml::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '"<span class=\"badge badge-secondary\" style=\"background-color:$data->status_color\">".$data->status_name."</span>"'
        ),
        array(
            'name' => 'delivery_id',
            'type' => 'html',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => CHtml::listData(ShopDeliveryMethod::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '$data->delivery_name ? "$data->delivery_name":"<span class=\"badge badge-secondary\">".Yii::t("common", "NOT_INDICATED")."</span>"'
        ),
        array(
            'name' => 'payment_id',
            'type' => 'html',
            'htmlOptions' => array('class' => 'text-center'),
            'filter' => CHtml::listData(ShopPaymentMethod::model()->findAll(), 'id', 'name'),
            'value' => '$data->payment_name ? "$data->payment_name":"<span class=\"badge badge-secondary\">".Yii::t("common", "NOT_INDICATED")."</span>"'
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