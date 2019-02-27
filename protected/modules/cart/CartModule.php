<?php

/**
 * Модуль корзины
 * 
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @license http://corner-cms.com/license.txt CORNER CMS License
 * @link http://corner-cms.com CORNER CMS
 * @package modules
 * @subpackage commerce.cart
 * @uses WebModule
 */
class CartModule extends WebModule {

    public $countOrder;
    public $tpl_keys = array(
        '{order_id}',
        '{order_key}',
        '{order_delivery_name}',
        '{order_payment_name}',
        '{total_price}',
        '{user_name}',
        '{user_phone}',
        '{user_email}',
        '{user_address}',
        '{user_comment}',
        '{current_currency}',
        '{for_payment}',
        '{list}',
        '{link_to_order}',
    );

    public $configFiles = array(
        'cart' => 'SettingsCartForm'
    );

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
            $this->id . '.components.payment.*',
            $this->id . '.components.delivery.*'
        ));
        $this->setIcon('icon-cart');
    }

    public function getCountOrder(){
        if(Yii::app()->db->schema->getTable(Order::model()->tableName())){
            return Order::model()->new()->count();
        }
    }


    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            return parent::afterInstall();
        } else {
            Yii::app()->controller->setNotify('Ошибка, Модуль интернет-магазин не устрановлен.', 'error');
            return false;
        }
    }

    public function afterUninstall() {
        Yii::app()->unintallComponent('cart');
        $db = Yii::app()->db;
        $tablesArray = array(
            Order::model()->tableName(),
            OrderHistory::model()->tableName(),
            OrderProduct::model()->tableName(),
            OrderStatus::model()->tableName(),
            OrderProductHistroy::model()->tableName(),
            ShopPaymentMethod::model()->tableName(),
            ShopPaymentMethodTranslate::model()->tableName(),
            ShopDeliveryMethod::model()->tableName(),
            ShopDeliveryMethodTranslate::model()->tableName(),
            ShopDeliveryPayment::model()->tableName(),
            ProductNotifications::model()->tableName(),
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        return parent::afterInstall();
    }

    public function getAddonsArray() {
        return array(
            'mainButtons' => array(
                array(
                    'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                    'url' => $this->adminHomeUrl,
                    'icon' => Html::icon($this->icon, array('class' => 'icon-x4 display-block')),
                    'count' => $this->countOrder,
                    'visible'=>Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.Index'))

                )
            )
        );
    }

    public function getRules() {
        return array(
            'cart' => 'cart/default/index',
            'cart/add' => 'cart/default/add',
            'cart/remove/<id:(\d+)>' => 'cart/default/remove',
            'cart/clear' => 'cart/default/clear',
            'cart/renderSmallCart' => 'cart/default/renderSmallCart',
            'cart/payment' => 'cart/default/payment',
            'cart/delivery' => 'cart/default/delivery',
            'cart/recount' => 'cart/default/recount',
            'cart/view/<secret_key>' => 'cart/default/view',
            'cart/print/<secret_key>' => 'cart/default/print',
            'cart/processPayment/*' => 'cart/payment/process',
            'cart/processDelivery/*' => 'cart/delivery/process',
            'cart/getAddressList' => 'cart/default/getAddressList',
            'notify' => array('cart/notify/index'),
            'cart/<action:[.\w]+>' => 'cart/default/<action>',
            'cart/<action:[.\w]>/*' => 'cart/default/<action>',
        );
    }

    public function getAdminMenu() {
        return array(
            'orders' => array(
                'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                //'url' => array('/admin/cart'),
                'active' => $this->getIsActive('cart'),
                'icon' => Html::icon($this->icon),
                'itemOptions' => array('class' => 'circle-orders'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.Index')),
                'count' => $this->countOrder,
                'items' => array(
                    array(
                        'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                        'url' => array('/admin/cart'),
                        'active' => $this->getIsActive('cart/default'),
                        'icon' => Html::icon('icon-cart'),
                        'visible' => Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.Index')),
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'STATS'),
                        'url' => array('/admin/cart/statistics'),
                        'icon' => Html::icon('icon-stats'),
                        'active' => $this->getIsActive('cart/statistics'),
                        'visible' => Yii::app()->user->openAccess(array('Cart.Statistics.*', 'Cart.Statistics.Index')),
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'STATUSES'),
                        'url' => array('/admin/cart/statuses'),
                        'icon' => Html::icon('s'),
                        'active' => $this->getIsActive('cart/statuses'),
                        'visible' => Yii::app()->user->openAccess(array('Cart.Statuses.*', 'Cart.Statuses.Index')),
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'HISTORY'),
                        'url' => array('/admin/cart/history'),
                        'icon' => Html::icon('icon-history'),
                        'active' => $this->getIsActive('cart/history'),
                        'visible' => Yii::app()->user->openAccess(array('Cart.History.*', 'Cart.History.Index')),
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'DELIVERY'),
                        'url' => array('/admin/cart/delivery'),
                        'active' => $this->getIsActive('cart/delivery'),
                        'icon' => Html::icon('icon-delivery'),
                        'visible' => Yii::app()->user->openAccess(array('Cart.Delivery.*', 'Cart.Delivery.Index')),
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'PAYMENTS'),
                        'url' => array('/admin/cart/paymentMethod'),
                        'active' => $this->getIsActive('cart/paymentMethod'),
                        'icon' => Html::icon('icon-creditcard'),
                        'visible' => Yii::app()->user->openAccess(array('Cart.PaymentMethod.*', 'Cart.PaymentMethod.Index')),
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'NOTIFIER'),
                        'url' => array('/admin/cart/notify'),
                        'active' => $this->getIsActive('cart/notify'),
                        'icon' => Html::icon('icon-envelope'),
                        'visible' => Yii::app()->user->openAccess(array('Cart.Notify.*', 'Cart.Notify.Index')),
                    ),
                    array(
                        'label' => Yii::t('app', 'SETTINGS'),
                        'url' => Yii::app()->createUrl('/admin/cart/settings'),
                        'active' => $this->getIsActive('cart/settings'),
                        'icon' => Html::icon('icon-settings'),
                        'visible' => Yii::app()->user->openAccess(array('Cart.Settings.*', 'Cart.Settings.Index')),
                    ),
                )
            ),
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu;
        $items = $mod->findMenu('shop');
        return $items['items'];
    }

    /**
     * Версия модуля
     * @return string
     */
    public function getVersion() {
        return '1.0 PRO';
    }

    public static function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
       // $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
       // $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            //$cs->registerScriptFile($baseUrl . '/cart.js', CClientScript::POS_BEGIN);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
