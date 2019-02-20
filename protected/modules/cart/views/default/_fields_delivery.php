<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title"><?= Yii::t('CartModule.default','DELIVERY_METHODS');?></div>
    </div>
    <div class="panel-body">


<?php
if($deliveryMethods){
foreach ($deliveryMethods as $delivery) {
    echo Html::activeRadioButton($form, 'delivery_id', array(
        'checked' => ($form->delivery_id == $delivery->id),
        'uncheckValue' => null,
        'value' => $delivery->id,
        'data-price' => Yii::app()->currency->convert($delivery->price),
        'data-free-from' => Yii::app()->currency->convert($delivery->free_from),
        'onClick' => 'cart.recountTotalPrice(this); ',
        'data-value' => Html::encode($delivery->name),
        'id' => 'delivery_id_' . $delivery->id,
        'class' => 'delivery_checkbox'
    ));
    echo Html::encode($delivery->name);
    if (isset($delivery->description)) {
        ?><p><?= $delivery->description ?></p>
    <?php }
}
}else{
    Yii::app()->tpl->alert('danger','Необходимо добавить способ доставки!');
}
?>
   </div>
       </div>