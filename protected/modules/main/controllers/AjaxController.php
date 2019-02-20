<?php

class AjaxController extends Controller {

    /**
     * Exp url:/ajax/filter.action
     * @return type
     */
    public function actions() {
        return array(
            'like.' => 'ext.like.LikeWidget',
            'callback.' => 'ext.callback.CallbackWidget',
            'city.' => 'ext.checkCity.CheckCityWidget',
            'uploadify.' => 'ext.uploadify.EUploadifyWidget',
            //'filter.' => array('class'=>'mod.shop.widgets.filter.FilterWidget','param'=>'asd'),
        );
    }

    public function actionHelp() {

        $md = new CMarkdown;
        $json = CJSON::encode(array(
                    'status' => 200,
                    'tabs' => array(
                        array(
                            'label' => 'Что следует помнить?',
                            'content' => $md->transform($this->helpShop())
                        ),
                        array(
                            'label' => 'Информация о модуле',
                            'content' => 'content 2'
                        ),
                    ),
                        )
        );

        $this->api($json);
    }

    protected function api($json) {
        if (Yii::app()->request->isAjaxRequest) {
            if ($this->license()) {
                $this->callback($json);
            }
        } else {
            die('Good buy.');
        }
    }

    protected function license() {
        if (false) {
            
        } else {
            $json = CJSON::encode(array(
                        'status' => 500,
                        'flashMessage' => 'Ошибка: Отсуствует лицензия' . CMS::get_referer()
                    ));
        }
        $this->callback($json);
    }

    protected function callback($json) {
        $callback = Yii::app()->request->getParam('callback');
        if ($callback) {
            $return = $callback . '(' . $json . ')';
        }
        header("Content-type: application/json; charset=UTF-8");
        echo $return;
    }

    public function actionApi() {
        //  if(Yii::app()->request->isAjaxRequest){
        $json = CJSON::encode(array(
                    'status' => 200,
                    'message' => $this->utf8('OK'),
                    'data' => array(
                        'contacts' => array(
                            'name' => $this->utf8('Алекстадр'),
                            'phone' => '(063) 489-26-95',
                            'email' => 'web@pixelion.com.ua',
                            'prof' => $this->utf8('Отдел разработки веб-сайтов')
                        ),
                        'address' => $this->utf8('г.Одесса, ул. М Арнаутская, 36'),
                        'receiverEmails' => array('andrew.panix@gmail.com') //Почта на которую приходят письма.
                    )
                        )
        );
        if (Yii::app()->request->getParam('callback')) {
            $callback = Yii::app()->request->getParam('callback');
            $json = $callback . '(' . $json . ')';
        }

        header("Content-type: application/json; charset=UTF-8");
        echo $json;
        Yii::app()->end();
        // }else{
        //     throw new CHttpException(401);
        // }
    }

    public function utf8($t) {
        //  return iconv("cp1251", "UTF-8", $t);
        return ($t);
    }

    public function showFav() {
        if (!empty($_SESSION['favorites'])) {
            $criteria_p = new CDbCriteria();
            $product_array_session = implode(',', array_unique($_SESSION['favorites']));
            $criteria_p->condition = '`t`.`id` IN (' . $product_array_session . ')';
            $criteria_p->with = array('catalog');
            $criteria_p->order = 'catid ASC';
            $criteria_p->limit = 3;
            $product = Production::model()->findAll($criteria_p);

            $product_count = Production::model()->findAll(array('condition' => '`t`.`id` IN (' . $product_array_session . ')'));
        } else {
            $product = array();
            $product_count = array();
        }

        $this->renderPartial('fav_box', array('favorites' => $product, 'counter' => $product_count));
    }

    public function actionLike() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = (int) $_POST['_id'];
            $type = (string) $_POST['type'];
            if (isset($_POST)) {
                $model = Comment::model()->findByPk($id);

                if ($type == 'up') {
                    $model->like += 1;
                } elseif ($type == 'down') {
                    $model->like -= 1;
                }
                //$model->like->user_id = (!Yii::app()->user->isGuest) ? Yii::app()->user->id : 0;
                if ($model->validate()) {

                    if ($model->save()) {
                        //$like = new CommentLike();
                        //$like->comment_id = $model->id; // Or get the data from the submitted form.
                        // $like->user_id = (!Yii::app()->user->isGuest) ? Yii::app()->user->id : 0;
                        //$like->save();
                    }
                    $json = array(
                        'num' => $model->like
                    );
                    echo CJSON::encode($json);
                }
            }
        }
    }

    public function actionRemove_favorites() {
        $id = (int) $_POST['id'];
        $url = $_POST['url'];
        $title = $_POST['title'];

        //$array = array(
        //    'id' => $id,
        //    'title' => $title,
        //     'url' => $url
        // );
        // $result = array();
        // foreach ($_SESSION['favorites'] as $fav) {
        //     if ($fav['id'] !== $id)
        //         array_push($array, $fav);
        // }
        //$_SESSION['favorites'] = $array;
        $curSess = Yii::app()->session->get("favorites");
        unset($curSess[$_POST['key']]);
        $this->widget('ext.favorites.FavoritesWidget', array('id' => $id, 'view' => true));
    }

    public function actionAdd_favorites() {
        $request = Yii::app()->request;

        if ($request->isAjaxRequest && !Yii::app()->user->isGuest) {

            $model = new Favorites;
            $model->mid = (int) $_POST['id'];
            $model->mtitle = $_POST['title'];
            $model->murl = $_POST['url'];
            $model->module = $_POST['module'];
            $model->user_id = Yii::app()->user->getId();
            $model->save();

            $this->widget('ext.favorites.FavoritesWidget', array('id' => (int) $_POST['id'], 'url' => $_POST['url'], 'title' => $_POST['title'], 'module' => $module, 'view' => false));
        }
    }

    public function actionRating() {
        $request = Yii::app()->request;
        if ($request->isAjaxRequest) {


            $mod = $_REQUEST['module'];
            $rating = (int) $_REQUEST['rating'];
            $id = (int) $_REQUEST['pid'];
            $baseModel = $_REQUEST['model'];

            $model = $baseModel::model()->findByPk($id);
            if ($model && in_array($rating, array(1, 2, 3, 4, 5))) {

                $model->score +=1;
                $model->rating += $rating;
                $model->save();
                $new = time();
                $ratingModel = new EngineRating;
                $ratingModel->mid = $id;
                $ratingModel->modul = $mod;
                $ratingModel->time = $new;
                $ratingModel->user_id = Yii::app()->user->getId();
                $ratingModel->host = CMS::getip();
                $ratingModel->save();
                $cookie = new CHttpCookie($mod . "-" . $id, $id);
                $cookie->expire = time() + 60 * 60 * 24 * 60;
                Yii::app()->request->cookies[$mod . "-" . $id] = $cookie;

                $this->widget('ext.rating.Rating', array(
                    'pid' => $id,
                    'rating' => $model->rating,
                    'votes' => $model->score,
                    'active' => false
                ));
            }
        } else {
            die('error');
        }
    }

}