<?php

Yii::import('ext.like.LikeModel');

class LikeAction extends CAction {

    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            header('Content-Type: application/json');
            $object_id = (int) $_POST['object_id'];
            $type = $_POST['type'];
            $modelClass = $_POST['m'];
            if (isset($modelClass) && isset($object_id)) {
                $model = $modelClass::model()->findByPk($object_id);
                if (isset($model)) {
                    if ($type == 'up') {
                        $model->like+=1;
                    } elseif ($type == 'down') {
                        $model->like-=1;
                    } else {
                        throw new CHttpException(500, 'Не верный тип');
                    }
                    $model->saveLike($type);

                } else {
                    throw new CHttpException(404);
                }
            } else {
                throw new CHttpException(404);
            }
        } else {
            throw new CHttpException(403);
        }
    }

}