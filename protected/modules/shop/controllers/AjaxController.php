<?php

/**
 * Handle ajax requests
 */
class AjaxController extends Controller {

    /**
     * Set currency for user session.
     */
    public function actionActivateCurrency() {
        Yii::app()->currency->setActive(Yii::app()->request->getParam('id'));
    }

    /**
     * Rate product
     * @param integer $id product id
     */
    public function actionRating($id) {
        $request = Yii::app()->request;
        if ($request->isAjaxRequest) {
            $model = ShopProduct::model()->findByPk($id);
            if ($model) {
                $mod = 'product';
                $rating = (int) $_POST['rating'];
                if (in_array($rating, array(1, 2, 3, 4, 5))) {


                    $cookName = 'rating'.md5(get_class($model) . $id);
                    $model->votes +=1;
                    $model->rating += $rating;
                    $model->save(false,false,false);
                    $new = time();
                    $ratingModel = new RatingModel;
                    $ratingModel->mid = $id;
                    $ratingModel->modul = $mod;
                    $ratingModel->time = $new;
                    $ratingModel->user_id = Yii::app()->user->getId();
                    $ratingModel->host = '127.0.0.1';
                    $ratingModel->save();
                    $cookie = new CHttpCookie($cookName, $id);
                    $cookie->expire = time() + 60 * 60 * 24 * 60;
                    Yii::app()->request->cookies[$cookName] = $cookie;

                    return $this->widget('ext.rating.StarRating', array(
                                'model' => $model
                    ));
                    /*  if($model->saveCounters(array(
                      'votes' => 1,
                      'rating' => $rating
                      ))){
                      die('sss');
                      }else{
                      die($rating);
                      } */
                }
            }
        }
    }

}
