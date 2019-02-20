<?php

class DefaultController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
        );
    }

    /**
     * Display discounts list
     */
    public function actionIndex() {
        $this->pageName = Yii::t('DiscountsModule.default', 'MODULE_NAME');
        
        
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );
        
        $model = new ShopDiscount('search');

        if (!empty($_GET['ShopDiscount']))
            $model->attributes = $_GET['ShopDiscount'];

        $dataProvider = $model->orderByName()->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }


    /**
     * Update discount
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true)
            $model = new ShopDiscount;
        else
            $model = ShopDiscount::model()->findByPk($_GET['id']);

        if (!$model)
            throw new CHttpException(404, Yii::t('DiscountsModule.default', 'NO_FOUND_DISCOUNT'));




        $this->pageName = ($model->isNewRecord) ? Yii::t('DiscountsModule.default', 'Создание скидки') :
                Yii::t('DiscountsModule.admin', 'Редактирование скидки');


        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('DiscountsModule.default', 'MODULE_NAME') => $this->createUrl('index'),
            $this->pageName
        );
        
        Yii::app()->clientScript->registerScriptFile(
                $this->module->assetsUrl . '/admin/default.update.js', CClientScript::POS_END
        );

        if (Yii::app()->request->isPostRequest) {
            if (!isset($_POST['ShopDiscount']['manufacturers']))
                $model->manufacturers = array();
            if (!isset($_POST['ShopDiscount']['categories']))
                $model->categories = array();
            if (!isset($_POST['ShopDiscount']['userRoles']))
                $model->userRoles = array();

            $model->attributes = $_POST['ShopDiscount'];
        }

        $form = new TabForm($model->getForm(), $model);
        $form->additionalTabs[Yii::t('DiscountsModule.admin', 'Категории')] = array('content' => $this->renderPartial('_categories', array('model' => $model), true));

        if (Yii::app()->request->isPostRequest) {
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model, 'form' => $form));
    }

}