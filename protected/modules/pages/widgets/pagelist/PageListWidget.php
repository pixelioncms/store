<?php

class PageListWidget extends CWidget {


    public function run() {
        Yii::import('mod.pages.models.*');
        $model = Page::model()
                ->cache(3600*24)
                ->inMenu()
                ->language(Yii::app()->language)
                ->findAll();

        $this->render($this->skin, array('model' => $model));
    }

}