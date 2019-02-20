<?php

/**
 * Контроллер пользователей
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class DefaultController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'getAvatars' => array(
                'class' => 'application.modules.users.actions.AvatarAction',
            ),
            'saveAvatar' => array(
                'class' => 'application.modules.users.actions.SaveAvatarAction',
            ),
        );
    }

    /**
     * Список и фильрации пользователей.
     */
    public function actionIndex() {
        $model = new User('search');

        if (!empty($_GET['User']))
            $model->attributes = $_GET['User'];
        $this->pageName = Yii::t('UsersModule.default', 'MODULE_NAME');
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Создание/редактирование пользователя
     * @param boolean $new
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new User;
            $this->pageName = Yii::t('app', 'CREATE', 1);
        } else {
            $model = User::model()->findByPk($_GET['id']);
            $this->pageName = Yii::t('app', 'UPDATE', 1);
        }

        if (!$model)
            throw new CHttpException(400);


        $this->breadcrumbs = array(
            Yii::t('UsersModule.default', 'MODULE_NAME') => $this->createUrl('index'),
            ($model->isNewRecord) ? $model::t('CREATE_USER') : Html::encode($model->login),
        );
        $oldImage = $model->avatar;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                $model->uploadFile('avatar', 'webroot.uploads.users.avatar', $oldImage);
                $model->save();
                if ($model->role_id) {
                    Yii::app()->authManager->assign($model->role_id, $model->id);
                }
                if ($new === true)
                    Yii::app()->authManager->assign('Authenticated', $model->id);

                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }
}
