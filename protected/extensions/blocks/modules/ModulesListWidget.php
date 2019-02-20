<?php

class ModulesListWidget extends BlockWidget {

    public function getTitle() {
        return 'Модули';
    }

    public function run() {
        $model = ModulesModel::model()->site()->published()->findAll();

        $this->render($this->skin, array('model' => $model));
    }

}