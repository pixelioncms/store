<?php

class SettingsController extends AdminController {

    public $topButtons = false;
    public $icon = 'icon-settings';

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SETTINGS');

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $model = new SettingsShopForm;
        $this->topButtons = array(
            array('label' => Yii::t('app', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'btn btn-default')
            )
        );
        if (isset($_POST['SettingsShopForm'])) {
            $model->attributes = $_POST['SettingsShopForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
