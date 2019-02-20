<?php
Yii::import('mod.shop.ShopModule');
class DefaultController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('Exchange1cModule.default','MODULE_NAME');
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );
        $model = new SettingsExchange1cForm;
        if (isset($_POST['SettingsExchange1cForm'])) {
            $model->attributes = $_POST['SettingsExchange1cForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
