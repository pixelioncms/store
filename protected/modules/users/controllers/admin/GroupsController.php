<?php

/**
 * Контроллер пользователей
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class GroupsController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    /**
     * Список и фильрации пользователей.
     */
    public function actionIndex() {
        $model = new UserGroups('search');
        $model->unsetAttributes();
        if (!empty($_GET['UserGroups']))
            $model->attributes = $_GET['UserGroups'];
        $this->pageName = Yii::t('UsersModule.default', 'MODULE_NAME');
        $this->render('list', array(
            'model' => $model,
        ));
    }

    /**
     * Создание/редактирование пользователя
     * @param boolean $new
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new UserGroups;
            $this->pageName = Yii::t('app', 'CREATE', 1);
        } else {
            $model = UserGroups::model()->findByPk($_GET['id']);
            $this->pageName = Yii::t('app', 'UPDATE', 1);
        }

        if (!$model)
            throw new CHttpException(400);


        $this->breadcrumbs = array(
            Yii::t('UsersModule.default', 'MODULE_NAME') => $this->createUrl('index'),
            ($model->isNewRecord) ? $model::t('PAGE_TITLE', 0) : Html::encode($model->login),
        );
        $oldImage = $model->avatar;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['UserGroups'];
            if ($model->validate()) {
                $model->uploadFile('avatar', 'webroot.uploads.users.avatar', $oldImage);
                $model->save();

                if ($new === true)
                    Yii::app()->authManager->assign('Authenticated', $model->id);
                //$this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }

}
