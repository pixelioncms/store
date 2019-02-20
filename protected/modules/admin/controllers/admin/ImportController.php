<?php

class ImportController extends AdminController {

    public $noCopyImageArray = array();
    
    public function actionSetbrands() {

        $command = Yii::app()->db2->createCommand()
                        ->select('*')
                        ->from('{{shop_manufacturer}}')->queryAll(true);


        foreach ($command as $obj) {
            $model = new ShopManufacturer(); //comment behavier NestedSetBehavior
            $model->id = $obj['id'];

            $model->seo_alias = $obj['seo_alias'];
            $model->ordern = $obj['ordern'];
            $model->switch = $obj['switch'];
            $model->currency_id = $obj['currency_id'];
            $model->name = $obj['name'];
            $model->description = $obj['description'];
            $model->save(false, false, false); //comment beforesave rebuildFullPath()
        }
    }
    
    public function actionSetcategory() {

        $commandCategory = Yii::app()->db2->createCommand()
                        ->select('*')
                        ->from('{{shop_category}}')->queryAll(true);


        foreach ($commandCategory as $obj) {
            $model = new ShopCategory(); //comment behavier NestedSetBehavior
            $model->id = $obj['id'];

            $model->lft = $obj['lft'];
            $model->rgt = $obj['rgt'];
            $model->level = $obj['level'];
            $model->seo_alias = $obj['seo_alias'];
            $model->full_path = $obj['full_path'];
            $model->switch = $obj['switch'];
            $model->name = $obj['name'];
            $model->description = $obj['description'];
            $model->save(false, false, false); //comment beforesave rebuildFullPath()
        }
    }

    public function actionSetattr() {

        $commandattr = Yii::app()->db2->createCommand()
                        ->select('*')
                        ->from('{{shop_attribute}}')->queryAll(true);


        foreach ($commandattr as $obj) {
            $model = new ShopAttribute();
            $model->id = $obj['id'];

            $model->name = $obj['name'];
            $model->type = $obj['type'];
            $model->display_on_front = $obj['display_on_front'];
            $model->use_in_filter = $obj['use_in_filter'];
            $model->use_in_variants = $obj['use_in_variants'];
            $model->use_in_compare = $obj['use_in_compare'];
            $model->select_many = $obj['select_many'];
            $model->ordern = $obj['ordern'];
            $model->required = $obj['required'];
            $model->title = $obj['title'];
            $model->save(false, false, false);
        }


        $commandattroptions = Yii::app()->db2->createCommand()
                        ->select('*')
                        ->from('{{shop_attribute_option}}')->queryAll(true);



        foreach ($commandattroptions as $obj) {
            $model = new ShopAttributeOption();
            $model->id = $obj['id'];


            $model->attribute_id = $obj['attribute_id'];
            $model->ordern = $obj['ordern'];
            $model->value = $obj['value'];
            $model->save(false, false, false);
        }
    }

    public function actionIndex() {
        set_time_limit(0);

        // $this->truncateProducts();


        $lim = 5000;
        $command = Yii::app()->db2->createCommand()
                ->select('*')
                ->from('{{shop_product}}')
                //->limit($lim, 0) //limit, offset
                //->limit($lim, 5000)
                //->limit($lim, 10000)
                //->limit($lim, 15000)
                //->limit($lim, 20000)
                //->limit($lim, 25000)
                //->limit($lim, 30000)
                //->limit($lim, 35000)
                //->limit($lim, 40000)
                //->limit($lim, 45000)
                //->order('id ASC')
                //  ->offset()
                ->queryAll(true);

        foreach ($command as $obj) {


            $model = new ShopProduct();
            $model->id = $obj['id'];
            $model->type_id = 1;
            $model->manufacturer_id = $obj['manufacturer_id'];
            $model->currency_id = $obj['currency_id'];
            $model->manufacturer_id = $obj['manufacturer_id'];
            $model->name = $obj['name'];
            $model->sku = $obj['sku'];
            $model->price = $obj['price'];
            $model->seo_alias = $obj['seo_alias'];
            $model->switch = $obj['switch'];
            $model->views = $obj['views'];
            $model->discount = $obj['discount'];
            $model->short_description = $obj['short_description'];
            $model->full_description = $obj['full_description'];
            $model->availability = $obj['availability'];

            //$model->image = $obj['image'];
            //$model->main_category_id = $model->mainCategory->id;
            //  if ($model->validate()) {
            //$model->image = $this->setImage($obj['id'], $model);
            $model->save(false, false, false);
            // $this->processAttributes($model, $obj);
            //$this->getImages($obj['id'], $model->id);
            //$model->setCategories(array(), $this->getIdCategories($obj));
            // } else {
            //     print_r($model->getErrors());
            //     die;
            // }
        }
        print_r($this->noCopyImageArray);
        die;
    }

    public function setImage($old_product_id, $model) {
        $noCopyImageArray = array();
        $command = Yii::app()->db2->createCommand()
                ->select('*')
                ->from('relphoto')
                ->where('production_id=:id', array(':id' => $old_product_id))
                ->queryAll(true);
        foreach ($command as $obj) {
            if (file_exists(Yii::getPathOfAlias("imageDir.{$old_product_id}") . DS . $obj['file'])) {
                //$model = new ShopProductImage;
                //$model->product_id = $new_product_id;
                //$model->name = $obj['file'];
                //$model->is_main = $obj['cover'];

                if ($model->save(false, false, false)) {
                    if (!copy(Yii::getPathOfAlias("imageDir.{$old_product_id}") . DS . $obj['file'], Yii::getPathOfAlias("webroot.uploads.product") . DS . $obj['file'])) {
                        $this->noCopyImageArray[] = array('old_product_id' => $old_product_id, 'file' => $obj['file']);
                    }
                    return $obj['file'];
                }
            }
        }
    }

    public function getImages($old_product_id, $new_product_id) {
        $noCopyImageArray = array();
        $command = Yii::app()->db2->createCommand()
                ->select('*')
                ->from('relphoto')
                ->where('production_id=:id', array(':id' => $old_product_id))
                ->queryAll(true);
        foreach ($command as $obj) {
            if (file_exists(Yii::getPathOfAlias("imageDir.{$old_product_id}") . DS . $obj['file'])) {
                $model = new ShopProductImage;
                $model->product_id = $new_product_id;
                $model->name = $obj['file'];
                $model->is_main = $obj['cover'];

                if ($model->save(false, false, false)) {
                    if (!copy(Yii::getPathOfAlias("imageDir.{$old_product_id}") . DS . $obj['file'], Yii::getPathOfAlias("webroot.uploads.product") . DS . $obj['file'])) {
                        $this->noCopyImageArray[] = array('old_product_id' => $old_product_id, 'file' => $obj['file']);
                    }
                }
            }
        }
    }

    /**
     * *****************************************************************************
     * SECTION NO ADD...
     */
    public function actionSection() {
        $command = Yii::app()->db2->createCommand()->select('*')
                ->from('filter_section')
                ->queryAll(true);
        foreach ($command as $obj) {
            $model = new ShopAttributeOption();
            $model->attribute_id = 4;
            $model->value = $obj['name'];

            $model->save(false, false, false);
        }
    }

    public function actionSeason() {
        $command = Yii::app()->db2->createCommand()->select('*')
                ->from('filter_season')
                ->queryAll(true);
        foreach ($command as $obj) {
            $model = new ShopAttributeOption();
            $model->attribute_id = 3;
            $model->value = $obj['name'];

            $model->save(false, false, false);
        }
    }

    public function actionType() {
        $command = Yii::app()->db2->createCommand()->select('*')
                ->from('filter_type')
                ->queryAll(true);
        foreach ($command as $obj) {
            $model = new ShopAttributeOption();
            $model->attribute_id = 2;
            $model->value = $obj['name'];

            $model->save(false, false, false);
        }
    }

    public function actionSize() {
        $command = Yii::app()->db2->createCommand()->select('*')
                ->from('filter_size')
                ->queryAll(true);
        foreach ($command as $obj) {
            $model = new ShopAttributeOption();
            $model->attribute_id = 1;
            $model->value = $obj['name'];

            $model->save(false, false, false);
        }
    }

    public function actionManufacturer() {
        $command = Yii::app()->db2->createCommand()->select('*')
                ->from('filter_brand')
                ->queryAll(true);


        foreach ($command as $obj) {

            $model = new ShopManufacturer();
            $model->name = $obj['name'];
            $model->seo_alias = (!empty($obj['seo_alias'])) ? $obj['seo_alias'] : CMS::translit($obj['name']);
            $model->image = $obj['image'];
            $model->serious = $obj['serious'];
            $model->rostovkaSold = $obj['rostovkaSold'];
            $model->currency_id = $obj['dollarUse'];
            $model->save(false, false, false);
        }
    }

    private function addColumnsManufacturer() {
        $ShopManufacturer = new ShopManufacturer();
        if (!isset($ShopManufacturer->tableSchema->columns['serious'])) {
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_manufacturer}}', 'serious', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_manufacturer}}', 'rostovkaSold', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_manufacturer}}', 'dollarUse', "tinyint(1) DEFAULT '0'");
        }

        $ShopProduct = new ShopProduct();
        if (!isset($ShopManufacturer->tableSchema->columns['serious'])) {
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'in_ros', "INT NOT NULL");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'in_box', "INT NOT NULL");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp1', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp2', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp3', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp4', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp5', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp6', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp7', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp8', "tinyint(1) DEFAULT '0'");
            $success = Yii::app()->db->createCommand()->addColumn('{{shop_product}}', 'stamp9', "tinyint(1) DEFAULT '0'");
        }
    }

    private function truncateProducts() {
        Yii::app()->db->createCommand()->truncateTable('{{seo_url}}');

        Yii::app()->db->createCommand()->truncateTable('{{shop_product}}');
        Yii::app()->db->createCommand()->truncateTable('{{shop_product_translate}}');
        Yii::app()->db->createCommand()->truncateTable('{{shop_product_image}}');
        Yii::app()->db->createCommand()->truncateTable('{{shop_product_category_ref}}');
        Yii::app()->db->createCommand()->truncateTable('{{shop_product_attribute_eav}}');
        Yii::app()->db->createCommand()->truncateTable('{{order}}');
        Yii::app()->db->createCommand()->truncateTable('{{order_product}}');
        CFileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.product'), array('traverseSymlinks' => true));
        if (!file_exists(Yii::getPathOfAlias('webroot.uploads.product')))
            CFileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.product'), 0777);
        die('truncate success');
    }

    private function getIdCategories($obj) {
        if ($obj['sex_id'] == 1) {//Для мужчин 1
            return 5;
        } elseif ($obj['sex_id'] == 2) {//Для женщин 2
            return 4;
        } elseif ($obj['sex_id'] == 3) {//Для мальчиков 3
            return 2;
        } elseif ($obj['sex_id'] == 4) { //Для девочек 4
            return 3;
        }
    }

    public function actionDeleteTranslates() {
        $models = array(
            'ShopAttributeTranslate',
            'ShopAttributeGroupsTranslate',
            'ShopAttributeOptionTranslate',
            'ShopCategoryTranslate',
            'ShopManufacturerTranslate',
            'ShopProductTranslate',
            'BlocksModelTranslate',
                //'PageTranslate',
        );
        foreach ($models as $model) {
            $test = $model::model()->findAllByAttributes(array('language_id' => 2));
            foreach ($test as $obj) {
                $obj->delete();
            }
        }
    }

}
