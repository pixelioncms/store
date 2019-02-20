<?php

trait ModelTranslate {

    public static function t($message, $params = array()) {
        return Yii::t(ucfirst(static::MODULE_ID) . 'Module.' . get_called_class(), $message, $params);
    }

}