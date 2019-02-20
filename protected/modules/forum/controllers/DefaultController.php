<?php

/**
 * Контроллер статичных страниц
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.pages.controllers
 * @uses Controller
 */
class DefaultController extends Controller {

    public function actionIndex() {
        $this->dataModel = ForumCategories::model()->roots()->findAll();
        $this->pageName = Yii::t('ForumModule.default', 'MODULE_NAME');
        $this->render('index', array(
            'categories' => $this->dataModel,
        ));
    }

    public function actionView($id) {
        $this->pageName = Yii::t('ForumModule.default', 'MODULE_NAME');
        $this->dataModel = ForumCategories::model()
                ->published()
                //->with('parents')
                ->findByPk($id);


        if (!$this->dataModel)
            throw new CHttpException(404);



        $ancestors = $this->dataModel->excludeRoot()->ancestors()->findAll();
        $this->breadcrumbs = array($this->pageName => array('/forum'));
        foreach ($ancestors as $c)
            $this->breadcrumbs[$c->name] = $c->getUrl();

        $this->breadcrumbs[] = $this->dataModel->name;
        $this->render('view', array(
            'model' => $this->dataModel,
        ));
    }

    public function actionAddCat($parent_id) {
        //  if ($new === true)
        //     $model = new ForumCategories;
        // else {
        //     $model = ForumCategories::model()
        //            ->findByPk($_GET['parent_id']);
        //  }
        //if (!$model)
        //    throw new CHttpException(404, Yii::t('ForumModule.admin', 'NO_FOUND_CATEGORY'));
        // $oldImage = $model->image;

        $model = new ForumCategories;

        if (!$model)
            throw new CHttpException(404, Yii::t('ForumModule.admin', 'NO_FOUND_CATEGORY'));

        $parent = ForumCategories::model()->findByPk($parent_id);


        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ForumCategories'];

            if ($model->validate()) {

                if ($model->getIsNewRecord()) {
                    $model->appendTo($parent);
                } else {
                    $model->saveNode();
                }
            }
        }
        $this->render('addcat', array('model' => $model));
    }

    public function actionQuote() {
        if (Yii::app()->request->isAjaxRequest) {
            $result = array();

            $post = ForumPosts::model()->findByPk($_GET['post_id']);

            $result['quote_html'] = $this->renderPartial('partials/_ajax_quote_html', array('post' => $post), true, false);
            echo CJSON::encode($result);
            Yii::app()->end();
        } else {
            throw new CHttpException(500);
        }
    }


}
