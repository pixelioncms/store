<?php

class BaseDeliverySystem extends CComponent {

    /**
     * @return string
     */
    public function renderSubmit() {
        return '<input type="submit" class="btn btn-success" value="' . Yii::t('app', 'Оплатить') . '">';
    }

    /**
     * @param $deliveryMethodId
     * @param $data
     */
    public function setSettings($deliveryMethodId, $data) {
        Yii::app()->settings->set($this->getSettingsKey($deliveryMethodId), $data);
    }

    /**
     * @param $deliveryMethodId
     */
    public function getSettings($deliveryMethodId) {
        // die($this->getSettingsKey($paymentMethodId));
        return Yii::app()->settings->get($this->getSettingsKey($deliveryMethodId));
    }

    public static function log($message) {
        return Yii::log($message, 'info', 'payment');
    }

}