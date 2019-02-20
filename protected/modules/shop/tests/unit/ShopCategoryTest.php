<?php

class ShopCategoryTest extends CTestCase {

    public function testLoadRootCategory() {
        // test shop category for multilanguage mode
        $model = ShopCategory::model()->findByPk(1);
        $this->assertTrue($model instanceof ShopCategory);
    }

    public function testLanguageLoad() {
        Yii::app()->languageManager->setActive('en');
        $model = ShopCategory::model()->findByPk(1);
        $name = 'root_' . time();
        $model->name = $name;
        $model->saveNode();

        $this->assertTrue($model instanceof ShopCategory);
        $this->assertEquals($model->name, $name);

        Yii::app()->languageManager->setActive('ru');
        $model = ShopCategory::model()->findByPk(1);
        $this->assertEquals($model->name, 'root');
    }

}