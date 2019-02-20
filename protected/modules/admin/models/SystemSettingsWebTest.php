<?php

class SystemSettingsWebTest extends WebTestCase {

    public function testSystemSettings() {
        $siteName = microtime();
        $ppa = rand(3, 10);

        $this->adminLogin();
        $this->open('/admin/app/settings');
        $this->type('id=SettingsAppForm_site_name', $siteName);
        $this->type('id=SettingsAppForm_pagenum', $ppa);
        $this->check('id=SettingsAppForm_multi_language');
        $this->clickAndWait('css=.buttons > input.buttonS');

        Yii::import('mod.admin.models.SettingsAppForm');
        Yii::app()->settings->init();
        $model = new SettingsAppForm;
        $this->assertEquals($model->core_site_name, $siteName);
        $this->assertEquals($model->core_pagenum, $ppa);
        $this->assertEquals(Yii::app()->settings->get('app', 'site_name'), $siteName);
        $this->assertEquals(Yii::app()->settings->get('app', 'multi_language'), 1);
    }

    public function testTitle() {
        Yii::app()->settings->set('app', array(
            'site_name' => microtime()
        ));
        $this->open('/');
        $this->assertEquals(Yii::app()->settings->get('app', 'site_name'), $this->getTitle());

        // Find any active product
        $product = ShopProduct::model()->published()->find();
        $this->assertTrue($product instanceof ShopProduct);

        // Open product page
        $this->open(Yii::app()->createUrl('/shop/product/view', array('seo_alias' => $product->seo_alias)));

        $this->assertEquals($product->name . ' / ' . Yii::app()->settings->get('app', 'site_name'), $this->getTitle());
    }

}
