<?php

/**
 * @package components
 * @uses CFormModel
 */
Yii::import('app.traits.ImageUrl');

class FormModel extends CFormModel {

    use ImageUrl;

    protected $_attrLabels = array();
    protected $lang;

    const MODULE_ID = null;

    public function init() {
        $this->lang = Yii::app()->language;
        parent::init();
    }

    public static function t($message, $params = array()) {
        return Yii::t(ucfirst(static::MODULE_ID) . 'Module.' . get_called_class(), $message, $params);
    }

    public function getModuleId() {
        return static::MODULE_ID;
    }

    public function attributeLabels() {
        $model = get_class($this);
        $langFileAlias = "mod.{$this->getModuleId()}.messages.{$this->lang}";
        $filePath = Yii::getPathOfAlias($langFileAlias) . DS . $model . '.php';
        foreach ($this->attributes as $attr => $val) {
            $this->_attrLabels[$attr] = static::t(strtoupper($attr));
        }
        if (!file_exists($filePath)) {
            Yii::app()->user->setFlash('warning', 'Форма не может найти файл переводов: <b>' . $filePath . '</b> ');
        }
        return $this->_attrLabels;
    }

    public function save($message = true) {
        if ($message)
        // Yii::app()->controller->setNotify(Yii::t('app', 'SUCCESS_UPDATE'));
            Yii::app()->user->setFlash('success', Yii::t('app', 'SUCCESS_UPDATE'));
    }

    protected function beforeSave() {
        
    }

    protected function afterSave() {
        
    }

    /* public function validate($message = true, $attributes = null, $clearErrors = true) {
      if (parent::validate($attributes, $clearErrors)) {
      return true;
      } else {
      if ($message)
      Yii::app()->user->setFlash('error','eeeeeeee');
      //Yii::app()->controller->setNotify(Yii::t('app', 'ERROR_VALIDATE'));
      return false;
      }
      } */
}
