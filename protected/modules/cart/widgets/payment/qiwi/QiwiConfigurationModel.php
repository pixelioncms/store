<?php

class QiwiConfigurationModel extends CModel {

    public $shop_id;
    public $password;

    /**
     * @return array
     */
    public function rules() {
        return array(
            array('shop_id, password', 'type')
        );
    }

    /**
     * @return array
     */
    public function attributeNames() {
        return array(
            'shop_id' => Yii::t('CartModule.payments', 'QIWI_ID'),
            'password' => Yii::t('CartModule.payments', 'QIWI_PWD'),
        );
    }

    /**
     * @return array
     */
    public function getForm() {
        $id = Yii::app()->request->getQuery('payment_method_id');
        if ($id === 'undefined')
            $successUrl = Yii::t('CartModule.payments', 'SUCCESS_TEXT');
        else
            $successUrl = Yii::app()->createAbsoluteUrl('/payment/process', array('payment_id' => $id)) . '?redirect=СCЫЛКА_СТРАНИЦЫ_УСПЕШНОЙ_ОПЛАТЫ';

        return array(
            'type' => 'form',
            'elements' => array(
                'shop_id' => array(
                    'label' => Yii::t('CartModule.payments', 'QIWI_ID'),
                    'type' => 'text',
                    'hint' => 'Пример: 2042',
                ),
                'password' => array(
                    'label' => Yii::t('CartModule.payments', 'QIWI_PWD'),
                    'type' => 'text',
                ),
                '<div class="row">
					<label>'.Yii::t('CartModule.payments', 'QIWI_SUCCESS_URL').'</label>
					' . $successUrl . '
				</div>
				'
                ));
    }

}
