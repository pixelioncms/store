<?php

/**
 * @package components
 * @uses FormModel
 */
class FormSettingsModel extends FormModel {

    protected $_attrLabels = array();
    private $_configName;

    const NAME = false;

    public function __construct($scenario = '') {
        $this->_configName = (static::NAME) ? static::NAME : $this->getModuleId();
        parent::__construct($scenario);
    }

    public function init() {

        $this->attributes = (array) Yii::app()->settings->get($this->_configName);

    }

    public function getModuleId() {
        return Yii::app()->controller->module->id;
    }

    public static function t($message, $params = array()) {
        return Yii::t(ucfirst(Yii::app()->controller->module->id) . 'Module.' . get_called_class(), $message, $params);
    }

    public function save($message = true) {
        Yii::app()->settings->set($this->_configName, $this->attributes);
        parent::save($message);
    }

}
