<?php

class FavoritesWidget extends Widget {

    public static function actions() {
        return array(
            'favorite' => 'mod.users.widgets.favorites.actions.FaviritesAction',
        );
    }

    public $id;
    public $model;
    public $view = true;
    public $registerFile = array('favorites.js', 'favorites.css');

    public function init() {
        $this->assetsPath = dirname(__FILE__) . '/assets';
        parent::init();
    }

    public function run() {
        $session = new CHttpSession;
        $session->open();

        $surSess = Yii::app()->session->get("favorites");

        if (!Yii::app()->user->isGuest) {
            Yii::import('mod.users.models.UserFavorites');
            $modelClass = $this->model;
            $currentModule = Yii::app()->controller->module->id;
            echo Html::openTag('span', array('id' => 'fav' . $this->model->id, 'class' => 'favorite'));
            $modelc = UserFavorites::model()->findAll(array('condition' => '`t`.`user_id`=:userid', 'params' => array(':userid' => Yii::app()->user->getId())));

            if (count($modelc) < Yii::app()->settings->get('users', 'favorite_limit')) {

                if (isset($modelClass)) {
                    $model = UserFavorites::model()->find(array('condition' => '`t`.`user_id`=:userid AND `t`.`model_class`=:model_class AND `t`.`object_id`=:id',
                        'params' => array(
                            ':userid' => Yii::app()->user->getId(),
                            ':model_class' => $modelClass->getModelName(),
                            ':id' => $modelClass->id,
                    )));

                    if ($this->view && !isset($model)) { //
                        $view = "add";
                        $params = array('model' => $this->model, 'mod' => $currentModule);
                    } else {
                        $view = "remove";
                        $params = array('favorite_id' => $model->id, 'model' => $this->model, 'object_id' => $this->model->id, 'mod' => $currentModule);
                    }
                }
            }
            $this->render($view, $params);
            echo Html::closeTag('span');
        }
    }

}
