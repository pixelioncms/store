<?php

/**
 * 
 * @package commerce.shop.widgets
 * @uses CWidget
 */
class SearchWidget extends BlockWidget {

    public function getTitle() {
        return 'Поиск товаров';
    }

    public function run() {
        $q = Yii::app()->request->getQuery('q');
        $value = isset($q) ? $q : '';

        $this->render($this->skin, array('value' => $value));
    }

}
