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
    //private $_minPrice, $_maxPrice;
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
        $query->select = $function . '(`t`.`price`) as aggregation_price';
        $query->limit = 1;
        $query->order = ($function === 'MIN') ? '`t`.`price`' : '`t`.`price` DESC';
        $result = ShopProduct::model();
        $result->getDbCriteria()->mergeWith($query);
        $r = $result->find();

        if ($r) {
            return $r->aggregation_price;
        }
        return null;
    }

    public function applyPricesFilter()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $minPrice = Yii::app()->request->getPost('min_price');
            $maxPrice = Yii::app()->request->getPost('max_price');
        } else {
            $minPrice = Yii::app()->request->getQuery('min_price');
            $maxPrice = Yii::app()->request->getQuery('max_price');
        }


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
if(is_array($list)){
    $list = array_unique($list);
}

                        $data[$key] = $list;

                    } else {
                        $data[$key] = array($_GET[$key]);
                    }
                    if(is_array($list)) {
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