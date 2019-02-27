<?php

Yii::import('mod.shop.models.ShopProduct');
Yii::import('mod.shop.models.ShopAttribute');

/**
 * Handle compare lists
 */
class CompareProducts extends CComponent {

    /**
     * Max products to compare
     */
    const MAX_PRODUCTS = 20;

    /**
     * @var string
     */
    public $sessionKey = 'CompareProducts';

    /**
     * @var CHttpSession
     */
    public $session;

    /**
     * @var array
     */
    private $_products;

    /**
     * @var array
     */
    private $_attributes;

    /**
     * Initialize component
     */
    public function __construct() {
        $this->session = Yii::app()->session;

        if (!isset($this->session[$this->sessionKey]) || !is_array($this->session[$this->sessionKey]))
            $this->session[$this->sessionKey] = array();
    }

    /**
     * Check if product exists add to list
     * @param string $id product id
     */
    public function add($id) {
        if ($this->count() <= self::MAX_PRODUCTS && ShopProduct::model()->published()->countByAttributes(array('id' => $id)) > 0) {
            $current = $this->getIds();
            $current[(int) $id] = (int) $id;
            $this->setIds($current);
            return true;
        }
        return false;
    }

    /**
     * Remove product from list
     * @param $id
     */
    public function remove($id) {
        $current = $this->getIds();
        if (isset($current[$id]))
            unset($current[$id]);
        $this->setIds($current);
    }

    /**
     * @return array of product id added to compare
     */
    public function getIds() {
        return $this->session[$this->sessionKey];
    }

    /**
     * @param array $ids
     */
    public function setIds(array $ids) {
        $this->session[$this->sessionKey] = array_unique($ids);
    }

    /**
     * Clear compare list
     */
    public function clear() {
        $this->setIds(array());
    }

    /**
     * @return array of ShopProduct models to compare
     */
    public function getProducts() {
        if ($this->_products === null)
            $this->_products = ShopProduct::model()->findAllByPk(array_values($this->getIds()));


        $result = array();


        foreach ($this->_products as $state) {

            $cid = $state->mainCategory->id;
            // Create the sub-array if it doesn't exist
            if (!isset($result[$cid])) {
                $result[$cid]['items'] = array();
                $result[$cid]['name'] = $state->mainCategory->name;
            }

            // Then append the state onto it
            $result[$cid]['items'][] = $state;
            $result[$cid]['name'] = $state->mainCategory->name;
        }
        return $result;
    }

    /**
     * Count products to compare
     * @return int
     */
    public function count() {
        return sizeof($this->getIds());
    }

    /**
     * Count user compare items without creating new instance
     * @static
     * @return int
     */
    public static function countSession() {
        $count = (Yii::app()->session['CompareProducts'])?Yii::app()->session['CompareProducts']:array();
        return sizeof($count);
    }
    /**
     * Load ShopAttribute models by names
     * @return array of ShopAttribute models
     */
    public function getAttributes() {

        $this->_products = ShopProduct::model()->findAllByPk(array_values($this->getIds()));
        $result = array();
        foreach ($this->_products as $state) {
            $cid = $state->mainCategory->id;
            // Create the sub-array if it doesn't exist
            if (!isset($result[$cid])) {
                // $result[$cid]['items'] = array();
                //$result[$cid]['name'] = $state->mainCategory->name;
            }

            // Then append the state onto it
            $result[$cid]['items'][] = array('list1', 'list2'); //$state
            $result[$cid]['name'] = $state->mainCategory->name;
             $result[$cid][$state->id]['attrs'] = array();
            
                if (isset($result[$cid][$state->id]['attrs'])) {
           
                    $names = array();
                    foreach ($this->_products as $p)
                        $names = array_merge($names, array_keys($p->getEavAttributes()));

                    $cr = new CDbCriteria;
                    $cr->addInCondition('t.name', $names);

                    $query = ShopAttribute::model()
                            ->displayOnFront()
                            ->useInCompare()
                            ->findAll($cr);

                    foreach ($query as $m) {
                        $result[$cid][$state->id]['attrs'][$m->name] = $m;
                        $result[$cid]['filter_name'][$m->name] = $m;
                    }
                }
          
        }




        return $result;
    }

    public function getAttributesold() {

        $this->_products = ShopProduct::model()->findAllByPk(array_values($this->getIds()));
        $result = array();
        foreach ($this->_products as $state) {
            $cid = $state->mainCategory->id;
            // Create the sub-array if it doesn't exist
            if (!isset($result[$cid])) {
                $result[$cid]['items'] = array();
                $result[$cid]['name'] = $state->mainCategory->name;
            }

            // Then append the state onto it
            $result[$cid]['items'][] = $state;
            $result[$cid]['name'] = $state->mainCategory->name;
            if ($result[$cid]['attr'] === null) {
                $result[$cid]['attr'] = array();
                $names = array();
                foreach ($this->_products as $p)
                    $names = array_merge($names, array_keys($p->getEavAttributes()));

                $cr = new CDbCriteria;
                //$cr->with=array('options');
                //$cr->distinct=true;
                // $cr->select = "options.value";
                $cr->addInCondition('t.name', $names);

                $query = ShopAttribute::model()
                        /* ->with(array(
                          'options'=>array(
                          'distinct'=>true,
                          'select'=>'value'
                          )
                          )) */
                        ->displayOnFront()
                        ->useInCompare()
                        ->findAll($cr);

                foreach ($query as $m) {
                    //if(array_unique($m))
                    $result[$cid]['attr'][$m->name] = $m;

                    foreach ($result[$cid]['items'] as $product) {

                        $value = $product->{'eav_' . $m->name};

                        //   echo $value === null ? Yii::t('ShopModule.default', 'Не указано') : $value;
                    }
                }
            }
        }



        return $result;
    }

    public function getAttributesById($id) {

        $this->_products = ShopProduct::model()->findByPk($id);
        $result = array();
        $cid = $this->_products->mainCategory->id;
        // Create the sub-array if it doesn't exist
        if (!isset($result[$cid])) {
            // $result[$cid]['items'] = array();
            //$result[$cid]['name'] = $state->mainCategory->name;
        }

        // Then append the state onto it
        $result[$cid]['items'][] = array('list1', 'list2'); //$state
        $result[$cid]['name'] = $this->_products->mainCategory->name;
        if ($result[$cid]['attrs'] === null) {
            $result[$cid]['attrs'] = array();
            $names = array();

            $names = array_merge($names, array_keys($this->_products->getEavAttributes()));

            $cr = new CDbCriteria;
            //$cr->with=array('options');
            //$cr->distinct=true;
            // $cr->select = "options.value";
            $cr->addInCondition('t.name', $names);

            $query = ShopAttribute::model()
                    /* ->with(array(
                      'options'=>array(
                      'distinct'=>true,
                      'select'=>'value'
                      )
                      )) */
                    ->displayOnFront()
                    ->useInCompare()
                    ->findAll($cr);

            foreach ($query as $m) {
                //if(array_unique($m))
                $result[$cid][$state->id]['attrs'][$m->name] = $m;
                $result[$cid]['filter_name'][$m->name] = $m;
            }
        }



        return $result;
    }

}

/**
 * 
        if ($this->_attributes === null) {
            $this->_attributes = array();
            $names = array();
            foreach ($this->getProducts() as $p)
                $names = array_merge($names, array_keys($p->getEavAttributes()));

            $cr = new CDbCriteria;
            $cr->addInCondition('t.name', $names);
            $query = ShopAttribute::model()
                    ->displayOnFront()
                    ->useInCompare()
                    ->findAll($cr);

            foreach ($query as $m)
                $this->_attributes[$m->name] = $m;
        }
        return $this->_attributes;
 */