<?php

Yii::import('application.modules.shop.components.*');

class SProductsDuplicatorTest extends  CTestCase
{
	public function testProductDuplicate()
	{
		$model      = ShopProduct::model()->find();
		$duplicator = new SProductsDuplicator();

		$clone = $duplicator->duplicateProduct($model);
		$this->assertEquals($clone->name, $model->name.$duplicator->getSuffix());
	}
}