<h5><?= Yii::t('CartModule.default', 'PAYMENT_METHODS'); ?></h5>

<?php
if ($paymenyMethods) {

    echo '<div class="form-group form-check">';
    foreach ($paymenyMethods as $pay) {
        echo Html::activeRadioButton($form, 'payment_id', array(
            'checked' => ($form->payment_id == $pay->id),
            'uncheckValue' => null,
            'value' => $pay->id,
            'data-value' => Html::encode($pay->name),
            'id' => 'payment_id_' . $pay->id,
            'class' => 'payment_checkbox  form-check-input'
        ));
        echo Html::label(Html::encode($pay->name),'payment_id_' . $pay->id,array('class'=>'form-check-label'));
    }
    echo '</div>';
} else {
    Yii::app()->tpl->alert('danger', Yii::t('CartModule.default','ALERT_PAYMENT'));
}


