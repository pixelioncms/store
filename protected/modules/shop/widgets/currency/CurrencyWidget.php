<?php

/**
 * Currency widget switch
 * 
 * @package widgets.modules.shop
 * @uses CWidget
 */
class CurrencyWidget extends CWidget {

    public function init() {
        parent::init();
    }

    public function run() {
        $q = Yii::app()->request->getQuery('q');
        $value = isset($q) ? $q : '';

        $this->render($this->skin, array('value' => $value));
    }

}
