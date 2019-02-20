<?php

class RouterController extends AdminController {

    public function actionIndex() {
        $this->pageName = Yii::t('ContactsModule.default', 'MARKERS');

        $this->breadcrumbs = array(
            Yii::t('ContactsModule.default', 'MODULE_NAME') => array('/admin/contacts'),
            $this->pageName
        );
        $mapsCount = ContactsMaps::model()->count();

        if ($mapsCount < 1)
            $this->topButtons = false;

        $model = new ContactsRouter('search');
        $model->unsetAttributes();
        if (Yii::app()->request->getPost('ContactsRouter')) {
            $model->attributes = Yii::app()->request->getPost('ContactsRouter');
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array('model' => $model, 'mapsCount' => $mapsCount));
    }

    /**
     * Действие редактирование и добавление
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        $mapsCount = ContactsMaps::model()->count();

        if ($mapsCount < 1)
            throw new CHttpException(403);


        $model = ($new === true) ? new ContactsRouter : ContactsRouter::model()->findByPk($_GET['id']);
        $this->pageName = ($model->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1);

        
        $this->breadcrumbs = array(
            Yii::t('ContactsModule.default', 'MODULE_NAME') => array('/admin/contacts'),
            Yii::t('ContactsModule.default', 'ROUTER') => array('/admin/contacts/router'),
            $this->pageName
        );

        if (Yii::app()->request->getPost('ContactsRouter')) {
            $model->attributes =Yii::app()->request->getPost('ContactsRouter');
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }

}
