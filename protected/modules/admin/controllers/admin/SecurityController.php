<?php

class SecurityController extends AdminController {

    public $icon = 'icon-security';

    public function actionIndex() {
        $this->topButtons = false;
        $model = new SettingsSecurityForm;
        $this->pageName = Yii::t('app', 'SECURITY');
        $this->breadcrumbs = array($this->pageName);
        if (isset($_POST['SettingsSecurityForm'])) {
            $post = $_POST['SettingsSecurityForm'];
            $model->attributes = $post;
            $model->backup_time_cache = CMS::time() - $post['backup_time'] * 60;
            if ($model->validate()) {
                $model->backup_time = $post['backup_time'] * 60;
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

    public function actionBanlist() {
        $model = new BannedIPModel('search');
        $this->pageName = Yii::t('admin', 'BANNED_IP');
        $this->breadcrumbs = array(
            Yii::t('app', 'SECURITY') => array('/admin/app/security'),
            $this->pageName
        );

        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['BannedIPModel'])) {
            $model->attributes = $_GET['BannedIPModel'];
        }
        $this->render('banlist', array('model' => $model));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new BannedIPModel : BannedIPModel::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('admin', 'BANNED_IP');
            $this->breadcrumbs = array(
                Yii::t('app', 'SECURITY') => array('/admin/app/security'),
                $this->pageName => array('/admin/app/security/banlist'),
                ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
            );
            if (isset($_POST['BannedIPModel'])) {
                $model->attributes = $_POST['BannedIPModel'];
                if ($model->validate()) {
                    $model->save();
                    $this->redirect(array('banlist'));
                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

    public function actionLogs() {
        $this->pageName = Yii::t('admin', 'LOGS');
        $this->breadcrumbs = array(
            Yii::t('app', 'SECURITY') => array('/admin/app/security'),
            $this->pageName
        );
        $this->render('logs', array());
    }

    public function actionClear() {
        $logFile = Yii::getPathOfAlias('application.runtime') . DS . 'application.log';
        if (file_exists($logFile)) {
            unlink($logFile);
            Yii::app()->user->setFlash('success', Yii::t('admin', 'SUCCESS_LOGS_CLEAR'));
        }
        $this->redirect(array('/admin/app/security/logs'));
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('admin', 'BANNED_IP'),
                'url' => array('/admin/app/security/banlist'),
                'icon' => Html::icon('icon-blocked'),
                'visible' => Yii::app()->user->openAccess(array('Admin.Security.*', 'Admin.Security.Banlist'))
            ),
            array(
                'label' => Yii::t('admin', 'LOGS'),
                'url' => array('/admin/app/security/logs'),
                'icon' => Html::icon('icon-log'),
                'visible' => Yii::app()->user->openAccess(array('Admin.Security.*', 'Admin.Security.Logs'))
            ),
        );
    }

}
