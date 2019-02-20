<?php

/**
 * Display product view page.
 */
class ProductController extends Controller {

    public function getConfig() {
        return Yii::app()->settings->get('shop');
    }

    /**
     * @var ShopProduct
     */
    public $model;

    /**
     * Display product
     * @param string $seo_alias product url
     */
    public function actionView($seo_alias) {
        //$preg = preg_match('/^([0-9]+)-([a-zA-Z0-9-]+)/i',$seo_alias,$match);
        //print_r($match);
        //die;
        $cs = Yii::app()->clientScript;


        if (Yii::app()->hasModule('cart')) {

            $cs->registerScript('app_shop', "cart.spinnerRecount = false;", CClientScript::POS_END);
        }
        $this->_loadModel($seo_alias);


        $this->canonical = $this->model->getAbsoluteUrl();




        //  Yii::app()->clientScript->registerScript('product_view', "cart.spinnerRecount = false;", CClientScript::POS_HEAD);
        $this->registerSessionViews($this->model->id);


        $cs->registerMetaTag(Yii::app()->createAbsoluteUrl($this->model->getMainImageUrl()), null, null, array('property' => 'og:image'));
        $cs->registerMetaTag((!empty($this->model->short_description))?$this->model->short_description:Html::encode($this->model->name), null, null, array('property' => 'og:description'));
        $cs->registerMetaTag(Html::encode($this->model->name), null, null, array('property' => 'og:title'));
        $cs->registerMetaTag(Html::encode($this->model->name), null, null, array('property' => 'og:image:alt'));
        $cs->registerMetaTag('product', null, null, array('property' => 'og:type'));
        $cs->registerMetaTag(Yii::app()->createAbsoluteUrl($this->model->getUrl()), null, null, array('property' => 'og:url'));


        $cs->registerScript('numberformat', "
        var penny = " . Yii::app()->currency->active->penny . ";
        var separator_thousandth = '" . Yii::app()->currency->active->separator_thousandth . "';
        var separator_hundredth = '" . Yii::app()->currency->active->separator_hundredth . "';
        ", CClientScript::POS_HEAD);

        $cs->registerScriptFile($this->module->assetsUrl . '/number_format.js', CClientScript::POS_END);


        //Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/product.view.js', CClientScript::POS_END);
        if ($this->model->use_configurations || $this->model->processVariants())
            $cs->registerScriptFile($this->module->assetsUrl . '/product.view.configurations.js', CClientScript::POS_END);


        if ($this->model->mainCategory) {
            $ancestors = $this->model->mainCategory->excludeRoot()->ancestors()->findAll();
         //   $this->breadcrumbs = array(Yii::t('ShopModule.default', 'BC_SHOP') => '/shop');
            foreach ($ancestors as $c) {
                $this->breadcrumbs[$c->name] = $c->getUrl();
            }
            // 
            // Do not add root category to breadcrumbs
            if ($this->model->mainCategory->id != 1) {
                //$bc[$this->model->mainCategory->name]=$this->model->mainCategory->getViewUrl();

                $this->breadcrumbs[$this->model->mainCategory->name] = $this->model->mainCategory->getUrl();
            }
            $this->breadcrumbs[] = $this->model->name;
        }

        if (Yii::app()->settings->get('shop', 'auto_gen_meta')) {
            $this->pageKeywords = $this->model->keywords();
            $this->pageDescription = $this->model->description();
            $this->pageTitle = $this->model->title();
        }
        $this->render('view', array('model' => $this->model));
    }



    public function registerSessionViews($id = null)
    {
        //unset($_SESSION['views']);
        $session = Yii::app()->session->get('views');
        Yii::app()->session->setTimeout(86400 * 7);

        if (empty($session)) {
            Yii::app()->session['views'] = array();
        }

        if (isset($session)) {
            if (!in_array($id, $_SESSION['views'])) {
                array_push($_SESSION['views'], $id);
            }
        }
    }

    /**
     * Load ShopProduct model by url
     * @param $seo_alias
     * @return ShopProduct
     * @throws CHttpException
     */
    protected function _loadModel($seo_alias) {
        $this->model = ShopProduct::model()
                ->published()
                ->withUrl($seo_alias)
                ->find();

        if (!$this->model)
            throw new CHttpException(404, Yii::t('ShopModule.default', 'ERROR_PRODUCT_NOTFOUND'));

        $this->model->saveCounters(array('views' => 1));
        return $this->model;
    }

    /**
     * Get data to render dropdowns for configurable product.
     * Used on product view.
     * array(
     *      'attributes' // Array of ShopAttribute models used for configurations
     *      'prices'     // Key/value array with configurations prices array(product_id=>price)
     *      'data'       // Array to render dropdowns. array(color=>array('Green'=>'1/3/5/', 'Silver'=>'7/'))
     * )
     * @todo Optimize. Cache queries.
     * @return array
     */
    public function getConfigurableData() {
        $attributeModels = ShopAttribute::model()->findAllByPk($this->model->configurable_attributes);
        $models = ShopProduct::model()->findAllByPk($this->model->configurations);

        $data = array();
        $prices = array();
        foreach ($attributeModels as $attr) {
            foreach ($models as $m) {
                $prices[$m->id] = $m->price;
                if (!isset($data[$attr->name]))
                    $data[$attr->name] = array('---' => '0');

                $method = 'eav_' . $attr->name;
                $value = $m->$method;

                if (!isset($data[$attr->name][$value]))
                    $data[$attr->name][$value] = '';

                $data[$attr->name][$value] .= $m->id . '/';
            }
        }

        return array(
            'attributes' => $attributeModels,
            'prices' => $prices,
            'data' => $data,
        );
    }

}
