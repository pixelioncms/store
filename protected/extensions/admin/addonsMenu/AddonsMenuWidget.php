<?php

/**
 * AddonsMenuWidget 
 * 
 * @property array $menu Массив меню
 * @uses CWidget
 */
class AddonsMenuWidget extends CWidget {

    public function run() {
        $menu = isset(Yii::app()->controller->addonsMenu) ? Yii::app()->controller->addonsMenu : null;
        $this->render($this->skin, array('menu' => $menu));
    }

}

