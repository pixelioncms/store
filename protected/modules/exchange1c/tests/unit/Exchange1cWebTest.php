<?php

class Exchange1cWebTest extends CTestCase {

    public function testExchange1cImport() {
        Yii::import('mod.exchange1c.components.C1ProductsImport');
        $tempDir = Yii::getPathOfAlias('application.runtime');

        $importXml = Yii::getPathOfAlias('mod.exchange1c.tests.data') . DS . 'import.xml';
        $offersXml = Yii::getPathOfAlias('mod.exchange1c.tests.data') . DS . 'offers.xml';

        copy($importXml, $tempDir . DS . 'import.xml');
        copy($offersXml, $tempDir . DS . 'offers.xml');

        $_GET['filename'] = 'import.xml';
        ob_start();
        C1ProductsImport::processRequest('catalog', 'import');
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertTrue(trim($result) == 'success');

        // Offers
        $_GET['filename'] = 'offers.xml';
        ob_start();
        C1ProductsImport::processRequest('catalog', 'import');
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertTrue(trim($result) == 'success');

        $import = new C1ProductsImport();
        $xml = $import->getXml('import.xml');
        $this->assertTrue($xml instanceof SimpleXMLElement);
        foreach ($xml->{"Каталог"}->{"Товары"}->{"Товар"} as $product) {
            $cr = new CDbCriteria();
            $cr->condition = 'translate.name=:name';
            $cr->params = array(':name' => $product->{"Наименование"});
            $model = ShopProduct::model()->applyTranslateCriteria()->find($cr);
            $this->assertTrue($model->name == $product->{"Наименование"});
        }

        // Check attribute
        $model = ShopProduct::model()->findByAttributes(array(
            'sku' => 'VAX-8002',
        ));
        $attr = 'eav_kod_virobnika';
        $this->assertTrue($model->$attr == 'VAX-8002');
        $this->assertTrue($model->price == '9');

        // Check category
        $this->assertTrue($model->mainCategory->name == 'Сумки для цифрового обладнання');
    }

}
