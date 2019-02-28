<?php

class DefaultController extends AdminController
{

    public function actions()
    {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
                'flag' => true
            ),
        );
    }

    public function allowedActions()
    {
        return 'renderOrderedProducts, jsonOrderedProducts';
    }

    /**
     * Display orders methods list
     */
    public function actionIndex()
    {
        $this->pageName = Yii::t('CartModule.admin', 'ORDER', 0);
        $this->breadcrumbs = array($this->pageName);
        $model = new Order('search');

        if (!empty($_GET['Order']))
            $model->attributes = $_GET['Order'];

        $user_id = (int)Yii::app()->request->getParam('user_id', false);
        if ($user_id) {
            $userModel = User::model()->findByPk($user_id);
            $this->pageName = Yii::t('CartModule.admin', 'ORDERS_BY_USER', array('{username}' => $userModel->getFullName()));
            $this->breadcrumbs = array(
                Yii::t('CartModule.default', 'MODULE_NAME') => $this->createUrl('index'),
                $this->pageName
            );
            $model->applyUser($user_id);
        }
        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update order
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false)
    {
        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/orders.update.js', CClientScript::POS_END);

        if ($new === true) {
            $model = new Order;
            $model->unsetAttributes();
        } else
            $model = $this->_loadModel($_GET['id']);


        $title = ($model->isNewRecord) ? Yii::t('CartModule.admin', 'ORDER_TITLE_CREATE') :
            Yii::t('CartModule.admin', 'ORDER_TITLE_UPDATE', array('{id}' => $model->id));


        $this->breadcrumbs = array(
            Yii::t('CartModule.admin', 'ORDER', 0) => $this->createUrl('index'),
            $title,
        );
        $this->topButtons[] = array(
            'label' => Yii::t('CartModule.default', 'VIEW_ORDER', array('{id}' => $model->id)),
            'icon' => 'icon-view',
            'url' => $model->getUrl(),
            'htmlOptions' => array('class' => 'btn btn-primary', 'target' => '_blank')
        );
        if (Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.Print'))) {
            $this->topButtons[] = array(
                'label' => Yii::t('CartModule.default', 'PDF_ORDER'),
                'icon' => 'icon-print',
                'url' => array('/admin/cart/default/print', 'id' => $model->id),
                'htmlOptions' => array('class' => 'btn btn-primary', 'target' => '_blank')
            );


        }

        if (!$model->isNewRecord) {
            $status_state = $model->status_id;
        }
        $this->pageName = $title;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Order'];

            if ($model->validate()) {
                if (!$model->isNewRecord) {
                    if ($status_state != $model->status_id) {
                        $model->sendEmailFormChangeStatus();
                    }
                }
                $model->save();

                // Update quantities
                if (sizeof(Yii::app()->request->getPost('quantity', array())))
                    $model->setProductQuantities(Yii::app()->request->getPost('quantity'));

                $model->updateDeliveryPrice();
                $model->updateTotalPrice();


                if ($model->isNewRecord === false)
                    $template[] = 'delete';


// register all delivery methods to recalculate prices
                if (isset($deliveryMethods)) {
                    Yii::app()->clientScript->registerScript('deliveryMetohds', strtr('
	var deliveryMethods = {data};
', array(
                        '{data}' => CJavaScript::jsonEncode($deliveryMethods)
                    )), CClientScript::POS_END);
                }
                ///  if ($new) {
                //      $this->setNotify(Yii::t('app', 'Теперь Вы можете добавить товары.'));
                //}
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'deliveryMethods' => ShopDeliveryMethod::model()->applyTranslateCriteria()->orderByName()->findAll(),
            'paymentMethods' => ShopPaymentMethod::model()->findAll(),
            'statuses' => OrderStatus::model()->orderByPosition()->findAll(),
            'model' => $model,
        ));
    }

    /**
     * Display gridview with list of products to add to order
     */
    public function actionAddProductList()
    {

        $order_id = Yii::app()->request->getQuery('id');
        $model = $this->_loadModel($order_id);
        if ($order_id) {
            if (!Yii::app()->request->isAjaxRequest) {
                $this->redirect(array('/admin/cart/default/update', 'id' => $order_id));
            }
        }
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect(array('/admin/cart/default/index'));
        }

        $dataProvider = new ShopProduct('search');

        if (isset($_GET['ShopProduct']))
            $dataProvider->attributes = $_GET['ShopProduct'];

        $this->renderPartial('_addProduct', array(
            'dataProvider' => $dataProvider,
            'order_id' => $order_id,
            'model' => $model,
        ));
    }

    /**
     * Add product to order
     * @throws CHttpException
     */
    public function actionAddProduct()
    {
        if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->request->isAjaxRequest) {
                $order = $this->_loadModel($_POST['order_id']);
                $product = ShopProduct::model()->findByPk($_POST['product_id']);

                $find = OrderProduct::model()->findByAttributes(array('order_id' => $order->id, 'product_id' => $product->id));

                if ($find) {
                    if (Yii::app()->request->isAjaxRequest) {
                        $this->setJson(array(
                            'success' => false,
                            'message' => Yii::t('CartModule.admin', 'ERR_ORDER_PRODUCT_EXISTS'),
                        ));
                    } else {
                        throw new CHttpException(400, Yii::t('CartModule.admin', 'ERR_ORDER_PRODUCT_EXISTS'));
                    }
                }
                if (!$product) {
                    if (Yii::app()->request->isAjaxRequest) {
                        $this->setJson(array(
                            'success' => false,
                            'message' => Yii::t('CartModule.default', 'ERROR_PRODUCT_NO_FIND'),
                        ));
                    } else {
                        throw new CHttpException(404, Yii::t('CartModule.default', 'ERROR_PRODUCT_NO_FIND'));
                    }
                }

                $order->addProduct($product, $_POST['quantity'], $_POST['price']);
                $this->setJson(array(
                    'success' => true,
                    'message' => Yii::t('CartModule.admin', 'SUCCESS_ADD_PRODUCT_ORDER'),
                ));
            } else {
                throw new CHttpException(500, Yii::t('error', '500'));
            }
        } else {
            throw new CHttpException(500, Yii::t('error', '500'));
        }
    }

    /**
     * Render ordered products after new product added.
     * @param $order_id
     */
    public function actionRenderOrderedProducts($order_id)
    {
        $this->renderPartial('_orderedProducts', array(
            'model' => $this->_loadModel($order_id)
        ));
    }

    /**
     * Get ordered products in json format.
     * Result is displayed in the orders list.
     */
    public function actionJsonOrderedProducts()
    {
        $model = $this->_loadModel(Yii::app()->request->getQuery('id'));
        $data = array();

        foreach ($model->getOrderedProducts()->getData() as $product) {
            $data[] = array(
                'name' => $product->renderFullName,
                'quantity' => $product->quantity,
                'price' => Yii::app()->currency->number_format($product->price),
            );
        }

        echo CJSON::encode($data);
    }

    /**
     * Load order model
     * @param $id
     * @return Order
     * @throws CHttpException
     */
    protected function _loadModel($id)
    {
        $model = Order::model()->findByPk($id);

        if (!$model)
            $this->error404(Yii::t('CartModule.admin', 'ORDER_NOT_FOUND'));

        //if ($model->is_deleted)
        //throw new CHttpException(404, Yii::t('CartModule.admin', 'ORDER_ISDELETED'));

        return $model;
    }

    /**
     * Delete product from order
     */
    public function actionDeleteProduct()
    {
        $order = Order::model()->findByPk(Yii::app()->request->getPost('order_id'));

        if (!$order)
            $this->error404(Yii::t('CartModule.admin', 'ORDER_NOT_FOUND'));

        if ($order->is_deleted)
            throw new CHttpException(404, Yii::t('CartModule.admin', 'ORDER_ISDELETED'));

        $order->deleteProduct(Yii::app()->request->getPost('id'));
    }

    /**
     * Render order history tab
     */
    public function actionHistory()
    {
        $id = Yii::app()->request->getQuery('id');
        $model = Order::model()->findByPk($id);

        if (!$model)
            $this->error404(Yii::t('CartModule.admin', 'ORDER_NOT_FOUND'));
        if ($model->is_deleted)
            throw new CHttpException(404, Yii::t('CartModule.admin', 'ORDER_ISDELETED'));
        $this->render('_history', array(
            'model' => $model
        ));
    }

    public function actionPrint($id)
    {
        $model = $this->_loadModel($id);

        Yii::import('app.tcpdf.TCPDF');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle(Yii::t('CartModule.default', 'ORDER_ID', array('{id}' => $model->id)));
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, Yii::t('CartModule.default', 'ORDER_ID', array('{id}' => $model->id)), CMS::date($model->date_create));
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setJPEGQuality(100);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('freeserif', '', 12);


        $pdf->writeHTML($this->renderPartial('print', array(
            'model' => $model,
        ), true), true, 0, false, false);

        $pdf->Ln();
        $pdf->lastPage();
        $pdf->Output("orders_{$model->id}.pdf", 'I');
    }

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu()
    {
        return array(
            array(
                'label' => Yii::t('CartModule.admin', 'STATUSES'),
                'url' => array('/admin/cart/statuses'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Statuses.*', 'Cart.Statuses.Index')),
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'STATS'),
                'url' => array('/admin/cart/statistics'),
                'icon' => Html::icon('icon-stats'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Statistics.*', 'Cart.Statistics.Index')),
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'HISTORY'),
                'url' => array('/admin/cart/history'),
                'icon' => Html::icon('icon-history'),
                'visible' => Yii::app()->user->openAccess(array('Cart.History.*', 'Cart.History.Index')),
            ),
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/cart/settings'),
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Settings.*', 'Cart.Settings.Index')),
            ),
        );
    }

}
