<?php

class MapsController extends AdminController {

    /**
     * Центр карты, по умолчанию Одесса
     */
    const DEFAULT_CENTER = '46.469517,30.739846';

    public function actionIndex() {
        $this->pageName = Yii::t('ContactsModule.default', 'MAPS');

        $this->breadcrumbs = array(
            Yii::t('ContactsModule.default', 'MODULE_NAME') => array('/admin/contacts'),
            $this->pageName
        );
        $model = new ContactsMaps;
        if (Yii::app()->request->getPost('ContactsMaps')) {
            $model->attributes = Yii::app()->request->getPost('ContactsMaps');
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array('model' => $model));
    }

    /**
     * Действие редактирование и добавление
     * @param bool $new
     */
    public function actionUpdate($new = false) {
        $model = ($new === true) ? new ContactsMaps : ContactsMaps::model()->findByPk($_GET['id']);
        $this->pageName = ($model->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1);
        if ($model->isNewRecord)
            $model->center = self::DEFAULT_CENTER;

        $this->breadcrumbs = array(
            Yii::t('ContactsModule.default', 'MODULE_NAME') => array('/admin/contacts'),
            Yii::t('ContactsModule.default', 'MAPS') => array('/admin/contacts/maps'),
            $this->pageName
        );

        if (Yii::app()->request->getPost('ContactsMaps')) {
            $model->attributes = Yii::app()->request->getPost('ContactsMaps');
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }

}
