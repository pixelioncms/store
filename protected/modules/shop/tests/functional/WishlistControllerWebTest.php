<?php

/**
 * Wishlist controller test
 */
class WishlistControllerWebTest extends WebTestCase
{

	public function testWishlist()
	{
		Yii::import('application.modules.shop.models.wishlist.*');
		$wishlist = ShopWishlist::model()->find();
		$product  = ShopProduct::model()->published()->find();
		$this->assertTrue($product instanceof ShopProduct);

		$this->open(Yii::app()->createUrl('/shop/Product/view', array('url'=>$product->url)));
		$this->clickAndWait('xpath=//button[contains(.,"Список желаний")]');
		$this->assertTrue($this->isTextPresent('Авторизация'));
		$this->type('id=UserLoginForm_username', 'admin');
		$this->type('id=UserLoginForm_password', 'admin');

		// Click on login button
		$this->clickAndWait('css=input.blue_button');

		$this->open(Yii::app()->createUrl('/shop/Product/view', array('url'=>$product->url)));
		$this->assertTrue($this->isTextPresent('Список желаний'));
		$this->clickAndWait('xpath=//button[contains(.,"Список желаний")]');
		$this->assertTrue($this->isTextPresent('Продукт успешно добавлен в список желаний.'));
		$this->assertTrue($this->isTextPresent(str_replace('  ',' ',$product->name)));

		// View wishlist view
		$this->open(Yii::app()->createAbsoluteUrl('/shop/wishlist/view', array('key'=>$wishlist->key)));
		$this->assertTrue($this->isTextPresent('Список желаний'));
		$this->assertTrue($this->isTextPresent($product->name));
	}
}
