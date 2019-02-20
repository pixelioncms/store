<?php

class Privat24ConfigurationModel extends CModel {

    public $MERCHANT_ID;
    public $MERCHANT_PASS;

    public function rules() {
        return array(
            array('MERCHANT_ID, MERCHANT_PASS', 'type')
        );
    }

    public function attributeNames() {
        return array(
            'MERCHANT_ID' => Yii::t('CartModule.payments', 'PRIVAT24_MERCHANT_ID'),
            'MERCHANT_PASS' => Yii::t('CartModule.payments', 'PRIVAT24_MERCHANT_PASS'),
        );
    }

    public function getForm() {
        return array(
            'type' => 'form',
            'elements' => array(
                'MERCHANT_ID' => array(
                    'label' => Yii::t('CartModule.payments', 'PRIVAT24_MERCHANT_ID'),
                    'type' => 'text',
                ),
                'MERCHANT_PASS' => array(
                    'label' => Yii::t('CartModule.payments', 'PRIVAT24_MERCHANT_PASS'),
                    'type' => 'text',
                ),
            )
        );
    }

}
