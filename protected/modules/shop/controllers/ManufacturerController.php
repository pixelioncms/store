<?php

class ManufacturerController extends FilterController
{

    protected function beforeAction($action)
    {
        $this->allowedPageLimit = explode(',', Yii::app()->settings->get('shop', 'per_page'));
        return parent::beforeAction($action);
    }

    /**
     * Display all manufacturers
     *
     * @throws CHttpException
     */
    public function actionIndex()
    {
        $this->dataModel = ShopManufacturer::model()->published()->findAll();

        if (!$this->dataModel)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_BRAND'));

        $this->pageName = Yii::t('ShopModule.default', 'MANUFACTURER');
        $this->breadcrumbs = array($this->pageName);


        $this->render('index');
    }



    /**
     * Display products by manufacturer
     *
     * @throws CHttpException
     */
    public function actionView()
    {

        $url = str_replace('manufacturer/','',Yii::app()->request->getQuery('seo_alias'));

       // echo Yii::app()->request->getQuery('seo_alias'); die;
        $this->dataModel = ShopManufacturer::model()->published()->findByAttributes(array('seo_alias' => $url));

        if (!$this->dataModel)
            $this->error404(Yii::t('ShopModule.admin', 'NO_FOUND_BRAND'));

        $this->pageName = $this->dataModel->name;
        $this->breadcrumbs = array($this->pageName);


        $cs = Yii::app()->clientScript;
        $cs->registerScript('numberformat', "
        var penny = " . Yii::app()->currency->active->penny . ";
        var separator_thousandth = '" . Yii::app()->currency->active->separator_thousandth . "';
        var separator_hundredth = '" . Yii::app()->currency->active->separator_hundredth . "';
        ", CClientScript::POS_HEAD);
        //$cs->registerScript('category', "var categoryFullUrl = '" . $this->dataModel->full_path . "';", CClientScript::POS_HEAD);
        $cs->registerScriptFile($this->module->assetsUrl . "/number_format.js", CClientScript::POS_HEAD);



        $this->query = new ShopProduct(null);
        $this->query->applyManufacturers($this->dataModel->id);
        $this->query->attachBehaviors($this->query->behaviors());
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

        $this->render('view', array(
            'provider' => $provider,
        ));
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
       // $model->manufacturer_id=$this->dataModel->id;
        $criteria = $model
           // ->cache(3600 * 2)
            // ->applyCategories($this->model)
            ->applyManufacturers($this->dataModel->id)
            ->published()
            ->getDbCriteria();
       // $criteria->addCondition('manufacturer_id=' . $this->dataModel->id);
        unset($model);

        $builder = new CDbCommandBuilder(Yii::app()->db->getSchema());

        $criteria->select = 'type_id';
        $criteria->group = 'type_id';
        $criteria->distinct = true;
        $typesUsed = $builder->createFindCommand(ShopProduct::model()->tableName(), $criteria)->queryColumn();

        // Find attributes by type
        $criteria = new CDbCriteria;
        $criteria->addInCondition('types.type_id', $typesUsed);

        $query = ShopAttribute::model()
           // ->cache(3600 * 2)
            ->sorting()
            ->useInFilter()
            ->with(array('types', 'options'))
            ->findAll($criteria);

        $this->_eavAttributes = array();
        foreach ($query as $attr)
            $this->_eavAttributes[$attr->name] = $attr;

        return $this->_eavAttributes;
    }

}
