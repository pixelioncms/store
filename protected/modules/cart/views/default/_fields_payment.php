<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title"><?= Yii::t('CartModule.default', 'PAYMENT_METHODS'); ?></div>
    </div>
    <div class="panel-body">
        <?php
        if ($paymenyMethods) {


            foreach ($paymenyMethods as $pay) {
                echo Html::activeRadioButton($form, 'payment_id', array(
                    'checked' => ($form->payment_id == $pay->id),
                    'uncheckValue' => null,
                    'value' => $pay->id,
                    'data-value' => Html::encode($pay->name),
                    'id' => 'payment_id_' . $pay->id,
                    'class' => 'payment_checkbox'
                ));
                echo Html::encode($pay->name);
            }
        } else {
            Yii::app()->tpl->alert('danger', 'Необходимо добавить способ оплаты!');
        }
        ?>
    </div>
</div>
