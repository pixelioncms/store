<?php

/**
 * Admin order statuses
 */
class StatusesController extends AdminController {

    /**
     * Display statuses list
     */
    public function actionIndex() {
        $this->pageName = Yii::t('CartModule.admin', 'STATUSES');
        $this->icon = $this->module->adminMenu['orders']['items'][2]['icon'];
        $this->breadcrumbs = array(
            Yii::t('CartModule.admin', 'ORDER', 0) => array('/admin/cart'),
            $this->pageName
        );
        $model = new OrderStatus('search');
        $model->unsetAttributes();

        if (!empty($_GET['OrderStatus']))
            $model->attributes = $_GET['OrderStatus'];

        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update status
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new OrderStatus;
            $model->unsetAttributes();
        } else
            $model = OrderStatus::model()->findByPk($_GET['id']);

        if (!$model)
            throw new CHttpException(404, Yii::t('CartModule.admin', 'NO_STATUSES'));

        if (in_array($model->id,$model->disallow_update))
            throw new CHttpException(403, Yii::t('error', '403'));

        //if(in_array($model->id,$model->hidden_update))
            //throw new CHttpException(403, Yii::t('error', '403'));

        $this->icon = $this->module->adminMenu['orders']['items'][2]['icon'];
        $title = ($model->isNewRecord) ? Yii::t('CartModule.admin', 'CONTROL_STATUSES', 1) :
                Yii::t('CartModule.admin', 'CONTROL_STATUSES', 0);

        $this->breadcrumbs = array(
            Yii::t('CartModule.admin', 'ORDER', 0) => array('/admin/cart'),
            Yii::t('CartModule.admin', 'STATUSES') => $this->createUrl('index'),
            ($model->isNewRecord) ? Yii::t('CartModule.admin', 'CONTROL_STATUSES', 1) : Html::encode($model->name),
        );

        $this->pageName = $title;

        if (isset($_POST['OrderStatus'])) {
            $model->attributes = $_POST['OrderStatus'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Delete status
     * @param array $id
     * @throws CHttpException
     */
    public function actionDelete($id = array()) {
        if (Yii::app()->request->isPostRequest) {
            $model = OrderStatus::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $m) {
                    if ($m->countOrders() == 0 && $m->id != 1)
                        $m->delete();
                    else
                        throw new CHttpException(409, Yii::t('CartModule.admin', 'ERR_DELETE_STATUS'));
                }
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                'url' => array('/admin/cart'),
                'icon' => Html::icon('icon-cart'),
                'visible' => Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.Index')),
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
        );
    }

}
