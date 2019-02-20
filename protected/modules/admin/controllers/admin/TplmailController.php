<?php

class TplmailController extends AdminController {

    public function actions() {
        return array(
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex() {
        $this->render('index', array('model' => $model));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new TemplateMailModel : TemplateMailModel::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('app', 'BLOCKS');
            $this->breadcrumbs = array(
                $this->pageName => Yii::app()->createUrl('admin/app/blocks'),
                ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
            );

            if (isset($_POST['TemplateMailModel'])) {
                $model->attributes = $_POST['TemplateMailModel'];
                if ($model->validate()) {

                    $model->save();

                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

}