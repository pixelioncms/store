<?php

class DeliveryController extends AdminController {

    public function actions() {
        return array(
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => ShopDeliveryMethod::model(),
            )
        );
    }
    public function allowedActions() {
        return 'renderConfigurationForm';
    }
    public function actionIndex() {
        $model = new ShopDeliveryMethod('search');
        $model->unsetAttributes();
        $this->icon = $this->module->adminMenu['orders']['items'][4]['icon'];
        if (!empty($_GET['ShopDeliveryMethod']))
            $model->attributes = $_GET['ShopDeliveryMethod'];

        $dataProvider = $model->search();
        $this->pageName = Yii::t('CartModule.admin', 'DELIVERY');

        $this->breadcrumbs = array(
            Yii::t('CartModule.default', 'MODULE_NAME') => array('/admin/cart'),
            $this->pageName
        );

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update delivery method
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new ShopDeliveryMethod;
            $model->unsetAttributes();
        } else {
            $model = ShopDeliveryMethod::model()
                    // ->language(Yii::app()->language)
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('CartModule.admin', 'NO_FOUND_DELIVERY'));


        $this->pageName = ($model->isNewRecord) ? $model::t('IS_CREATE', 0) : $model::t('IS_CREATE', 1);
        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/delivery.js', CClientScript::POS_END);


        $this->breadcrumbs = array(
            Yii::t('CartModule.admin', 'ORDER', 0) => array('/admin/cart'),
            Yii::t('CartModule.admin', 'DELIVERY') => $this->createUrl('index'),
            $this->pageName
        );



        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopDeliveryMethod'];

            if ($model->validate()) {
                $model->save();

                if ($model->delivery_system) {
                    $manager = new DeliverySystemManager;
                    $system = $manager->getSystemClass($model->delivery_system);
                    $system->saveAdminSettings($model->id, $_POST);
                }

                $this->redirect('index');
            }
        }

        $this->render('update', array('model' => $model));
    }

    /**
     * Delete method
     * @param array $id
     * @throws CHttpException
     */
    public function actionDelete($id = array()) {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopDeliveryMethod::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $m) {
                    if ($m->countOrders() == 0)
                        $m->delete();
                    else
                        throw new CHttpException(409, Yii::t('CartModule.admin', 'ERR_DEL_DELIVERY'));
                }
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

    /**
     * Renders delivery system configuration form
     */
    public function actionRenderConfigurationForm() {
        Yii::import('mod.cart.CartModule');
        $systemId = Yii::app()->request->getQuery('system');
        $deliveryMethodId = Yii::app()->request->getQuery('delivery_method_id');
        if (empty($systemId))
            exit;
        $manager = new DeliverySystemManager;
        $system = $manager->getSystemClass($systemId);
        echo $system->getConfigurationFormHtml($deliveryMethodId);
    }
}
