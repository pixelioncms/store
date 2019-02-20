<?php

class CategoriesController extends AdminController {

    public $icon = 'icon-books';

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
                'model' => CategoriesModel::model(),
            )
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'CATEGORIES');
        $this->breadcrumbs = array($this->pageName);
        $model = new CategoriesModel('search');
        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['CategoriesModel'])) {
            $model->attributes = $_GET['CategoriesModel'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new CategoriesModel : CategoriesModel::model()->findByPk($_GET['id']);
        $this->pageName = Yii::t('app', 'CATEGORIES');
        $this->breadcrumbs = array(
            $this->pageName => Yii::app()->createUrl('admin/app/categories'),
            ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
        );

        if (isset($_POST['CategoriesModel'])) {
            $model->attributes = $_POST['CategoriesModel'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

}
