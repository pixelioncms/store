<?php

/**
 * 
 * @package widgets.modules.shop
 * @uses Widget
 */
class PopularBlock extends BlockWidget {


    public $limiter = 10;

    public function getTitle() {
        return 'Популярные товары';
    }

    public function run() {
        $cr = new CDbCriteria;
        $cr->with = array('translate');
        $cr->order = '`t`.`views` DESC';
        $data = new ActiveDataProvider('ShopProduct', array(
            'criteria' => $cr,
            'sort' => ShopProduct::getCSort(),
            'pagination' => array(
                'pageSize' => $this->limiter
            )
                )
        );
        $this->render($this->skin, array('data' => $data));
    }

}
