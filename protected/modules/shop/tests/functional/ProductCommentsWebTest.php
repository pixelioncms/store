<?php

Yii::import('mod.shop.models.*');
Yii::import('mod.comments.models.*');

/**
 * Test adding comment to products
 */
class ProductCommentsWebTest extends WebTestCase {

    /**
     * Check creating new comment
     */
    public function testCreateComment() {
        // Find any active product
        $product = ShopProduct::model()->published()->find();
        $this->assertTrue($product instanceof ShopProduct);

        $email = 'tester@localhost.loc';
        $text = 'this is test comment' . microtime();

        // Open product page and post comment
        $this->open(Yii::app()->createAbsoluteUrl('/shop/product/view', array('seo_alias' => $product->seo_alias)));
        $this->type('id=Comment_name', 'tester');
        $this->type('id=Comment_email', $email);
        $this->type('id=Comment_text', $text);
        $this->clickAndWait("//input[@value='Отправить']");

        $this->open(Yii::app()->createAbsoluteUrl('/shop/product/view', array('seo_alias' => $product->seo_alias)));
        $this->assertTrue($this->isTextPresent('Ваш комментарий успешно добавлен. Он будет опубликован после проверки администратором.'));

        $this->adminLogin();
        $this->open('/admin/shop/products/update?id=' . $product->id);
        $this->click('xpath=//a[contains(.,"Отзывы")]');
        $this->assertTrue($this->isTextPresent($email));
        $this->assertTrue($this->isTextPresent($text));
    }

}
