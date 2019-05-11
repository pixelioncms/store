<?php

/**
 * ShopCommand command
 * 
 * 
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @uses CConsoleCommand
 * @package commands
 * 
 */
class ShopCommand extends ConsoleCommand {

    const productTable = '{{shop_product}}';

    public function run2($args) {



        //$this->existsTable();
        //$this->insertPeriod();
    }

    /**
     * Run каждый день в 12 часов.
     */
    public function actionAggregation_price(){
        $db = Yii::app()->db;
        $products = $db->createCommand()
            ->select('t.id, t.price, c.rate')
            ->from('{{shop_product}} t')
            ->join('{{shop_currency}} c', 't.currency_id=c.id')
            //->where('t.currency_id=:id', array(':id'=>2))
            ->queryAll(true);
        foreach ($products as $product){
            $value = $product['price']*$product['rate'];
           // $value = NULL;
            //$db->createCommand()->update('{{shop_product}}', array('aggregation_price'=>$value), 'id=:id', array(':id'=>$product['id']));
        }
        print_r($products);
    }

}
