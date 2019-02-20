<?php

class NotificationController extends AdminController {

    public $icon = 'icon-notification';

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'NOTIFICATION');
        $this->breadcrumbs = array($this->pageName);
        $this->topButtons = false;
        $model = new NotificationModel('search');
        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['NotificationModel'])) {
            $model->attributes = $_GET['NotificationModel'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new NotificationModel : NotificationModel::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('app', 'NOTIFICATION');
            $this->breadcrumbs = array(
                $this->pageName => Yii::app()->createUrl('admin/app/blocks'),
                ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
            );

            if (isset($_POST['NotificationModel'])) {
                $model->attributes = $_POST['NotificationModel'];
                if (!empty($model->modules))
                    $model->modules = implode(',', $_POST['BlocksModel']['modules']);
                if ($model->validate()) {

                    $model->save();
                    $this->redirect(array('index'));
                }
            } else {
                
            }

            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

}
