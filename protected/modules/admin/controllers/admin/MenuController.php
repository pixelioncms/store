<?php

class MenuController extends AdminController {

    public $icon = 'icon-menu';

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => MenuModel::model(),
            )
        );
    }

    public function actionIndex() {
        $model = new MenuModel('search');
        $this->pageName = Yii::t('app', 'ENGINE_MENU');
        $this->breadcrumbs = array($this->pageName);

        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['MenuModel'])) {
            $model->attributes = $_GET['MenuModel'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new MenuModel : MenuModel::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('app', 'ENGINE_MENU');
            $this->breadcrumbs = array(
                $this->pageName => Yii::app()->createUrl('admin/app/menu'),
                ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
            );
            if (isset($_POST['MenuModel'])) {
                $model->attributes = $_POST['MenuModel'];
                if ($model->validate()) {
                    $model->save();
                    $this->redirect(array('index'));
                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

}
