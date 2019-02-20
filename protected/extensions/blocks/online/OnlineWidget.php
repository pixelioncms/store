<?php

class OnlineWidget extends BlockWidget
{

    public $alias = 'ext.blocks.online';

    public function getTitle()
    {
        return 'Текущий онлайн';
    }

    public function init()
    {
        $this->registerClientScript();
        parent::init();
    }

    public function run()
    {
        $model = new Session('search');

        $this->render($this->skin, array(
            'model' => $model,
            'online' => Session::online(),
        ));
    }


    protected function registerClientScript()
    {
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        $cs = Yii::app()->clientScript;
        if (is_dir($this->assetsPath)) {
            $cs->registerCssFile($this->assetsUrl . '/online.css');
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }
}
