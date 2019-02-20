<?php

/**
 * @author Andrew (panix) S. <andrew.panix@gmail.com>
 */
class FaviritesAction extends CAction {

    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            $action = $_POST['action'];
            $request = Yii::app()->request;
            if ($request->isAjaxRequest && !Yii::app()->user->isGuest) {
                $modelName = $_POST['model'];
                $mod = $_POST['mod'];

                Yii::import("mod.{$mod}.models.*");
                if ($action == 'add') {
                    $m = $modelName::model()->findByPk($_POST['id']);
                    if (isset($m)) {
                        $model = new UserFavorites;
                        $model->object_id = $m->primaryKey;
                        $model->owner_title = $m->getOwnerTitle();
                        $model->url = $m->getUrl();
                        $model->model_class = $m->getModelName();
                        $model->user_id = Yii::app()->user->id;
                        if ($model->save(false, false, false)) {
                            $this->controller->render('mod.users.widgets.favorites.views.remove', array(
                                'favorite_id' => $model->id,
                                'model' => $m,
                                'object_id' => $_POST['id'],
                                'view' => false
                            ));
                        }
                    }
                } elseif ($action == 'delete') {
                    $id = (int) $_POST['id'];
                    $model = (string) $_POST['model'];

                    $modelFavorite = UserFavorites::model()->findByPk($id);
                    $m = $model::model()->findByPk($modelFavorite->object_id);
                    if (isset($modelFavorite)) {
                        $modelFavorite->delete();
                        $this->controller->render('mod.users.widgets.favorites.views.add', array('model' => $m, 'view' => true, 'mod' => $mod));
                    }
                } else {
                    throw new CHttpException(400);
                }
            }
        } else {
            throw new CHttpException(401);
        }
    }

}
