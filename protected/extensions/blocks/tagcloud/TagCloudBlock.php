<?php

class TagCloudBlock extends BlockWidget {

    public $alias = 'ext.blocks.tagcloud';

    public function getTitle() {
        return Yii::t('default', 'Облако');
    }

    public function run() {
        $module = Yii::app()->getModule(Yii::app()->controller->module->id);
        if (isset($module->tegRoute)) {
            $route = '/' . $module->tegRoute;
        } else {
            $route = '/' . Yii::app()->controller->route;
        }
        $tags = Tag::model()->findTagWeights($this->config['maxTags']);
        if (!empty($tags)) {
            foreach ($tags as $tag => $weight) {
                $link = Html::link(Html::encode($tag), array($route, 'tag' => $tag), array('title' => Html::encode($tag)));
                echo Html::tag('span', array(
                    'class' => 'tag',
                    'style' => "font-size:{$weight}pt",
                        ), $link) . "\n";
            }
        } else {
            Yii::app()->tpl->alert('warning', 'Нет неодного тега', false);
        }
    }

}
