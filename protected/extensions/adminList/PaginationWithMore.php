<?php

Yii::import('system.web.CPagination');

class PaginationWithMore extends CPagination {

    /**
     * The default page size.
     */
   // const DEFAULT_PAGE_SIZE = 5;

    /**
     * @var string name of the GET variable storing the current page index. Defaults to 'page'.
     */
   // public $pageVar = 'page';

    /**
     * @var string the route (controller ID and action ID) for displaying the paged contents.
     * Defaults to empty string, meaning using the current route.
     */
    //public $route = '';

    /**
     * @var array of parameters (name=>value) that should be used instead of GET when generating pagination URLs.
     * Defaults to null, meaning using the currently available GET parameters.
     */
    //public $params;

    /**
     * @var boolean whether to ensure {@link currentPage} is returning a valid page number.
     * When this property is true, the value returned by {@link currentPage} will always be between
     * 0 and ({@link pageCount}-1). Because {@link pageCount} relies on the correct value of {@link itemCount},
     * it means you must have knowledge about the total number of data items when you want to access {@link currentPage}.
     * This is fine for SQL-based queries, but may not be feasible for other kinds of queries (e.g. MongoDB).
     * In those cases, you may set this property to be false to skip the validation (you may need to validate yourself then).
     * Defaults to true.
     * @since 1.1.4
     */
   /// public $validateCurrentPage = true;
   // public $_pageSize = self::DEFAULT_PAGE_SIZE;
    //private $_itemCount = 0;
    private $_currentPage;
    private $_currentPage2;
   // private $limitArray = array();

    /**
     * Constructor.
     * @param integer $itemCount total number of items.

      public function __construct($itemCount = 0) {
      $this->setItemCount($itemCount);

      } */
    /**
     * @return integer number of items in each page. Defaults to 10.

      public function getPageSize() {
      return $this->_pageSize;
      } */
    /**
     * @param integer $value number of items in each page

      public function setPageSize($value) {
      if (($this->_pageSize = $value) <= 0)
      $this->_pageSize = self::DEFAULT_PAGE_SIZE;
      } */
    /**
     * @return integer total number of items. Defaults to 0.

      public function getItemCount() {
      return $this->_itemCount;
      } */
    /**
     * @param integer $value total number of items.

      public function setItemCount($value) {
      if (($this->_itemCount = $value) < 0)
      $this->_itemCount = 0;
      } */
    /**
     * @return integer number of pages

      public function getPageCount() {
      return (int) (($this->_itemCount + $this->_pageSize - 1) / $this->_pageSize);
      } */

    /**
     * @param boolean $recalculate whether to recalculate the current page based on the page size and item count.
     * @return integer the zero-based index of the current page. Defaults to 0.
     
    public function getCurrentPage($recalculate = true) {
        if ($this->_currentPage === null || $recalculate) {
            if (isset($_GET[$this->pageVar])) {
                $this->_currentPage = (int) $_GET[$this->pageVar] - 1;
                if ($this->validateCurrentPage) {
                    $pageCount = $this->getPageCount();
                    if ($this->_currentPage >= $pageCount)
                        $this->_currentPage = $pageCount - 1;
                }
                if ($this->_currentPage < 0)
                    $this->_currentPage = 0;
            } else
                $this->_currentPage = 0;
        }
        return $this->_currentPage;
    }*/

    public function getCurrentPage2($recalculate = true) {
        if ($this->_currentPage === null || $recalculate) {
            if (isset($_GET['current_page'])) {
                $this->_currentPage = (int) $_GET['current_page'] - 1;
                if ($this->validateCurrentPage) {
                    $pageCount = $this->getPageCount();
                    if ($this->_currentPage >= $pageCount)
                        $this->_currentPage = $pageCount - 1;
                }
                if ($this->_currentPage < 0)
                    $this->_currentPage = 0;
            } else
                $this->_currentPage = 0;
        }
        return $this->_currentPage;
    }

    /**
     * @param integer $value the zero-based index of the current page.
     */
    public function setCurrentPage($value) {
        $this->_currentPage = $value;
        $_GET[$this->pageVar] = $value + 1;
    }

    /**
     * Applies LIMIT and OFFSET to the specified query criteria.
     * @param CDbCriteria $criteria the query criteria that should be applied with the limit
     */
    public function applyLimit($criteria) {

        $criteria->limit = $this->getLimit();
        $criteria->offset = $this->getOffset();
    }

    public function createPageUrl($controller, $page) {
        $params = $this->params === null ? $_GET : $this->params;
        if ($page > 0){ // page 0 is the default
            $params[$this->pageVar] = $page + 1;
        }else
            unset($params[$this->pageVar]); //unset ajax for change page js //,$_GET['ajax']
        return $controller->createUrl($this->route, $params);
    }

    /**
     * @return integer the offset of the data. This may be used to set the
     * OFFSET value for a SQL statement for fetching the current page of data.
     * @since 1.1.0
     */
    public function getOffset() {
        if (isset($_GET['offset'])) {
            return Yii::app()->request->getParam('offset');
        } else {
            return $this->getCurrentPage() * $this->getPageSize();
        }
    }

    /**
     * @return integer the limit of the data. This may be used to set the
     * LIMIT value for a SQL statement for fetching the current page of data.
     * This returns the same value as {@link pageSize}.
     * @since 1.1.0
     */
    public function getLimit() {

        if (Yii::app()->request->getParam('limit')) {
            return Yii::app()->request->getParam('limit');
        } else {
            return $this->getPageSize();
        }
    }

}
