<?php

/**
 * Present ShopCategory as JsTree node.
 */
class ShopCategoryNodeNew extends CComponent implements ArrayAccess {

    /**
     * @var ShopCategory
     */
    protected $model;

    /**
     * @var string category name
     */
    protected $_name;

    /**
     * @var integer category id
     */
    protected $_id;

    /**
     * @var bool category switch
     */
    protected $_switch;

    /**
     * @var boolean
     */
    protected $_hasChildren;

    /**
     * @var array category children
     */
    protected $_children;
    protected $options = array();

    /**
     * @param ShopCategory $model
     */
    public function __construct(ShopCategory $model, $options = array()) {

        /*$this->options = & $options;*/
        $this->model = & $model;
        $result =array();
        foreach($this->model as $row){
            $result[]=array('text'=>$row->name);
        }
       return $result;
        //return $this;
    }

    /**
     * Create nodes from array
     * @static
     * @param array $model
     * @return array
     */
    public static function fromArray($model, $options = array()) {
        $result = array();
        foreach ($model as $row){
            if($options['switch'] || $row->switch){
                $result[] =new ShopCategoryNodeNew($row, $options);
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function getHasChildren() {
        return (boolean) $this->model->children()->count();
    }

    /**
     * @return array
     */
    public function getChildren() {
        return self::fromArray($this->model->children()->findAll(), $this->options);
    }

    /**
     * @return string
     */
    public function getName() {
        if (isset($this->options['displayCount']) && $this->options['displayCount'])
            return "{$this->model->name} ({$this->model->countProducts})";
        else
            return $this->model->name;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->model->id;
    }

    /**
     * @return string
     */
    public function getSwitch() {
        return $this->model->switch;
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->{$offset};
    }

    public function offsetExists($offset) {
        
    }

    public function offsetSet($offset, $value) {
        
    }

    public function offsetUnset($offset) {
        
    }

}
