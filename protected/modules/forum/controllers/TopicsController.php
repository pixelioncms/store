<?php

/**
 * Контроллер статичных страниц
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.pages.controllers
 * @uses Controller
 */
class TopicsController extends Controller {

    public function actionAddTopic() {
        $this->pageName = Yii::t('ForumModule.default', 'MODULE_NAME');

        $model = new ForumTopics;
        $model->category_id = $_GET['id'];


        $category = ForumCategories::model()->findByPk($_GET['id']);


        $ancestors = $category->excludeRoot()->ancestors()->findAll();
        $this->breadcrumbs = array($this->pageName => array('/forum'));
        foreach ($ancestors as $c)
            $this->breadcrumbs[$c->name] = $c->getUrl();

        $this->breadcrumbs[] = $category->name;

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ForumTopics'];
            if ($model->validate()) {
                if ($model->save()) {


                    $post = new ForumPosts;
                    $post->topic_id = $model->id;
                    $post->text = $model->text;
                    $post->save(false, false, false);

                    $model->category->count_topics++;
                    $model->category->last_post_user_id = $post->user_id;
                    $model->category->last_post_id = $post->id;
                    $model->category->last_topic_id = $model->id;

                    $model->category->saveNode(false, false, false);
                    $ancestors = $model->category->ancestors()->findAll();
                    if ($ancestors) {
                        foreach ($ancestors as $category) {
                            $category->count_topics++;
                            $category->last_post_user_id = $post->user_id;
                            $category->last_post_id = $post->id;
                            $category->last_topic_id = $model->id;
                            $category->saveNode(false, false, false);
                        }
                    }
                }
                $this->redirect(array('/forum/default/view', 'id' => $_GET['id']));
            }
        }
        $this->render('addtopic', array('model' => $model, 'category' => $category));
    }

    public function actionView($id) {
        $this->pageName = Yii::t('ForumModule.default', 'MODULE_NAME');
        $this->dataModel = ForumTopics::model()
                //->published()
                //->with('parents')
                ->findByPk($id);


        if (!$this->dataModel)
            throw new CHttpException(404);


        $this->breadcrumbs = array(
            $this->pageName => array('/forum'),
            $this->dataModel->category->name => $this->dataModel->category->getUrl(),
            $this->dataModel->title
        );


        $providerPosts = new CArrayDataProvider($this->dataModel->posts, array(
            'pagination' => array(
                'pageSize' => Yii::app()->settings->get('forum', 'pagenum'),
            ),
                )
        );

        $this->render('view', array(
            'model' => $this->dataModel,
            'providerPosts' => $providerPosts
        ));
    }

    public function actionAddreply() {
        if (!Yii::app()->user->isGuest) {
            $postModel = new ForumPosts;
            $view = (Yii::app()->request->isAjaxRequest) ? '_form_addreply' : '_form_addreply';
            $request = Yii::app()->request;

            if ($request->isPostRequest && $request->isAjaxRequest) { // && $request->isAjaxRequest
                $postModel->attributes = $request->getPost('ForumPosts');

                if ($postModel->validate()) {
                    if ($postModel->save()) {

                        $postModel->topic->date_update = date('Y-m-d H:i:s', CMS::time());
                        $postModel->topic->save(false, false, false);

                        $postModel->topic->category->last_post_user_id = $postModel->user_id;
                        $postModel->topic->category->last_post_id = $postModel->id;
                        $postModel->topic->category->last_topic_id = $postModel->topic_id;

                        $postModel->topic->category->count_posts++;


                        $postModel->topic->category->saveNode(false, false, false);
                        $ancestors = $postModel->topic->category->ancestors()->findAll();
                        if ($ancestors) {
                            foreach ($ancestors as $category) {
                                $category->last_post_user_id = $postModel->user_id;
                                $category->last_post_id = $postModel->id;
                                $category->last_topic_id = $postModel->topic_id;
                                $category->count_posts++;
                                $category->saveNode(false, false, false);
                            }
                        }
                    }
                    Yii::app()->user->setFlash('success', 'Success!!!');
                } else {
                    print_r($postModel->getErrors());
                    die;
                }
                $this->render($view, array(
                    'model' => $postModel
                ));
            }
        } else {
            throw new Exception('NOPauth');
        }
    }

    public function actionEditpost($id) {
        if (Yii::app()->request->isAjaxRequest) {

Yii::import('ext.notify.Notify');
Notify::register();
            $cs = Yii::app()->getClientScript();
            $cs->scriptMap = array(
                //  'jquery.yiigridview.js'=>false,
                // 'jquery.js' => false,
                //'jquery.min.js' => false,
              //  'common.js' => false,
            );

            $result = array();

            $post = ForumPosts::model()->findByPk($id);
            $request = Yii::app()->request;

            if ($request->isPostRequest && $request->isAjaxRequest) { // && $request->isAjaxRequest
                $post->attributes = $request->getPost('ForumPosts');
                if (!empty($post->edit_reason)) {
                    $post->edit_user_id = Yii::app()->user->getId();
                }
                if ($post->validate()) {
                    if ($post->save()) {
                        $result['post'] = $this->renderPartial('_posts_content', array(
                            'data' => $post
                                ), true, false);
                        $result['message'] = Yii::t('ForumModule.default','POST_EDITED');
                        $result['id'] = $id;
                        echo CJSON::encode($result);
                        Yii::app()->end();
                    }
                }
            }
            $this->render('_form_editpost', array(
                'model' => $post
                    ), false, true);
        } else {
            throw new CHttpException(500);
        }
    }

}
