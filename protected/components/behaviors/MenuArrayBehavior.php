<?php

/**
 * Represent model as array needed to create CMenu.
 * Usage:
 * 	'MenuArrayBehavior'=>array(
 * 		'class'=>'app.behaviors.MenuArrayBehavior',
 * 		'labelAttr'=>'name',
 * 		'urlExpression'=>'array("/shop/category", "id"=>$model->id)',
 * TODO: Cache queries
 * 	)
 */
class MenuArrayBehavior extends CActiveRecordBehavior {

    /**
     * @var string Owner attribute to be placed in `label` key
     */
    public $labelAttr;
    private $cacheID = 'categories';
    protected $_cache = false;

    /**
     * @var string Expression will be evaluated to create url.
     * Example: 'urlExpression'=>'array("/shop/category", "id"=>$model->id)',
     */
    public $urlExpression;

    private function getDependency() {
        return new CDbCacheDependency('SELECT MAX(id) FROM {{shop_product}}');
        //return new CGlobalStateCacheDependency(null);
    }

    public function menuArray() {
        // $this->_cache = Yii::app()->cache->get($this->cacheID);
        // if ($this->_cache === false) {
        $this->_cache = $this->walkArray($this->owner);
        //     Yii::app()->cache->set($this->cacheID, $this->_cache,3600,new CExpressionDependency());
        // }
        return $this->_cache;
    }

    /**
     * Recursively build menu array
     * @param $model CActiveRecord model with NestedSet behavior
     * @return array
     */
    protected function walkArray($model) {
        $url = $this->evaluateUrlExpression($this->urlExpression, array('model' => $model));
        $data = array(
            'label' => $model->{$this->labelAttr},
            'url' => $url,
            'id' => $model->primaryKey,
            //'imagePath' => $model->getImageUrl('image', 'categories', '140x140'),
            'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
            'itemOptions' => array('class' => 'dropdown menu-item'),
            'total_count' => $model->countProducts, //count($model->productsPublished)
        );
        // TODO: Cache result
        $children = $model->children()
                ->published()

                ->findAll();
        if (!empty($children)) {
            foreach ($children as $c)
                $data['items'][] = $this->walkArray($c);
        }
        return $data;
    }

    /**
     * @param $expression
     * @param array $data
     * @return mixed
     */
    public function evaluateUrlExpression($expression, $data = array()) {
        extract($data);
        return eval('return ' . $expression . ';');
    }

}
