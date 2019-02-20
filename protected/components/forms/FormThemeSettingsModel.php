<?php

/**
 * @package components
 * @uses FormModel
 */
class FormThemeSettingsModel extends FormModel {
    /* protected $_attrLabels = array(); */

    public function uploadFile($attr, $old_image = null) {
        $file = CUploadedFile::getInstance($this, $attr);
        $path = Yii::getPathOfAlias('webroot.uploads') . DS;
        //TODO добавить проверку на наличие папки.
        if (isset($file)) {
            if ($old_image && file_exists($path . $old_image))
                unlink($path . $old_image);
            $newname = "logo." . $file->extensionName;
            if (in_array($file->extensionName, array('jpg', 'jpeg', 'png', 'gif'))) { //Загрузка для изображений
                $img = Yii::app()->img;
                $img->load($file->tempName);
                $img->save($path . $newname);
            } else {

                $this->addError($attr, 'Error format');
            }

            $this->$attr = (string) $newname;
        } else {

            $this->$attr = (string) $old_image;
        }
    }

    public static function t($message, $params = array()) {
        return Yii::t(get_called_class() . '.default', $message, $params);
    }

    public function attributeLabels() {
        $model = get_class($this);
        $langFileAlias = "current_theme.settings.messages.{$this->lang}";
        $filePath = Yii::getPathOfAlias($langFileAlias) . DS . $model . '.php';

        foreach ($this->attributes as $attr => $val) {
            $this->_attrLabels[$attr] = static::t(strtoupper($attr));
        }
        if (!file_exists($filePath)) {
            Yii::app()->user->setFlash('warning', 'Форма не может найти файл переводов: <b>' . $filePath . '</b> ');
        }
        return $this->_attrLabels;
    }

    public function init() {
       // print_r(Yii::app()->themeManager->get());die;
        $this->attributes = Yii::app()->themeManager->get();
        parent::init();
    }

    public function save($message = true) {

        Yii::app()->themeManager->set(Yii::app()->theme->name, $this->attributes);
        parent::save($message);
    }

}
