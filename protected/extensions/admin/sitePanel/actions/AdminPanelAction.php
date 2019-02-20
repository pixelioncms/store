<?php

/**
 * AdminPanelAction class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage admin.sitePanel.actions
 * @uses CAction
 * 
 */
class AdminPanelAction extends CAction {

    public function run() {
        if (Yii::app()->request->isAjaxRequest && Yii::app()->user->isSuperuser) {
            if (isset($_POST['e'])) {
                $user = User::model()->findByPk(Yii::app()->user->id);
                $user->edit_mode = $_POST['e'];
                $user->save(false,false,false);
            }
        } else {
            throw new CHttpException(403);
        }
    }

}
