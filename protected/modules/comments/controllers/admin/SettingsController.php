<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app','SETTINGS');
        $this->breadcrumbs = array($this->pageName);
        $model = new SettingsCommentForm;
        $this->topButtons = array(
            array('label' => Yii::t('app', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'btn btn-secondary')
            )
        );

        if (isset($_POST['SettingsCommentForm'])) {
            $model->attributes = $_POST['SettingsCommentForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
