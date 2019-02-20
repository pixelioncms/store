<?php

Yii::import('ext.like.LikeModel');

class LikeBehavior extends CActiveRecordBehavior {

    /**
     * @var string model primary key attribute
     */
    public $pk = 'id';

    /**
     * @var string alias to class. e.g: application.shop.models.ShopProduct or pages.models.Page
     */
    public $model;
    public $nodeSave = false;
    public $modelClass;

    /**
     * @return string pk name
     */
    public function getObjectPkAttribute() {
        return $this->pk;
    }

    public function saveLike($rate) {
        if ($this->checkUserLiked()) {
            $owner = $this->getOwner();
            $like = new LikeModel;
            $like->model = $this->getClassName();
            $like->rate = $rate;
            $like->object_id = $owner->primaryKey;
            if ($this->getNodeSave()) {
                if (!$owner->saveNode(false)) {
                    throw new CHttpException(404, 'error save');
                }
            } else {
                $owner->save(false, false, false);
            }
            $like->save(false, false);

            echo CJSON::encode(array(
                'success' => true,
                'message' => 'Голос засчитан!',
                'count' => $this->getLikes(),
                'status'=>$this->getLikeStatus(),
                'object_id' => $owner->primaryKey
            ));
        } else {
            echo CJSON::encode(array(
                'success' => false,
                'message' => 'Вы уже отдали свой отзыв и не можете повторно'
            ));
        }
    }
    public function getLikeStatus(){
        if($this->getLikes() > 0){
            return 'widget-like-status-up';
        }elseif($this->getLikes() < 0){
            return 'widget-like-status-down';
        }else{
            return false;
        }
    }
    public function getLikes($num = true) {
        $like = $this->getOwner()->likes;
        if ($num) {
            if ($like > 0) {
                return '+' . $like;
            } else {
                return $like;
            }
        } else {
            return $like;
        }
    }

    public function checkUserLiked() {
        $user = Yii::app()->user;
        if (!$user->isGuest) {
            $model = LikeModel::model()->findByAttributes(array(
                'user_id' => $user->id,
                'model' => $this->getClassName(),
                'object_id' => $this->getOwner()->primaryKey));
            if (!isset($model)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getClassName() {
        return $this->model;
    }

    public function getModelClass() {
        return $this->modelClass;
    }

    public function getNodeSave() {
        return $this->nodeSave;
    }

    public function attach($owner) {
        parent::attach($owner);
    }

}
