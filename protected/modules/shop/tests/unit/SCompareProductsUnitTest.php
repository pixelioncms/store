<?php

/**
 * Unit tests for SCompareProducts class
 */
class SCompareProductsUnitTest extends CTestCase
{

	public function testCompare()
	{
		Yii::import('mod.shop.components.SCompareProducts');
		$product=ShopProduct::model()->published()->find();
		$model = new SCompareProducts();

		$this->assertTrue($model->session instanceof ArrayAccess);
		$this->assertTrue(is_array($model->getIds()));
		$this->assertTrue($model->add($product->id));
		$this->assertFalse($model->add(time()));
		$this->assertNotEmpty($model->getIds());
		$this->assertTrue($model->count() === 1);
		$products=$model->getProducts();
		$this->assertNotEmpty($products);
		$this->assertTrue($products[0] instanceof ShopProduct);
		$this->assertTrue(is_array($model->getAttributes()));
		$model->clear();
		$this->assertEmpty($model->getIds());

		// Removing
		$this->assertTrue($model->add($product->id));
		$model->remove($product->id);
		$this->assertEmpty($model->getIds());
	}

}
