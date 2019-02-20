<?php

class CitesController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => ContactsCites::model(),
            )
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('ContactsModule.default', 'CITES');
        $model = new ContactsCites;
        if (Yii::app()->request->getPost('ContactsCites')) {
            $model->attributes = Yii::app()->request->getPost('ContactsCites');
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array('model' => $model, 'config' => Yii::app()->settings->get('contacts')));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new ContactsCites : ContactsCites::model()->findByPk($_GET['id']);
        $modelAddress = new ContactsAddress;
        $addressList = ContactsAddress::model()->findAllByAttributes(array('city_id' => $model->id));

        $this->pageName = ($model->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1);
        if (Yii::app()->request->getPost('ContactsCites')) {
            $model->attributes = Yii::app()->request->getPost('ContactsCites');
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        if (Yii::app()->request->getPost('ContactsAddress')) {
            $modelAddress->attributes = Yii::app()->request->getPost('ContactsAddress');
            $modelAddress->city_id = $_GET['id'];
            if ($modelAddress->validate()) {
                $modelAddress->save();
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        $this->render('update', array(
            'model' => $model,
            'modelAddress' => $modelAddress,
            'addressList' => $addressList
        ));
    }

}
