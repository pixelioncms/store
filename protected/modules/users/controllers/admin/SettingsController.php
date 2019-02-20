<?php
/**
 * Контроллер настройки пользователей.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app','SETTINGS');
        $this->breadcrumbs = array($this->pageName);




        $model = new SettingsUsersForm;

        $this->topButtons = array(
            array('label' => Yii::t('app', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'btn btn-outline-secondary')
            )
        );


        if (isset($_POST['SettingsUsersForm'])) {
            $model->attributes = $_POST['SettingsUsersForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
