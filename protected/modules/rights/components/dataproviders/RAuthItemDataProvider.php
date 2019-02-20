<?php

/**
 * Authorization item data provider class file.
 *
 * @author Christoffer Niska <cniska@live.com>
 * @copyright Copyright &copy; 2010 Christoffer Niska
 * @since 0.9.10
 */
class RAuthItemDataProvider extends CDataProvider {

    public $type;
    public $userId;
    public $parent;
    public $exclude = array();
    public $items;
    //public $sortable;
    public $modelClass;

    /**
     * Constructs the data provider.
     * @param string $id the data provider identifier.
     * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
     * @return RightsAuthItemDataProvider
     */
    public function __construct($id, $config = array()) {
        $this->setId($id);

        foreach ($config as $key => $value)
            $this->$key = $value;
    }

    /**
     * Fetches the data from the persistent data storage.
     * @return array list of data items
     */
    public function fetchData() {
        //if ($this->sortable !== null)
           // $this->processSortable();

        if ($this->items === null)
            $this->items = Rights::getAuthorizer()->getAuthItems($this->type, $this->userId, $this->parent, true, $this->exclude);

        $data = array();
        foreach ($this->items as $name => $item)
            $data[] = $item;

        return $data;
    }

    /**
     * Fetches the data item keys from the persistent data storage.
     * @return array list of data item keys.
     */
    public function fetchKeys() {
        $keys = array();
        foreach ($this->getData() as $name => $item)
            $keys[] = $name;

        return $keys;
    }


    /**
     * Calculates the total number of data items.
     * @return integer the total number of data items.
     */
    protected function calculateTotalItemCount() {
        return count($this->getData());
    }

}
