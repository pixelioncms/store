<?php

class DefaultController extends Controller {

    public $defaultAction = 'admin';

    public function filters() {
        return array(
                //   'accessControl', // perform access control for CRUD operations
                //  'ajaxOnly + PostComment, Delete, Approve, Edit',
        );
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('postComment', 'captcha', 'authProvider', 'auth'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('admin', 'delete', 'approve', 'edit'),
                'users' => array('admin'),
            ),
            array('allow',
                'actions' => array('edit', 'delete', 'create', 'reply', 'reply_submit', 'rate'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionRate($type, $object_id) {
        // $model = Yii::import("mod.comments.models.NestedComments");
        $like = new Like;
        $like->model = 'mod.comments.models.Comments';
        $like->rate = $type;
        $like->object_id = $object_id;
        if ($like->validate()) {
            $modelClass = Yii::import("mod.comments.models.Comments");
            $model = $modelClass::model()->findByPk($object_id);
            if ($type == 'up') {
                $model->like+=1;
            } elseif ($type == 'down') {
                $model->like-=1;
            }
            $model->saveNode();

            $like->save(false, false);
            $json = array(
                'num' => $model->getLikes()
            );
        } else {
            $json = array('error' => 'error validate');
        }
        echo CJSON::encode($json);
    }

    public function actionReply_submit() {
        //Yii::app()->request->enableCsrfValidation=false;

        $reply = Comments::model()->findByPk(Yii::app()->request->getPost('reply_id'));

        $comment = new Comments;
        if (isset($_POST['Comments'])) {
            $comment->attributes = $_POST['Comments'];
            $comment->model = $reply->model;
            $comment->object_id = $reply->object_id;
            if ($comment->validate()) {
                $comment->appendTo($reply);
                if($comment->isNewRecord){
                    $message = 'Комментарий успешно Добавлен';
                }else{
                    $message = 'Комментарий успешно отредактирован';
                }
                $data = array(
                    'code' => 'success',
                    'flash_message' => $message
                );
            } else {
                $data = array(
                    'code' => 'fail',
                    'response' => $comment->getErrors()
                );
            }
        }
        echo CJSON::encode($data);
    }

    public function actionReply($id) {
        $reply = Comments::model()->findByPk($id);
        // $sleep = Yii::app()->session['caf'];

        $comment = new Comments();
        if (isset($_POST['Comments'])) {
            $comment->attributes = $_POST['Comments'];
            $comment->model = $reply->model;
            $comment->object_id = $reply->object_id;
            $comment->user_id = (!Yii::app()->user->isGuest) ? Yii::app()->user->id : 0;
            if ($comment->validate()) {

                $comment->appendTo($reply);
                Yii::app()->session['caf'] = time();
                $data = array(
                    'code' => 'success',
                    'flash_message' => Yii::t('CommentsModule.default', 'COMM_SUCCESS_REPLY'),
                );
            } else {
                $data = array(
                    'code' => 'fail',
                    'response' => $comment->getErrors()
                );
            }
            echo CJSON::encode($data);
        } else {
            //$reply->unsetAttributes();
            Yii::app()->clientScript->scriptMap = array('jquery.js' => false);
            $this->render('_reply_form', array('model' => $reply), false, true);
        }
    }

    public function actionEdit() {
        $model = Comments::model()->findByPk((int) $_POST['_id']);
        // Yii::app()->request->enableCsrfValidation=false;
        if ($model->controlTimeout()) {
            if (Yii::app()->request->isAjaxRequest) {
                if ($model->user_id == Yii::app()->user->id || Yii::app()->user->getIsSuperuser()) {
                    if (isset($_POST['Comments'])) {
                        $model->attributes = $_POST['Comments'];
                        if ($model->validate()) {
                            $model->saveNode();
                            $data = array(
                                'code' => 'success',
                                'flash_message' => 'Комментарий успешно отредактирован',
                                'response' => nl2br(Html::text($model->text))
                            );
                        } else {
                            $data = array(
                                'code' => 'fail',
                                'response' => $model->getErrors()
                            );
                        }
                        echo CJSON::encode($data);
                    } else {
                        $this->render('_edit_form', array(
                            'model' => $model,
                            'currentUrl'=>''
                        ));
                    }
                } else {
                    die('Access denie ' . $model->user_id . ' - ' . Yii::app()->user->id);
                }
            } else {
                die('Access denie 2');
            }
        } else {
            $data = array(
                'code' => 'fail',
                'response' => 'Время редактирование завершено!'
            );
            echo CJSON::encode($data);
        }
    }

    /**
     * Deletes a particular model.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);
        if ($model->controlTimeout()) {
            $result = array('deletedID' => $id);
            if ($model->deleteNode()) {

                foreach (array_reverse($model->descendants()->findAll()) as $subCategory)
                    $subCategory->deleteNode();
                $result['code'] = 'success';
                $result['flash_message'] = 'Комментарий удален.';
            } else {
                $result['code'] = 'fail';
                $result['flash_message'] = 'Ошибка удаление.';
            }
        } else {
            $result['code'] = 'fail';
            $result['flash_message'] = 'Таймаут';
        }
        echo CJSON::encode($result);
    }

    /**
     * Deletes a particular model.
     * @param integer $id the ID of the model to be deleted

      public function actionDelete($id) {
      $model = $this->loadModel($id);
      $result = array('deletedID' => $id);
      if ($model->delete()) {
      $result['code'] = 'success';
      $result['flash_message'] = 'Комментарий удален.';
      } else {
      $result['code'] = 'fail';
      $result['flash_message'] = 'Ошибка удаление.';
      }
      echo CJSON::encode($result);
      } */

    /**
     * Approves a particular model.
     * @param integer $id the ID of the model to be approve
     */
    public function actionApprove($id) {
        // we only allow deletion via POST request
        $result = array('approvedID' => $id);
        if ($this->loadModel($id)->setApproved())
            $result['code'] = 'success';
        else
            $result['code'] = 'fail';
        echo CJSON::encode($result);
    }

    /*
      public function actionPostComment() {
      if (isset($_POST['Comment']) && Yii::app()->request->isAjaxRequest) {
      $comment = new Comment();
      $comment->attributes = $_POST['Comment'];
      $result = array();
      if ($comment->save()) {
      $result['code'] = 'success';
      $this->beginClip("list");
      $this->widget('comments.widgets.ECommentsListWidget', array(
      'model' => $comment->ownerModel,
      'showPopupForm' => false,
      ));
      $this->endClip();
      $this->beginClip('form');
      $this->widget('comments.widgets.ECommentsFormWidget', array(
      'model' => $comment->ownerModel,
      ));
      $this->endClip();
      $result['list'] = $this->clips['list'];
      } else {
      $result['code'] = 'fail';
      $this->beginClip('form');
      $this->widget('comments.widgets.ECommentsFormWidget', array(
      'model' => $comment->ownerModel,
      'validatedComment' => $comment,
      ));
      $this->endClip();
      }
      $result['form'] = $this->clips['form'];
      echo CJSON::encode($result);
      }
      } */

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Comments::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404);
        return $model;
    }

    public function actionCreate() {
        $owner_title = $_POST['owner_title'];
        $modelName = $_POST['model'];
        $objID = $_POST['object_id'];
        $comment = new Comments;
        $request = Yii::app()->request;
        if ($request->isPostRequest && $request->isAjaxRequest) {

            $comment->attributes = $request->getPost('Comments');
            $comment->model = $modelName;
            $comment->owner_title = $owner_title;
            $comment->object_id = $objID;
            if ($comment->validate()) {
                if(Yii::app()->user->isSuperuser){
                    $comment->switch=1;
                }
                $comment->saveNode();

                if(Yii::app()->user->isSuperuser){
                    echo CJSON::encode(array(
                        'status'=>'success',
                        'success' => true,
                        'message'=>'Комментарий успешно опубликован.'
                    ));
                }else{
                    echo CJSON::encode(array(
                        'status'=>'wait',
                        'success' => true,
                        'message'=>'Комментарий будет опубликован после проверки'
                    ));
                }


                Yii::app()->end();
                Yii::app()->session['caf'] = time();
            }
        }
        return $comment;
    }

}
