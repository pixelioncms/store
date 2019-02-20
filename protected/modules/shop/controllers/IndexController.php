<?php

class IndexController extends Controller {

    public function actionIndex() {

        $this->pageName = 'Продукция';
        $this->breadcrumbs = array($this->pageName);


        $this->render('index', array());
    }

    /**
     * Renders products list to display on the start page
     */
    public function actionRenderProductsBlock() {
        $scope = Yii::app()->request->getQuery('scope');
        switch ($scope) {
            case 'newest':
                $this->renderBlock($this->getNewest(4));
                break;

            case 'added_to_cart':
                $this->renderBlock($this->getByAddedToCart(4));
                break;
        }
    }

    /**
     * @param $products
     */
    protected function renderBlock($products) {
        foreach ($products as $p)
            $this->renderPartial('_product', array('data' => $p));
    }

    /**
     * @param $limit
     * @return array
     */
    protected function getPopular($limit) {
        return ShopProduct::model()
                        ->cache($this->cacheTime)
                        ->published()
                        ->byViews()
                        ->findAll(array('limit' => $limit));
    }

    /**
     * @param $limit
     * @return array
     */
    protected function getByAddedToCart($limit) {
        return ShopProduct::model()
                        ->cache($this->cacheTime)
                        ->published()
                        ->byAddedToCart()
                        ->findAll(array('limit' => $limit));
    }

    /**
     * @param $limit
     * @return array
     */
    protected function getNewest($limit) {
        return ShopProduct::model()
                        ->cache($this->cacheTime)
                        ->published()
                        ->newest()
                        ->findAll(array('limit' => $limit));
    }

}
