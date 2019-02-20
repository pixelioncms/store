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

    public function actionIndex($url)
    {

        // Html pages
        $theme = Yii::app()->theme->getName();
        $layouts = array(
            "webroot.themes.{$theme}.views.pages.default.html",
            "mod.pages.views.default.html",
        );

        foreach ($layouts as $layout) {
            if (file_exists(Yii::getPathOfAlias($layout) . DS . $url . '.php')) {
                $this->render($layout . '.' . $url, array());
                Yii::app()->end();
            }
        }

        //Database pages
        $this->dataModel = Page::model()
            ->published()
            ->withUrl($url)
            ->find(array(
                'limit' => 1
            ));
        if (!$this->dataModel)
            throw new CHttpException(404);

        $this->dataModel->saveCounters(array('views' => 1));

		$this->pageName = $this->dataModel->title;
        $this->breadcrumbs = array($this->pageName);

        $this->render('view', array(
            'model' => $this->dataModel,
        ));


    }
}