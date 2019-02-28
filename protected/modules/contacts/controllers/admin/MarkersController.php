<?php

class MarkersController extends AdminController {

    public function actionIndex() {
        $this->pageName = Yii::t('ContactsModule.default', 'ROUTER');

        $this->breadcrumbs = array(
            Yii::t('ContactsModule.default', 'MODULE_NAME') => array('/admin/contacts'),
            $this->pageName
        );
        $mapsCount = ContactsMaps::model()->count();

        if ($mapsCount < 1)
            $this->topButtons = false;

        $model = new ContactsMarkers;

        if (Yii::app()->request->getPost('ContactsMarkers')) {
            $model->attributes = Yii::app()->request->getPost('ContactsMarkers');
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array(
            'model' => $model,
            'mapsCount' => $mapsCount
        ));
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


        $model = ($new === true) ? new ContactsMarkers : ContactsMarkers::model()->findByPk($_GET['id']);
        $this->pageName = ($model->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1);
        $oldImage = $model->icon_file;

        $this->breadcrumbs = array(
            Yii::t('ContactsModule.default', 'MODULE_NAME') => array('/admin/contacts'),
            Yii::t('ContactsModule.default', 'MARKERS') => array('/admin/contacts/markers'),
            $this->pageName
        );

        if (Yii::app()->request->getPost('ContactsMarkers')) {
            $model->attributes = Yii::app()->request->getPost('ContactsMarkers');
            if ($model->validate()) {
                $model->uploadFile('icon_file', 'webroot.uploads', $oldImage);
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }

    public function actionDeletefile($id) {

        $model = ContactsMarkers::model()->findByPk($id);
        if (!$model)
            throw new CHttpException(403);


            if (file_exists(Yii::getPathOfAlias('webroot.uploads') . DS . $model->icon_file)) {
                $this->setNotify(Yii::t('app', 'FILE_DELETE_SUCCESS'));
                unlink(Yii::getPathOfAlias('webroot.uploads') . DS . $model->icon_file);
                 $this->redirect(array('index'));
            }

    }

}
