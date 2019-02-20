<?php

class DefaultController extends AdminController {

    public $topButtons = false;

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex() {
        $model = new Comments('search');
        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/comments.index.js');

        $this->pageName = Yii::t('CommentsModule.default', 'MODULE_NAME');

        $this->breadcrumbs = array($this->pageName);
        if (!empty($_GET['Comments']))
            $model->attributes = $_GET['Comments'];

        $dataProvider = $model->search();
        $dataProvider->pagination->pageSize = 10;

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider
        ));
    }

    /**
     * Update comment
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id) {
        $model = Comments::model()->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('CommentsModule.default', 'NO_FOUND_COMMENT'));

        $this->pageName = Yii::t('CommentsModule.default', 'EDITED');

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Comments'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }

    public function actionUpdateStatus() {
        $ids = Yii::app()->request->getPost('ids');
        $switch = Yii::app()->request->getPost('switch');
        $models = Comments::model()->findAllByPk($ids);

        if (!array_key_exists($switch, Comments::getStatuses()))
            throw new CHttpException(404, Yii::t('CommentsModule.default', 'ERROR_UPDATE_STATUS'));

        if (!empty($models)) {
            foreach ($models as $comment) {
                $comment->switch = $switch;
                $comment->save();
            }
        }

        echo Yii::t('CommentsModule.default', 'SUCCESS_UPDATE_STATUS');
    }

}
