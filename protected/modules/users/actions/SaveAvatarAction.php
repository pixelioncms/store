<?php

class SaveAvatarAction extends CAction {

    public function run() {
        $avatar = (isset($_POST['img'])) ? $_POST['img'] : $_GET['img'];
        if (!Yii::app()->user->isGuest) {
            $user_id = Yii::app()->user->id;
            $user = User::model()->findByPk($user_id);
            if ($user->validate()) {
                $user->avatar = $avatar;
                $user->save(false,false,false);
                
                //Yii::app()->user->setFlash('messages', Yii::t('app', 'Changes saved successfully'));
                //$this->controller->setNotify(Yii::t('app', 'SUCCESS_SAVE'));
                //$this->redirect('/users/profile');
            } else {
                die(print_r($user->getErrors()));
            }
        } else {
            $this->controller->redirect('/users/profile');
        }
    }

}
