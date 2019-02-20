<?php

class Info extends FormModel {

    public $errors = false;
    public $writeAble = array(
        'protected/config/_db.php',
        'protected/runtime',
        'assets',
        'uploads',
        'upgrade'
    );
    public $chmod = array(
        '.htaccess' => 666,
        'robots.txt' => 666,
        'uploads'=>750
    );

    public function getForm() {
        return new CMSForm(array(
            'showErrorSummary' => true,
            'attributes' => array('id' => __CLASS__, 'class' => ''),
            'elements' => array(
                Yii::app()->controller->renderPartial('info', array(
                    'writeAble' => $this->writeAble,
                    'chmod' => $this->chmod,
                    'model' => new self
                        ), true, false)
            ),
            'buttons' => array(
                'previous' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-default',
                    'label' => Yii::t('InstallModule.default', 'BACK')
                ),
                'submit' => array(
                    'type' => 'submit',
                    // 'visible' => ($this->hasErrors() == 'yes') ? false : true,
                    'class' => 'btn btn-success',
                    'label' => Yii::t('InstallModule.default', 'NEXT')
                ),
            )
                ), $this);
    }

    public function isWritable($path) {
        $fullPath = Yii::getPathOfAlias('webroot') . DS . $path;
        return is_writable($fullPath);
    }

    public function hasErrors($attribute = null) {
        foreach ($this->writeAble as $path) {
            $result = $this->isWritable($path);
            if ($result) {
                return false;
            } else {
                return true;
            }
        }
    }

}
