<?php

/**
 * Display category products
 * TODO: Add default sorting by rating, etc...
 *
 * @property $activeAttributes
 * @property $eavAttributes
 */
class CategoryController extends FilterController
{

    public function actionAjaxFilter()
    {
        /*echo $this->widget('mod.shop.widgets.filter.FilterWidget', array(
            'model' => $this->model,
            'attributes' => $this->eavAttributes,
            'countAttr' => true,
            'countManufacturer' => true,
        ), true);*/


        echo 's';
        Yii::app()->end();
    }

    public function actions()
    {
        return array(
            'aclist' => array(
                'class' => 'app.EAutoCompleteAction',
                'model' => 'ShopProduct', //My model's class name
                'attribute' => 'name', //The attribute of the model i will search
            ),
            /*'filter.' => array(
                'class' => 'mod.shop.widgets.filter.FilterWidget',
                'model' => $this->model,
                'attributes' => $this->eavAttributes,
            ),*/
            /* 'filter.' => array(
                 'class' => 'mod.shop.widgets.filter.FilterWidget',
                 'model' => $this->model,
                 'eavAttributes' => $this->eavAttributes,
             ),*/

            'filter' => array(
                'class' => 'mod.shop.widgets.filter.actions.FilterAction',
                //'model' => $this->model = $this->_loadModel(Yii::app()->request->getQuery('seo_alias')),
                'model' => $this->dataModel = $this->_loadModel(Yii::app()->request->getQuery('seo_alias')),
                'eavAttributes' => $this->eavAttributes,
            ),

        );
    }

    public function getConfig()
    {
        return Yii::app()->settings->get('shop');
    }


    /**
     * Load category model by url
     *
     * @param $action
     * @return bool
     */
    public function beforeAction($action)
    {

        $this->allowedPageLimit = explode(',', Yii::app()->settings->get('shop', 'per_page'));

        if (Yii::app()->request->getPost('min_price') || Yii::app()->request->getPost('max_price')) {
            $data = array();

            if (Yii::app()->request->getPost('min_price'))
                $data['min_price'] = (int)Yii::app()->request->getPost('min_price');
            if (Yii::app()->request->getPost('max_price'))
                $data['max_price'] = (int)Yii::app()->request->getPost('max_price');

            if ($this->action->id === 'search') {
                $this->redirect(Yii::app()->request->addUrlParam('/shop/category/search', $data));
            } else {
                if (!Yii::app()->request->isAjaxRequest) {
                    if (Yii::app()->request->getPost('filter')) {
                        foreach (Yii::app()->request->getPost('filter') as $key => $filter) {
                            $data[$key] = $filter;
                        }
                    }
                    $this->redirect(Yii::app()->request->addUrlParam('/shop/category/view', $data));
                }

            }
        }

        return parent::beforeAction($action);
    }


    /**
     * Search products
     */
    public function actionSearch()
    {
        if (Yii::app()->request->isPostRequest)
            $this->redirect(Yii::app()->request->addUrlParam('/shop/category/search', array('q' => Yii::app()->request->getPost('q'))));
        $q = Yii::app()->request->getQuery('q');
        if (empty($q)) {
            $q = '+';
        }


        if (Yii::app()->request->isAjaxRequest) {
            $res = array();
            $criteria = new CDbCriteria();
            $criteria->compare('name', Yii::app()->request->getQuery('term'), true);
            $model = new ShopProduct;
            /*foreach ($model->findAll($criteria) as $m) {

                $res[] = array(
                    'label' => $m->name,
                    'value' => $m->name,
                    'price' => $m->getFrontPrice(),
                    'symbol' => Yii::app()->currency->active->symbol,
                    'url' => $m->getUrl(),
                    'image' => $m->getMainImageUrl('50x50'),
                );
            }*/
            $this->renderPartial('_ajax_search', array('model' => $model->findAll($criteria)));
            //echo CJSON::encode($res);
            Yii::app()->end();
        }


        if (!$q) {
            $this->render('search');
        } else {
            $this->doSearch($q, 'search');
        }
    }

    /**
     * Search products
     * @param $data ShopCategory|string
     * @param string $view
     */
    public function doSearch($data, $view)
    {
        $this->query = new ShopProduct(null);


        $this->query->attachBehaviors($this->query->behaviors());
        $this->query->applyAttributes($this->activeAttributes)->published();


        if ($data instanceof ShopCategory) {
            //  $cr->with = array('manufacturerActive');
            // Скрывать товары если производитель скрыт.
            //TODO: если у товара не выбран производитель то он тоже скрывается!! need fix
            //$this->query->with(array('manufacturer' => array(
            //        'scopes' => array('published')
            //)));
            if (!Yii::app()->request->isAjaxRequest)
                Yii::app()->clientScript->registerMetaTag("noindex, nofollow", 'robots');

            $this->query->applyCategories($this->dataModel);
            //  $this->query->with('manufacturerActive');
        } else {
            $cr = new CDbCriteria;
            $cr->with = array(
                // 'manufacturerActive',
                'translate' => array('together' => true),
            );

            $cr->addSearchCondition('t.sku', $data);
            $cr->addSearchCondition('translate.name', $data, true, 'OR');

            $this->query->getDbCriteria()->mergeWith($cr);
        }


        // Filter by manufacturer
        if (Yii::app()->request->getQuery('manufacturer')) {
            $manufacturers = explode(',', Yii::app()->request->getParam('manufacturer', ''));
            $this->query->applyManufacturers($manufacturers);
        }


        // Create clone of the current query to use later to get min and max prices.
        $this->currentQuery = clone $this->query->getDbCriteria();

        // Filter products by price range if we have min_price or max_price in request
        $this->applyPricesFilter();


        //        $this->maxprice = $this->getMaxPrice();
        //$this->minprice = $this->getMinPrice();

        $per_page = $this->allowedPageLimit[0];
        if (isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $this->allowedPageLimit))
            $per_page = (int)$_GET['per_page'];

        $this->provider = new ActiveDataProvider($this->query, array(
            // Set id to false to not display model name in
            // sort and page params
            'id' => false,
            'pagination' => array(
                'pageSize' => $per_page,
            )
        ));

        $this->provider->sort = ShopProduct::getCSort();
        if ($view != 'search') {
            $cs = Yii::app()->clientScript;
            $cs->registerScript('numberformat', "
        //var xhrCurrentFilter;
        var penny = " . Yii::app()->currency->active->penny . ";
        var separator_thousandth = '" . Yii::app()->currency->active->separator_thousandth . "';
        var separator_hundredth = '" . Yii::app()->currency->active->separator_hundredth . "';
        ", CClientScript::POS_HEAD);
            $cs->registerScript('category', "var categoryFullUrl = '" . $this->dataModel->full_path . "';", CClientScript::POS_HEAD);
            $cs->registerScriptFile($this->module->assetsUrl . "/number_format.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($this->module->assetsUrl . "/filter.js", CClientScript::POS_END);

            if (Yii::app()->settings->get('shop', 'auto_gen_cat_meta')) {
                $this->pageKeywords = $this->dataModel->keywords();
                $this->pageDescription = $this->dataModel->description();
                $this->pageTitle = $this->dataModel->title();
            }

            $ancestors = $this->dataModel->excludeRoot()->ancestors()->findAll();
            // $this->breadcrumbs = array(Yii::t('ShopModule.default', 'BC_SHOP') => array('/shop'));
            foreach ($ancestors as $c)
                $this->breadcrumbs[$c->name] = $c->getUrl();

            $name = $this->dataModel->name;
            $this->pageName = $this->dataModel->name;
            if (!Yii::app()->request->isAjaxRequest) {

                $filterData = $this->getActiveFilters();


                unset($filterData['price']);
                if ($filterData) {
                    $name = '';
                    foreach ($filterData as $filterKey => $filterItems) {
                        if ($filterKey == 'manufacturer') {
                            $manufacturerNames = array();
                            foreach ($filterItems['items'] as $mKey => $mItems) {
                                $manufacturerNames[] = $mItems['label'];
                            }
                            $sep = (count($manufacturerNames) > 2) ? ', ' : ' и ';
                            $name .= ' ' . implode($sep, $manufacturerNames);
                            $this->pageName .= ' ' . implode($sep, $manufacturerNames);
                        } else {
                            $attributesNames[$filterKey] = array();
                            foreach ($filterItems['items'] as $mKey => $mItems) {
                                $attributesNames[$filterKey][] = $mItems['label'];
                            }
                            if (isset($filterData['manufacturer'])) {
                                $s = '; ';
                            }else{
                                $s = ' ';
                            }
                            $sep = (count($attributesNames[$filterKey]) > 2) ? ', ' : ' и ';
                            $name .= $s . $filterItems['label'] . ' ' . implode($sep, $attributesNames[$filterKey]);
                            $this->pageName .= $s . $filterItems['label'] . ' ' . implode($sep, $attributesNames[$filterKey]);
                        }
                    }
                    $this->breadcrumbs[$this->dataModel->name] = $this->dataModel->getUrl();
                }

                $this->breadcrumbs[] = $name;


            }
        }
        if (Yii::app()->request->isAjaxRequest) {
            $cs->scriptMap = array(
                'number_format.js' => false,
                'number_format.min.js' => false,
                'pixelion-icons.css' => false,
                'pixelion-icons.min.css' => false,
                'filter.js' => false,
            );
            if (isset($_GET['ajax']) && $_GET['ajax'] === 'shop-products') {
                $this->render('_ajax', array(
                    'provider' => $this->provider,
                    'itemView' => $this->itemView
                ));
            } else {
                $this->renderPartial('mod.shop.widgets.filter3.views._current', array(), false, true);
            }
        } else {
            $this->render($view, array(
                'provider' => $this->provider,
            ));
        }

    }


    public function getActiveFilters()
    {
        $request = Yii::app()->request;
        // Render links to cancel applied filters like prices, manufacturers, attributes.
        $menuItems = array();
        if ($this->route == 'shop/category/view') {
            $manufacturers = array_filter(explode(',', $request->getQuery('manufacturer')));
            $manufacturers = ShopManufacturer::model()
                //->cache($this->controller->cacheTime)
                ->findAllByPk($manufacturers);
        }
        if ($request->getQuery('min_price') || $request->getQuery('min_price')) {
            $menuItems['price'] = array(
                'label' => Yii::t('ShopModule.default', 'FILTER_PRICE_HEADER') . ':',
                'itemOptions'=>array('id'=>'current-filter-prices')
            );
        }


        if ($request->getQuery('min_price')) {
            $menuItems['price']['items'][] = array(
                'label' => Yii::t('ShopModule.default', 'от {minPrice} {c}', array('{minPrice}' => Yii::app()->currency->number_format($this->getCurrentMinPrice()), '{c}' => Yii::app()->currency->active->symbol)),
                'linkOptions' => array('class' => 'remove', 'data-price' => 'min_price'),
                'url' => $request->removeUrlParam('/shop/category/view', 'min_price')
            );
        }

        if ($request->getQuery('max_price')) {
            $menuItems['price']['items'][] = array(
                'label' => Yii::t('ShopModule.default', 'до {maxPrice} {c}', array('{maxPrice}' => Yii::app()->currency->number_format($this->getCurrentMaxPrice()), '{c}' => Yii::app()->currency->active->symbol)),
                'linkOptions' => array('class' => 'remove', 'data-price' => 'max_price'),
                'url' => $request->removeUrlParam('/shop/category/view', 'max_price')
            );
        }
        if ($this->route == 'shop/category/view') {
            if (!empty($manufacturers)) {
                $menuItems['manufacturer'] = array(
                    'label' => Yii::t('ShopModule.default', 'FILTER_MANUFACTURER') . ':',
                );

                foreach ($manufacturers as $manufacturer) {
                    $menuItems['manufacturer']['items'][] = array(
                        'label' => $manufacturer->name,
                        'linkOptions' => array('class' => 'remove', 'data-target' => '#filter_manufacturer_' . $manufacturer->id),
                        'url' => $request->removeUrlParam('/shop/category/view', 'manufacturer', $manufacturer->id)
                    );
                }
            }
        }
        // Process eav attributes
        $activeAttributes = $this->activeAttributes;
        if (!empty($activeAttributes)) {
            foreach ($activeAttributes as $attributeName => $value) {
                if (isset($this->eavAttributes[$attributeName])) {
                    $attribute = $this->eavAttributes[$attributeName];
                    $menuItems[$attributeName] = array(
                        'label' => $attribute->title . ':',
                    );
                    foreach ($attribute->options as $option) {
                        if (isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name])) {
                            $menuItems[$attributeName]['items'][] = array(
                                'label' => $option->value . ' ' . $attribute->abbreviation,
                                'linkOptions' => array('class' => 'remove', 'data-target' => "#filter_{$attribute->name}_{$option->id}"),
                                'url' => $request->removeUrlParam('/shop/category/view', $attribute->name, $option->id)
                            );
                            sort($menuItems[$attributeName]['items']);
                        }
                    }
                }
            }
        }
        return $menuItems;
    }


    /**
     * @return array of available attributes in category
     */
    public function getEavAttributes()
    {
        if (is_array($this->_eavAttributes))
            return $this->_eavAttributes;

        // Find category types
        $model = new ShopProduct(null);
        $criteria = $model
            //->cache($this->cacheTime)
            ->applyCategories($this->dataModel)
            ->published()
            ->getDbCriteria();

        unset($model);

        $builder = new CDbCommandBuilder(Yii::app()->db->getSchema());

        $criteria->select = 'type_id';
        $criteria->group = 'type_id';
        $criteria->distinct = true;
        $typesUsed = $builder->createFindCommand(ShopProduct::model()->tableName(), $criteria)->queryColumn();

        // Find attributes by type
        $criteria = new CDbCriteria;
        $criteria->addInCondition('types.type_id', $typesUsed);
        //$criteria->order = 't.ordern DESC';
        $query = ShopAttribute::model()
            // ->cache($this->cacheTime)
            ->useInFilter()
            ->with(array('types', 'options'))
            ->findAll($criteria);

        $this->_eavAttributes = array();
        foreach ($query as $attr) {
            $this->_eavAttributes[$attr->name] = $attr;
        }
        return $this->_eavAttributes;
    }


    /**
     * Load category by url
     * @param $url
     * @return mixed
     * @throws CHttpException
     */
    public function _loadModel($url)
    {
        // Find category
        $model = ShopCategory::model()
            ->excludeRoot()
            //->language(Yii::app()->languageManager->active)
            ->withFullPath($url)
            ->find();

        if (!$model)
            $this->error404(Yii::t('ShopModule.default', 'NOFIND_CATEGORY'));

        return $model;
    }

    public function actionDiscount2()
    {
        $this->query = new ShopProduct(null);
        //  $this->query->applyManufacturers($this->dataModel->id);
        $this->query->attachBehaviors($this->query->behaviors());
        $this->query->appliedDiscount = 1;
        $this->query->applyAttributes($this->activeAttributes)->published();
        // Create clone of the current query to use later to get min and max prices.
        $this->currentQuery = clone $this->query->getDbCriteria();

        // Filter products by price range if we have min_price or max_price in request
        $this->applyPricesFilter();

        $provider = new ActiveDataProvider($this->query, array(
            'id' => false,
            'pagination' => array(
                'pageSize' => $this->allowedPageLimit[0],
            )
        ));


        $itemView = '_view_grid';
        if (isset($_GET['view'])) {
            if (in_array($_GET['view'], array('list', 'table', 'grid'))) {
                $itemView = '_view_' . $_GET['view'];
            } else {
                $itemView = '_view_grid';
            }
        }
        $this->render('discount', array(
            'provider' => $this->provider,
            'itemView' => $itemView
        ));
    }

    /**
     * Display category products
     */
    public function actionView()
    {
        $this->dataModel = $this->_loadModel(Yii::app()->request->getQuery('seo_alias'));
        $this->canonical = Yii::app()->createAbsoluteUrl($this->dataModel->getUrl());
        $this->doSearch($this->dataModel, 'view');
    }

    public function actionDiscount()
    {


        $per_page = $this->allowedPageLimit[0];
        if (isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $this->allowedPageLimit))
            $per_page = (int)$_GET['per_page'];


        $this->provider = new CActiveDataProvider('ShopProduct', array(
            'criteria' => array(
                'condition' => 'is_discount=1 && switch=1',
                'order' => 'ordern DESC',
            ),
            'countCriteria' => array(
                'condition' => 'is_discount=1 && switch=1',
            ),
            'pagination' => array(
                'pageSize' => $per_page,
            ),
        ));


        $itemView = '_view_grid';
        if (isset($_GET['view'])) {
            if (in_array($_GET['view'], array('list', 'table', 'grid'))) {
                $itemView = '_view_' . $_GET['view'];
            } else {
                $itemView = '_view_grid';
            }
        }
        $this->render('discount', array(
            'provider' => $this->provider,
            'itemView' => $itemView
        ));

    }
}
