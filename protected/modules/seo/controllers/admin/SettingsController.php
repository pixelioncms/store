<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs = array(
            Yii::t('SeoModule.default', 'MODULE_NAME') => array('/admin/seo'),
            $this->pageName
        );
        $model = new SettingsSeoForm;
        $this->topButtons = array(
            array('label' => Yii::t('app', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'btn btn-outline-secondary')
            )
        );
        if (isset($_POST['SettingsSeoForm'])) {
            $model->attributes = $_POST['SettingsSeoForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

    public function getAddonsMenu() {
        return array(

            array(
                'label' => Yii::t('SeoModule.default', 'REDIRECTS'),
                'url' => array('/admin/seo/redirects'),
                'icon' => Html::icon('icon-refresh'),
                'visible'=>true
            ),
        );
    }

}
