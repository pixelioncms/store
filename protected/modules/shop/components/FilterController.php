<?php

class FilterController extends Controller
{

    /**
     * @var ShopProduct
     */
    public $query;

    /**
     * @var ShopCategory
     */
    public $model;

    /**
     * @var array Eav attributes used in http query
     */
    public $_eavAttributes;

    /**
     * @var array
     */
    public $allowedPageLimit = array();

    /**
     * Current query clone to use in min/max price queries
     * @var CDbCriteria
     */
    public $currentQuery;

    /**
     * @var ActiveDataProvider
     */
    public $provider;

    /**
     * @var string
     */
    public $_maxPrice;
    public $_minPrice;

    public $maxprice, $minprice;

    public $itemView = '_view_grid';

    public function init()
    {
        if (isset($_GET['view'])) {
            if (in_array($_GET['view'], array('list', 'table', 'grid'))) {
                $this->itemView = '_view_' . $_GET['view'];
            } else {
                $this->itemView = '_view_grid';
            }
        }
        parent::init();
    }

    /**
     * @param string $function
     * @return mixed
     */
    public function aggregatePrice($function = 'MIN')
    {
        $query = clone $this->currentQuery;


        $query->select = array(
            ''.$function.'((CASE WHEN (`t`.`currency_id`)
                    THEN
                        `t`.`price` * (SELECT rate FROM `cms_shop_currency` `currency` WHERE `currency`.`id`=`t`.`currency_id`)
                    ELSE
                        `t`.`price`
                END)) AS aggregation_price',
        );
        //$query->select = $function . '(`t`.`price`) as aggregation_price';


        $query->limit = 1;
        //$query->order = ($function === 'MIN') ? '`t`.`price`' : '`t`.`price` DESC';
        $query->order = ($function === 'MIN') ? 'aggregation_price' : 'aggregation_price DESC';
        $query->distinct=false; //@todo panix 24.02.2019 added
        $result = ShopProduct::model();
        $result->getDbCriteria()->mergeWith($query);

        $r = $result->find();
        if ($r) {
            return $r->aggregation_price;
        }
        return null;
    }

    /**
     * @var string min price in the query
     */
    private $_currentMinPrice = null;

    /**
     * @var string max price in the query
     */
    private $_currentMaxPrice = null;

    /**
     * @return mixed
     */
    public function getCurrentMinPrice()
    {
        if ($this->_currentMinPrice !== null)
            return $this->_currentMinPrice;

        if (Yii::app()->request->getQuery('min_price'))
            $this->_currentMinPrice = Yii::app()->request->getQuery('min_price');
        else
            $this->_currentMinPrice = Yii::app()->currency->convert($this->getMinPrice());

        return $this->_currentMinPrice;
    }

    /**
     * @return mixed
     */
    public function getCurrentMaxPrice()
    {
        if ($this->_currentMaxPrice !== null)
            return $this->_currentMaxPrice;

        if (Yii::app()->request->getQuery('max_price'))
            $this->_currentMaxPrice = Yii::app()->request->getQuery('max_price');
        else
            $this->_currentMaxPrice = Yii::app()->currency->convert($this->getMaxPrice());

        return $this->_currentMaxPrice;
    }

    public function applyPricesFilter()
    {
        $minPrice = Yii::app()->request->getQuery('min_price');
        $maxPrice = Yii::app()->request->getQuery('max_price');

        $cm = Yii::app()->currency;
        if ($cm->active->id !== $cm->main->id && ($minPrice > 0 || $maxPrice > 0)) {
            $minPrice = $cm->activeToMain($minPrice);
            $maxPrice = $cm->activeToMain($maxPrice);
        }

        if ($minPrice > 0)
            $this->query->applyMinPrice($minPrice);
        if ($maxPrice > 0)
            $this->query->applyMaxPrice($maxPrice);
    }

    /**
     * @return array of attributes used in http query and available in category
     */
    public function getActiveAttributes()
    {
        $data = array();

        if (false) {
            unset($_GET['token']);
            foreach (array_keys($_GET) as $key) {
                if (array_key_exists($key, $this->eavAttributes)) {
                    // $data[$key]['label'] =$this->eavAttributes[$key]->title;
                    if ((boolean)$this->eavAttributes[$key]->select_many === true) {
                        if (!Yii::app()->request->isAjaxRequest) {
                            $list = explode(',', $_GET[$key]);
                        } else {
                            $list = $_GET[$key];
                        }

                        $list = array_unique($list);
                        $data[$key] = $list;

                    } else {
                        $data[$key] = array($_GET[$key]);
                    }

                    sort($data[$key]);
                }
            }
        } else {
            unset($_GET['token']);

            foreach (array_keys($_GET) as $key) {
                if (array_key_exists($key, $this->eavAttributes)) {
                    // $data[$key]['label'] =$this->eavAttributes[$key]->title;
                    if ((boolean)$this->eavAttributes[$key]->select_many === true) {
                        if (strpos($_GET[$key], ',')) {
                            $list = explode(',', $_GET[$key]);
                        } else {
                            $list = array($_GET[$key]);
                        }
                        if (is_array($list)) {
                            $list = array_unique($list);
                        }

                        $data[$key] = $list;

                    } else {
                        $data[$key] = array($_GET[$key]);
                    }
                    if (is_array($list)) {
                        sort($data[$key]);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @return string min price
     */
    public function getMinPrice()
    {
        if ($this->_minPrice !== null)
            return $this->_minPrice;
        $this->_minPrice = $this->aggregatePrice('MIN');
        return $this->_minPrice;
    }

    /**
     * @return string max price
     */
    public function getMaxPrice()
    {
        $this->_maxPrice = $this->aggregatePrice('MAX');
        return $this->_maxPrice;
    }
}