<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs = array(
            $this->module->name => $this->module->adminHomeUrl,
            $this->pageName
        );

        $model = new SettingsCsvForm;
        $this->topButtons = array(
            array('label' => Yii::t('app', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'btn btn-default')
            )
        );
        if (isset($_POST['SettingsCsvForm'])) {
            $model->attributes = $_POST['SettingsCsvForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
