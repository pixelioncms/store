<?php

class MailTplController extends AdminController
{

    public function actions()
    {
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

    public function actionIndex()
    {
        $model = new MailTplModel('search');

        if (isset($_GET['MailTplModel'])) {
            $model->attributes = $_GET['MailTplModel'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionUpdate($new = false)
    {
        $model = ($new === true) ? new MailTplModel : MailTplModel::model()->findByPk($_GET['id']);

        if (!$model)
            $this->error404();


        $this->pageName = Yii::t('app', 'BLOCKS');
        $this->breadcrumbs = array(
            $this->pageName => Yii::app()->createUrl('admin/app/blocks'),
            ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
        );

        if (isset($_POST['MailTplModel'])) {
            $model->attributes = $_POST['MailTplModel'];
            if ($model->validate()) {

                $model->save();
                if ($model->isNewRecord) {
                    $this->redirect(array('index'));

                } else {
                    $this->redirect(array('update', 'id' => $model->id));
                }
            }
        }
        $this->render('update', array('model' => $model));

    }

}