<?php

class ShareWidget extends BlockWidget {

    public $alias = 'ext.share';
    public $title;
    public $model;
    public $image;
    public function getTitle() {
        return Yii::t('default', 'share');
    }

    public function run() {

        $tags = Tag::model()->findTagWeights($this->config['maxTags']);
        $this->render($this->skin);
    }

}
