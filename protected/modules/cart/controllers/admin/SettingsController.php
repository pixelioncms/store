<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs = array(
            Yii::t('CartModule.default', 'MODULE_NAME') => array('/admin/cart'),
            $this->pageName
        );
        $this->icon = $this->module->adminMenu['orders']['items'][7]['icon'];
        $model = new SettingsCartForm;
        $this->topButtons = array(
            array('label' => Yii::t('app', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'btn btn-default')
            )
        );
        if (isset($_POST['SettingsCartForm'])) {
            $model->attributes = $_POST['SettingsCartForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

    public function actionManual() {
        $this->render('manual');
    }

}
