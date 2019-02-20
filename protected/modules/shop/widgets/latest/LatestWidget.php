<?php

class LatestWidget extends CWidget {

    public $limit = 9;

    public function run() {
       $criteria = new CDbCriteria;
       // $criteria->condition = '`t`.`manufacturer_id`=67';
        $criteria->order='`t`.`date_create` DESC';
        $criteria->scopes = array('active');
        $criteria->limit = $this->limit;

        $provider = new ActiveDataProvider(ShopProduct::model()->cache(Yii::app()->controller->cacheTime), array('criteria' => $criteria, 'pagination' => array('totalItemCount' => $this->limit,'pageSize'=>$this->limit)));
        

 
                
        $this->render('view', array('provider' => $provider));
    }

}
