<?php

Yii::setPathOfAlias('webroot', Yii::getPathOfAlias('application') . DS . '..');

class ConsoleCommand extends CConsoleCommand
{
    public $useMemory;

    public function init()
    {
        echo 'Welcome to ' . Yii::app()->name . '!' . PHP_EOL;
        if (shell_exec('chcp 65001')) {
            echo 'chcp 65001 изменено(utf8)!' . PHP_EOL;
        }

        parent::init();
    }


    public function afterAction($action, $params, $exitCode = 0)
    {
        $m = memory_get_usage() - $this->useMemory;
        $pageload = number_format(Yii::getLogger()->getExecutionTime(), 3, '.', ' ');
        $mm = CMS::files_size($m);
        echo "Use memory: {$mm}, Page load: {$pageload} sec." . PHP_EOL;
        return parent::afterAction($action, $params, $exitCode);

    }

    public function beforeAction($action, $params)
    {
        $this->useMemory = memory_get_usage();

        if (Yii::app()->hasComponent('languageManager'))
            Yii::app()->languageManager->setActive('ru');

        return parent::beforeAction($action, $params);
    }
}