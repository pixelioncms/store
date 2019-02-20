<?php

class AddressController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => ContactsAddress::model(),
            )
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('ContactsModule.default', 'ADDRESS');
        $model = new ContactsAddress;
        $post = $_POST['ContactsAddress'];
        if (isset($post)) {
            $model->attributes = $post;
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array(
            'model' => $model,
            'config' => Yii::app()->settings->get('contacts')
                )
        );
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new ContactsAddress : ContactsAddress::model()->findByPk($_GET['id']);

        $this->pageName = ($model->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1);
        if (isset($_POST['ContactsAddress'])) {
            $model->attributes = $_POST['ContactsAddress'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,

        ));
    }

}
