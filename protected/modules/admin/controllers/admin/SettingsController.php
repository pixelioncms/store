<?php

class SettingsController extends AdminController {

    public $icon = 'icon-settings';
    public $topButtons = false;
    public $path = 'current_theme.settings';

    public function actionIndex() {
        $model = new SettingsAppForm;
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs = array($this->pageName);
        if (isset($_POST['SettingsAppForm'])) {
            $model->attributes = $_POST['SettingsAppForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

    public function actionTheme() {
        if (file_exists(Yii::getPathOfAlias($this->path)) && file_exists(Yii::getPathOfAlias("{$this->path}") . DS . 'ThemeForm.php')) {
            $this->pageName = Yii::t('app', 'SETTINGS_THEME');
            $this->breadcrumbs = array(
                Yii::t('app', 'SETTINGS') => array('/admin/app/settings'),
                $this->pageName
            );

            Yii::import("{$this->path}.*");
            $model = new ThemeForm;
            if (isset($_POST['ThemeForm'])) {
                $model->attributes = $_POST['ThemeForm'];
                if ($model->validate()) {
                    $model->save();
                    $this->refresh();
                }
            }
        } else {
            throw new CHttpException(404, 'Настройки темы не найдены');
        }


        $this->render('index', array('model' => $model));
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS_THEME'),
                'url' => array('/admin/app/settings/theme'),
                'icon' => Html::icon('icon-settings'),
                'visible' => Yii::app()->user->openAccess(array('Admin.Settings.*', 'Admin.Settings.Theme'))
            ),
        );
    }

}
