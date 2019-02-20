<?php

/**
 * Контроллер закладок пользователей.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.users.controllers
 * @uses Controller
 */
class FavoritesController extends Controller {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
                'model' => 'UserFavorites'
            ),
            'widget.' => 'mod.users.widgets.favorites.FavoritesWidget',
        );
    }

    public function actionIndex() {
        $alert = array();
        $model = new UserFavorites('search');
        $model->unsetAttributes();
        $this->pageTitle = Yii::t('UsersModule.default','FAVORITES');
        $limit = Yii::app()->settings->get('users', 'favorite_limit');

        $remains = $limit - $model->search()->totalItemCount;
        if ($remains) {
            $alert['text'] = Yii::t('UsersModule.default', 'FAVLIMIT', array('{LIMIT}' => $limit, '{REMAINS}' => $remains));
            $alert['type'] = 'info';
        } else {
            $alert['text'] = Yii::t('UsersModule.default', 'FAVFULL', array('{LIMIT}' => $limit));
            $alert['type'] = 'warning';
        }

        $return = (Yii::app()->request->isAjaxRequest) ? true : false;
        $this->render('index', array('model' => $model, 'alert' => $alert), false, $return);
    }

}
