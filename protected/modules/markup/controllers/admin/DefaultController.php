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
     * Display markup list
     */
    public function actionIndex() {
        $this->pageName = Yii::t('MarkupModule.default', 'MODULE_NAME');


        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $model = new ShopMarkup('search');

        if (!empty($_GET['ShopMarkup']))
            $model->attributes = $_GET['ShopMarkup'];

        $dataProvider = $model->orderByName()->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update markup
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true)
            $model = new ShopMarkup;
        else
            $model = ShopMarkup::model()->findByPk($_GET['id']);

        if (!$model)
            throw new CHttpException(404, Yii::t('MarkupModule.default', 'NO_FOUND_MARKUP'));




        $this->pageName = ($model->isNewRecord) ? Yii::t('MarkupModule.default', 'Создание скидки') :
                Yii::t('MarkupModule.admin', 'Редактирование скидки');


        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('MarkupModule.default', 'MODULE_NAME') => $this->createUrl('index'),
            $this->pageName
        );

        Yii::app()->clientScript->registerScriptFile(
                $this->module->assetsUrl . '/admin/default.update.js', CClientScript::POS_END
        );

        if (Yii::app()->request->isPostRequest) {
            if (!isset($_POST['ShopMarkup']['manufacturers']))
                $model->manufacturers = array();
            if (!isset($_POST['ShopMarkup']['categories']))
                $model->categories = array();
            if (!isset($_POST['ShopMarkup']['userRoles']))
                $model->userRoles = array();

            $model->attributes = $_POST['ShopMarkup'];
        }

        $form = new TabForm($model->getForm(), $model);
        $form->additionalTabs[Yii::t('MarkupModule.admin', 'Категории')] = array('content' => $this->renderPartial('_categories', array('model' => $model), true));

        if (Yii::app()->request->isPostRequest) {
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model, 'form' => $form));
    }

}
