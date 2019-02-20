<?php

Yii::import('mod.shop.ShopModule');

class DefaultController extends Controller
{

    /**
     * @var OrderCreateForm
     */
    public $form;

    /**
     * @var bool
     */
    protected $_errors = false;

    public function actions()
    {
        return array(
            'buyOneClick.' => 'mod.cart.widgets.buyOneClick.BuyOneClickWidget',
        );
    }

    public function init()
    {
        $this->form = new OrderCreateForm;
        parent::init();
    }

    public function actionRecount()
    {
        Yii::app()->request->enableCsrfValidation = false;
        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->request->isPostRequest && !empty($_POST['quantities'])) {
                $test = array();
                $test[Yii::app()->request->getPost('product_id')] = Yii::app()->request->getPost('quantities');
                Yii::app()->cart->ajaxRecount($test);
            }
        }
    }

    /**
     * Display list of product added to cart
     */
    public function actionIndex()
    {
        $cs = Yii::app()->clientScript;


        $cs->registerScript('numberformat', "
        var penny = " . Yii::app()->currency->active->penny . ";
        var separator_thousandth = '" . Yii::app()->currency->active->separator_thousandth . "';
        var separator_hundredth = '" . Yii::app()->currency->active->separator_hundredth . "';
        ", CClientScript::POS_HEAD);
        $cs->registerScriptFile(Yii::app()->getModule('shop')->assetsUrl . "/number_format.js", CClientScript::POS_BEGIN);

        $this->pageName = Yii::t('CartModule.default', 'MODULE_NAME');
        $this->pageTitle = $this->pageName;

        $this->breadcrumbs = array(
            //Yii::t('ShopModule.default', 'BC_SHOP') => array('/shop'),
            $this->pageName);
        if (Yii::app()->request->isPostRequest && Yii::app()->request->getPost('recount') && !empty($_POST['quantities'])) {

            $this->processRecount();
        }
        //$this->form = new OrderCreateForm;
        // Make order
        if (Yii::app()->request->isPostRequest && Yii::app()->request->getPost('create')) {

            if (isset($_POST['OrderCreateForm'])) {
                $this->form->attributes = $_POST['OrderCreateForm'];
                if ($this->form->validate()) {
                    $this->form->registerGuest();
                    $order = $this->createOrder();
                    //die('create order');
                    Yii::app()->cart->clear();
                    Yii::app()->user->setFlash('success', Yii::t('CartModule.default', 'SUCCESS_ORDER'));
                    Yii::app()->request->redirect($this->createUrl('view', array('secret_key' => $order->secret_key)));
                } else {
                    // print_r($this->form->getErrors());
                    // die;
                }
            }
        }

        $deliveryMethods = ShopDeliveryMethod::model()
            ->applyTranslateCriteria()
            ->published()
            //->orderByName()
            ->findAll();

        $paymenyMethods = ShopPaymentMethod::model()->findAll();

        $this->render('index', array(
            'items' => Yii::app()->cart->getDataWithModels(),
            'totalPrice' => Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice()),
            'deliveryMethods' => $deliveryMethods,
            'paymenyMethods' => $paymenyMethods,
        ));
    }

    public function actionPayment()
    {
        if (isset($_POST)) {
            $this->form = ShopPaymentMethod::model()
                //->cache($this->cacheTime)
                ->findAll();
            $this->render('_payment', array('model' => $this->form));
        }
    }
    public function actionDelivery()
    {
        if (isset($_POST)) {
            $this->form = ShopDeliveryMethod::model()
                //->cache($this->cacheTime)
                ->findAll();
            $this->render('_delivery', array('model' => $this->form));
        }
    }
    /**
     * Find order by secret_key and display.
     * @throws CHttpException
     */
    public function actionView()
    {

        $secret_key = Yii::app()->request->getParam('secret_key');
        $model = Order::model()
            //->cache($this->cacheTime)
            ->find('secret_key=:key', array(':key' => $secret_key));

        if (!$model)
            $this->error404(Yii::t('CartModule.default', 'ERROR_ORDER_NO_FIND'));


        $this->pageName = Yii::t('CartModule.default', 'VIEW_ORDER', array('{id}' => $model->id));
        $this->pageTitle = $this->pageName;
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'BC_SHOP') => array('/shop'),
            //  Yii::t('CartModule.default', 'MODULE_NAME') => array('/cart'),
            $this->pageName);


        $this->render('view', array(
            'model' => $model,
        ));
    }

    public function actionPrint()
    {

        $this->layout = '//layouts/print';
        $secret_key = Yii::app()->request->getParam('secret_key');
        $model = Order::model()
            //->cache($this->cacheTime)
            ->find('secret_key=:key', array(':key' => $secret_key));

        if (!$model)
            throw new CHttpException(404, Yii::t('CartModule.default', 'ERROR_ORDER_NO_FIND'));



        $this->pageName = Yii::t('CartModule.default', 'VIEW_ORDER', array('{id}' => $model->id));
        $this->pageTitle = $this->pageName;
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'BC_SHOP') => array('/shop'),
            //  Yii::t('CartModule.default', 'MODULE_NAME') => array('/cart'),
            $this->pageName);


        $this->render('print', array(
            'model' => $model,
        ));
    }

    /**
     * Validate POST data and add product to cart
     */
    public function actionAdd()
    {
        $variants = array();
        Yii::import('mod.shop.models.ShopProduct');
        // Load product model
        $model = ShopProduct::model()
            ->published()
            ->findByPk(Yii::app()->request->getPost('product_id', 0));

        // Check product
        if (!isset($model))
            $this->_addError(Yii::t('CartModule.default', 'ERROR_PRODUCT_NO_FIND'), true);

        // Update counter
        $model->saveCounters(array('added_to_cart_count' => 1));

        // Process variants
        if (!empty($_POST['eav'])) {
            foreach ($_POST['eav'] as $attribute_id => $variant_id) {
                if (!empty($variant_id)) {
                    // Check if attribute/option exists
                    if (!$this->_checkVariantExists($_POST['product_id'], $attribute_id, $variant_id))
                        $this->_addError(Yii::t('CartModule.default', 'ERROR_VARIANT_NO_FIND'));
                    else
                        array_push($variants, $variant_id);
                }
            }
        }

        // Process configurable products
        if ($model->use_configurations) {
            // Get last configurable item
            $configurable_id = Yii::app()->request->getPost('configurable_id', 0);

            if (!$configurable_id || !in_array($configurable_id, $model->configurations))
                $this->_addError(Yii::t('CartModule.default', 'ERROR_SELECT_VARIANT'), true);
        } else
            $configurable_id = 0;


        Yii::app()->cart->add(array(
            'product_id' => $model->id,
            'comment' => Yii::app()->request->getPost('comment'),
            'variants' => $variants,
            //'currency_id' => $model->currency_id,
            //'supplier_id' => $model->supplier_id,
            //  'category_id' => Yii::app()->request->getPost('category_id'),
            'configurable_id' => $configurable_id,
            'quantity' => (int)Yii::app()->request->getPost('quantity', 1),
            'price' => Yii::app()->request->getPost('product_price'),
        ));


        $this->_finish($model->name);
    }

    /**
     * Remove product from cart and redirect
     */
    public function actionRemove($id)
    {
        Yii::app()->cart->remove($id);

        if (Yii::app()->request->isAjaxRequest) {

            $this->setJson(array(
                'status' => 'success',
                'id' => $id,
                'total' => Yii::app()->cart->getTotalPrice(),
                'message'=>'Товар удален'
            ));

        } else {
            Yii::app()->request->redirect($this->createUrl('index'));
        }
    }

    /**
     * Clear cart
     */
    public function actionClear()
    {
        Yii::app()->cart->clear();

        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->request->redirect($this->createUrl('index'));
    }

    /**
     * Render data to display in theme header.
     */
    public function actionRenderSmallCart()
    {
        $skin = isset($_POST['skin']) ? $_POST['skin'] : 'default';
        $this->widget('cart.widgets.cart.CartWidget', array('skin' => $skin));
    }

    /**
     * Create new order
     * @return Order
     * @throws CHttpException
     */
    public function createOrder()
    {
        if (Yii::app()->cart->countItems() == 0)
            return false;
        Yii::import('mod.cart.models.Order');
        Yii::import('mod.cart.models.OrderProduct');
        $order = new Order('siteOrder');
//print_r($_POST);
//die;
        // Set main data
        $order->user_id = Yii::app()->user->isGuest ? null : Yii::app()->user->id;
        $order->user_name = $this->form->user_name;
        $order->user_email = $this->form->user_email;
        $order->user_phone = $this->form->user_phone;
        $order->user_address = $this->form->user_address;
        $order->user_comment = $this->form->user_comment;
        $order->delivery_id = $this->form->delivery_id;
        $order->payment_id = $this->form->payment_id;


        if ($order->validate()) {
            $order->save();
        } else {
            print_r($order->getErrors());
            die;
            throw new CHttpException(503, Yii::t('CartModule.default', 'ERROR_CREATE_ORDER'));
        }

        // Process products
        foreach (Yii::app()->cart->getDataWithModels() as $item) {
            $price = 0;
            $ordered_product = new OrderProduct;
            $ordered_product->order_id = $order->id;
            $ordered_product->product_id = $item['model']->id;
            //$ordered_product->category_id = $item['category_id'];
            $ordered_product->configurable_id = $item['configurable_id'];


            $ordered_product->currency_id = $item['model']->currency_id;
            $ordered_product->supplier_id = $item['model']->supplier_id;
            $ordered_product->name = $item['model']->name;
            $ordered_product->quantity = $item['quantity'];
            $ordered_product->sku = $item['model']->sku;
            $ordered_product->date_create = $order->date_create;
            // if($item['currency_id']){
            //     $currency = ShopCurrency::model()->findByPk($item['currency_id']);
            //$ordered_product->price = ShopProduct::calculatePrices($item['model'], $item['variant_models'], $item['configurable_id']) * $currency->rate;
            // }else{
            // 
            // $category = ShopCategory::model()->findByPk($item['category_id']);
            //  $options = $item['options'];
            if ($item['model']->appliedDiscount) {

                $price += Yii::app()->currency->convert($item['model']->discountPrice, $item['model']->currency_id);
            } else {
                $price += ShopProduct::calculatePrices($item['model'], $item['variant_models'], $item['configurable_id']);
            }


            $ordered_product->price = $price;

            // $ordered_product->price = ShopProduct::calculatePrices($item['model'], $item['variant_models'], $item['configurable_id']);
            // }
            // Process configurable product
            if (isset($item['configurable_model']) && $item['configurable_model'] instanceof ShopProduct) {
                $configurable_data = array();

                $ordered_product->configurable_name = $item['configurable_model']->name;
                // Use configurable product sku
                $ordered_product->sku = $item['configurable_model']->sku;
                // Save configurable data

                $attributeModels = ShopAttribute::model()
                    //->cache($this->cacheTime)
                    ->findAllByPk($item['model']->configurable_attributes);
                foreach ($attributeModels as $attribute) {
                    $method = 'eav_' . $attribute->name;
                    $configurable_data[$attribute->title] = $item['configurable_model']->$method;
                }
                $ordered_product->configurable_data = serialize($configurable_data);
            }

            // Save selected variants as key/value array
            if (!empty($item['variant_models'])) {
                $variants = array();
                foreach ($item['variant_models'] as $variant)
                    $variants[$variant->attribute->title] = $variant->option->value;
                $ordered_product->variants = serialize($variants);
            }

            $ordered_product->save();
        }

        // Reload order data.
        $order->refresh();
        // All products added. Update delivery price.
        $order->updateDeliveryPrice();
        // Send email to user.
        //$this->sendClientEmail($order);
        // Send email to admin.
        //$this->sendAdminEmail($order);


        //send user
        $this->sendEmail($order, 'user');
        //send admin
        $this->sendEmail($order, 'admin');
        return $order;
    }

    /**
     * Check if product variantion exists
     * @param $product_id
     * @param $attribute_id
     * @param $variant_id
     * @return string
     */
    protected function _checkVariantExists($product_id, $attribute_id, $variant_id)
    {
        return ShopProductVariant::model()
            //->cache($this->cacheTime)
            ->countByAttributes(array(
                'id' => $variant_id,
                'product_id' => $product_id,
                'attribute_id' => $attribute_id
            ));
    }

    /**
     * Recount product quantity and redirect
     */
    public function processRecount()
    {
        print_r(Yii::app()->request->getPost('quantities'));
        die;
        Yii::app()->cart->recount(Yii::app()->request->getPost('quantities'));

        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->request->redirect($this->createUrl('index'));
    }

    /**
     * Add message to errors array.
     * @param string $message
     * @param bool $fatal finish request
     */
    protected function _addError($message, $fatal = false)
    {
        if ($this->_errors === false)
            $this->_errors = array();

        array_push($this->_errors, $message);

        if ($fatal === true)
            $this->_finish();
    }

    /**
     * Process result and exit!
     */
    protected function _finish($product = null)
    {

        echo CJSON::encode(array(
            'errors' => $this->_errors,
            'message' => Yii::t('CartModule.default', 'SUCCESS_ADDCART', array(
                '{cart}' => Html::link(Yii::t('CartModule.default', 'CART'), array('/cart')),
                '{product_name}' => $product
            )),
        ));
        Yii::app()->end();
    }


    private function getProductImage($p)
    {
        if ($p->getMainImageUrl('50x50')) {
            return Html::image($this->createAbsoluteUrl($p->getMainImageUrl('50x50')), $p->name);
        } else {
            return Html::image($p->getMainImageUrl('50x50'), $p->name);
        }
    }

    private function sendEmail(Order $order, $type = 'admin')
    {
        $configCart = Yii::app()->settings->get('cart');
        $config = Yii::app()->settings->get('app');


        if ($type == 'admin') {
            $subject = Yii::t('CartModule.default', 'SUBJECT_MAIL_NEW_ORDER_ADMIN', array(
                '{id}' => $order->id
            ));
            $emails = explode(',', $configCart->order_emails);
        } else {
            $subject = Yii::t('CartModule.default', 'SUBJECT_MAIL_NEW_ORDER_USER', array(
                '{id}' => $order->id
            ));
            $emails = array($order->user_email);
        }

        $thStyle = 'border-color:#D8D8D8; border-width:1px; border-style:solid;';
        $tdStyle = $thStyle;
        $currency = Yii::app()->currency->active->symbol;

        $tables = '<table border="0" width="100%" cellspacing="1" cellpadding="5" style="border-spacing: 0;border-collapse: collapse;">'; //border-collapse:collapse;
        $tables .= '<tr>';
        $tables .= '<th style="' . $thStyle . '" colspan="2">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_NAME') . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_QUANTITY', 1) . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_PRICE_FOR') . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_TOTALPRICE') . '</th>';
        $tables .= '</tr>';
        foreach ($order->products as $row) { // Продажа розничная
            $tables .= '<tr>
            <td style="' . $tdStyle . '" align="center"><a href="' . $row->prd->absoluteUrl . '"  target="_blank">' . $this->getProductImage($row->prd) . '</a></td>
            <td style="' . $tdStyle . '"> ' . $row->getRenderFullName() . '</td>
            <td style="' . $tdStyle . '" align="center">' . $row->quantity . '</td>
            <td style="' . $tdStyle . '" align="center">' . Yii::app()->currency->number_format($row->price) . ' ' . $currency . '</td>
            <td style="' . $tdStyle . '" align="center">' . Yii::app()->currency->number_format($row->price * $row->quantity) . ' ' . $currency . '</td>
            </tr>';
        }


        $tables .= '</table>';

        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . Yii::app()->request->serverName;
        $mailer->FromName = $config->site_name;
        $mailer->Subject = $subject;
        $mailer->Body = $order->replace($tables, $configCart->tpl_body_admin);

        foreach ($emails as $mail) {
            $mailer->AddAddress($mail);
        }
        $mailer->AddReplyTo('noreply@' . Yii::app()->request->serverName);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }


    /**
     * Display user orders
     */
    public function actionOrders()
    {
        if(!Yii::app()->user->isGuest){
        Yii::import('mod.shop.models.*');

        $orders = new Order('search');
        $orders->user_id = Yii::app()->user->id;

        $this->pageName = Yii::t('common', 'MY_ORDERS');
        $this->render('user_orders', array(
            'orders' => $orders,
        ));
        }else{
            $this->error404();
        }
    }

}
