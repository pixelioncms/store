<?php

Yii::import('zii.widgets.jui.CJuiDatePicker');

class JuiDatePicker extends CJuiDatePicker {

    public function run() {
        $this->language = Yii::app()->language;
        if (Yii::app()->controller instanceof AdminController){
            $this->htmlOptions['class'] = 'form-control';
            $this->htmlOptions['style'] = 'width:auto';
        }
        parent::run();
    }

}
