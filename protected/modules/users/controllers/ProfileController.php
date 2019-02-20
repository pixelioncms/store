<?php

/**
 * Контроллер профиля пользователей.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers
 * @uses Controller
 */
class ProfileController extends Controller {

    public function actions() {
        return array(
            'widget.' => 'mod.users.widgets.webcam.Webcam',
            'getAvatars' => array(
                'class' => 'mod.users.actions.AvatarAction',
            ),
            'saveAvatar' => array(
                'class' => 'mod.users.actions.SaveAvatarAction',
            ),
        );
    }

    /**
     * Display profile start page
     */
    public function actionIndex() {
        if (!Yii::app()->user->isGuest) {
            $this->pageName = Yii::t('UsersModule.default', 'PROFILE');
            $this->pageTitle = $this->pageName;
            $this->breadcrumbs = array($this->pageName);

            Yii::import('mod.users.forms.ChangePasswordForm');
            $request = Yii::app()->request;
            $user = Yii::app()->user->getModel();
            //  if(!isset($user->service)){



            $oldAvatar = $user->avatar;
            if (isset($_POST['User'])) {
                $user->attributes = $_POST['User'];
                if ($user->validate()) {
                    $user->uploadFile('avatar', 'webroot.uploads.users.avatar', $oldAvatar);
                    $user->save();
                    Yii::app()->user->setFlash('success', Yii::t('app', 'SUCCESS_UPDATE'));
                    $this->refresh();
                }
            }

            //change password code
            $changePasswordForm = new ChangePasswordForm();
            $changePasswordForm->user = $user;
            $changePasswordSuccess=false;
            if ($request->getPost('ChangePasswordForm')) {
                $changePasswordForm->attributes = $request->getPost('ChangePasswordForm');

                if ($changePasswordForm->validate()) {
                    $user->password = User::encodePassword($changePasswordForm->new_password);
                    if ($user->save(false, false, false)) {
                        if (Yii::app()->settings->get('app', 'forum') != null) {
                            $forum = new CIntegrationForums;
                            $forum->changepassword($user->login, $changePasswordForm->new_password, $user->email);
                        }
                        $changePasswordSuccess=Yii::t('UsersModule.default', 'Пароль успешно изменен.');
                        Yii::app()->user->setFlash('change_success', Yii::t('UsersModule.default', 'Пароль успешно изменен.'));
                        //   $this->addFlashMessage(Yii::t('UsersModule.default', 'Пароль успешно изменен.'));
                       //  $this->refresh();
                    }
                }
            }

            $uConfig = Yii::app()->settings->get('users');
            $tabsArray = array(
                Yii::t('UsersModule.default', 'PROFILE') => array(
                    'content' => $this->renderPartial('_profile', array('user' => $user), true),
                    'id' => 'profile',
                    'visible' => true
                ),
                Yii::t('UsersModule.default', 'CHANGE_PASSWORD') => array(
                    'content' => $this->renderPartial('_changepass', array('changePasswordForm' => $changePasswordForm), true),
                    'id' => 'changepass',
                    'visible' => true
                ),
                Yii::t('common', 'MY_ORDERS') => array(
                    'ajax' => $this->createAbsoluteUrl('/cart/orders'),
                    'id' => 'orders',
                    'visible' => true
                ),
                Yii::t('UsersModule.default', 'FAVORITES') => array(
                    'ajax' => $this->createAbsoluteUrl('/users/favorites'),
                    'id' => 'favorites',
                    'visible' => $uConfig->favorites
                ),
            );
            $tabs = array();
            foreach ($tabsArray as $k => $tab) {
                if ($tabsArray[$k]['visible']) {
                    $tabs[$k] = $tabsArray[$k];
                }
            }


            $this->render('index', array(
                'user' => $user,
                'tabs' => $tabs,
                'changePasswordForm' => $changePasswordForm,
                'changePasswordSuccess'=>$changePasswordSuccess
            ));
        } else {
      
            $this->redirect('/');
        }
    }

    public function actionView($user_id) {
        $user = User::model()->findByPk((int) $user_id);

        if (!$user)
            $this->redirect('/');

        $this->pageTitle = Yii::t('UsersModule.default', 'PROFILE_NAME', array('{user_name}' => $user->username));
        $this->render('view', array('user' => $user));
    }



}
