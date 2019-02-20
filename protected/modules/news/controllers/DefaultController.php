<?php

/**
 * Контроллер статичных страниц
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package modules.pages.controllers
 * @uses Controller
 */
class DefaultController extends Controller
{

    public function actionSuggestTags()
    {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $tags = Tag::model()->suggestTags($keyword);
            if ($tags !== array())
                echo implode("\n", $tags);
        }
    }

    public function actionIndex()
    {
        $this->dataModel = new News('search');

        $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');


        $this->render('index', array(
            'provider' => $this->dataModel,
        ));
    }

    public function actionView($seo_alias)
    {
        $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
        $this->dataModel = News::model()
            ->published()
            // ->language(Yii::app()->languageManager->active->id)
            ->withUrl($seo_alias)
            ->find(array('limit' => 1));


        if (!$this->dataModel)
            throw new CHttpException(404);
        $this->printer($this->dataModel->title, $this->dataModel->full_text, $this->dataModel->date_create);
        $this->dataModel->saveCounters(array('views' => 1));

        $this->breadcrumbs = array(
            $this->pageName => array('/news'),
            $this->dataModel->title
        );


        $this->render('view', array(
            'model' => $this->dataModel,
        ));
    }

}
