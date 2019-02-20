<?php

class ModulesController extends AdminController {

    public $icon = 'icon-puzzle';
    public $topButtons = false;

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
        );
    }

    public function actionIndex() {
        $model = new ModulesModel('search');
        $this->pageName = Yii::t('app', 'MODULES');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/default'), $this->pageName);
        $mod = new ModulesModel;
        if (count($mod->getAvailable()) && Yii::app()->user->openAccess(array('Admin.Modules.*','Admin.Modules.Install'))) {
            $this->topButtons = array(array(
                    'label' => Yii::t('admin', 'INSTALL', array('{n}' => count($mod->getAvailable()), 0)),
                    'url' => $this->createUrl('install'),
                    'htmlOptions' => array('class' => 'btn btn-success')
            ));
        } else {
            $this->topButtons = false;
        }
        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['ModulesModel'])) {
            $model->attributes = $_GET['ModulesModel'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionInstall($name = null) {
        // if (!isset($_GET['name']) && count(ModulesModel::getAvailable())) {
        $this->pageName = Yii::t('admin', 'LIST_MODULES');
        $this->breadcrumbs = array(
            Yii::t('app', 'MODULES') => $this->createUrl('index'),
            Yii::t('admin', 'INSTALL', 1),
        );
        $mod = $result = new ModulesModel;
        if ($name) {
            $result = ModulesModel::install($name);
            if ($result) {
                $this->redirect(array('index'));
            }
        }
        $this->render('install', array('modules' => $mod->getAvailable()));
    }

    public function actionUpdate() {
        $model = ModulesModel::model()->findByPk($_GET['id']);
        $this->pageName = Yii::t('app', 'MODULES');
        $this->breadcrumbs = array(
            $this->pageName => Yii::app()->createUrl('admin/app/modules'),
            Yii::t('app', 'UPDATE', 1)
        );

        if (isset($_POST['ModulesModel'])) {
            $model->attributes = $_POST['ModulesModel'];
            if ($model->validate()) {
                $model->save();
                Yii::app()->cache->delete('EngineMainMenu-' . Yii::app()->language);
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {
        if (Yii::app()->request->isPostRequest) {
            $model = ModulesModel::model()->findByPk($_GET['id']);
            $modname = $model->name;
            if ($model) {
                $model->delete();
                Yii::app()->cache->flush();
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

    public function actionInsertSql() {
        $model = ModulesModel::model()->findByAttributes(array('name' => $_GET['mod']));
        if ($model) {
            Yii::app()->db->import($model->name, 'insert.sql');
            Yii::app()->user->setFlash('success', 'База данных успешно импортирована.');
            $this->redirect(array('/admin/app/modules'));
        } else {
            Yii::app()->user->setFlash('error', 'Ошибка импорта база данных.');
            $this->redirect(array('/admin/app/modules'));
        }
    }

}
