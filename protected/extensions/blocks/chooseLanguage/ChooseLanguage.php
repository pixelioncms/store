<?php

class ChooseLanguage extends CWidget {

    public function run() {
        $this->render($this->skin, array('language' => Yii::app()->languageManager));
    }

}