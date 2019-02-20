<?php

class CurrencyController extends AdminController {

    public $icon = 'icon-currencies';

    public function actionIndex() {
        $model = new ShopCurrency('search');

        if (!empty($_GET['ShopCurrency']))
            $model->attributes = $_GET['ShopCurrency'];

        $this->pageName = Yii::t('ShopModule.admin', 'CURRENCY');
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Update currency
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new ShopCurrency;
            $model->unsetAttributes();
        } else {
            $model = ShopCurrency::model()->findByPk($_GET['id']);
        }
        $isNew = $model->isNewRecord;
        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_CURRENCY'));

        $this->pageName = ($isNew) ? $model::t('IS_NEW', 0) : $model::t('IS_NEW', 1);
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'CURRENCY') => array('/admin/shop/currency'),
            $this->pageName
        );
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopCurrency'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }



    public function actionUpdateOld($new = false) {
        if ($new === true) {
            $model = new ShopCurrency;
            $model->unsetAttributes();
        } else {
            $model = ShopCurrency::model()->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_CURRENCY'));

        $form = new CMSForm($model->config, $model);
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopCurrency'];
            if ($model->validate()) {
                $model->save();

                $this->redirect(array('index'));
            }
        }
        $this->render('update', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    /**
     * Delete currency
     * @param array $id
     */
    public function actionDelete($id = array()) {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopCurrency::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $m) {
                    if ($m->is_main)
                        throw new CHttpException(404, Yii::t('ShopModule.admin', 'Ошибка. Удаление главной валюты запрещено.'));
                    if ($m->is_default)
                        throw new CHttpException(404, Yii::t('ShopModule.admin', 'Ошибка. Удаление валюты по умолчанию запрещено.'));

                    $m->delete();
                }
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

}
