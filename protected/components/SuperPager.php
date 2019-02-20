<?php

/**
 * LinkPager class file.
 * Данный класс для ListView
 */
class SuperPager extends CBasePager {

    const CSS_FIRST_PAGE = 'page-item first';
    const CSS_LAST_PAGE = 'page-item last';
    const CSS_PREVIOUS_PAGE = 'page-item previous';
    const CSS_NEXT_PAGE = 'page-item next';
    const CSS_INTERNAL_PAGE = 'page-item';
    const CSS_HIDDEN_PAGE = 'page-item hidden';
    const CSS_SELECTED_PAGE = 'page-item active';

    public $paramTest;
    public $showMoreLabel;
    public $autoShowMoreLabel = false;
    public $enableShowmore = true;
    public $gridid;

    /**
     * @var string the CSS class for the first page button. Defaults to 'first'.
     * @since 1.1.11
     */
    public $firstPageCssClass = self::CSS_FIRST_PAGE;

    /**
     * @var string the CSS class for the last page button. Defaults to 'last'.
     * @since 1.1.11
     */
    public $lastPageCssClass = self::CSS_LAST_PAGE;

    /**
     * @var string the CSS class for the previous page button. Defaults to 'previous'.
     * @since 1.1.11
     */
    public $previousPageCssClass = self::CSS_PREVIOUS_PAGE;

    /**
     * @var string the CSS class for the next page button. Defaults to 'next'.
     * @since 1.1.11
     */
    public $nextPageCssClass = self::CSS_NEXT_PAGE;

    /**
     * @var string the CSS class for the internal page buttons. Defaults to 'page'.
     * @since 1.1.11
     */
    public $internalPageCssClass = self::CSS_INTERNAL_PAGE;

    /**
     * @var string the CSS class for the hidden page buttons. Defaults to 'hidden'.
     * @since 1.1.11
     */
    public $hiddenPageCssClass = self::CSS_HIDDEN_PAGE;

    /**
     * @var string the CSS class for the selected page buttons. Defaults to 'selected'.
     * @since 1.1.11
     */
    public $selectedPageCssClass = self::CSS_SELECTED_PAGE;

    /**
     * @var integer maximum number of page buttons that can be displayed. Defaults to 10.
     */
    public $maxButtonCount = 10;

    /**
     * @var string the text label for the next page button. Defaults to 'Next &gt;'.
     */
    public $nextPageLabel;

    /**
     * @var string the text label for the previous page button. Defaults to '&lt; Previous'.
     */
    public $prevPageLabel;

    /**
     * @var string the text label for the first page button. Defaults to '&lt;&lt; First'.
     */
    public $firstPageLabel;

    /**
     * @var string the text label for the last page button. Defaults to 'Last &gt;&gt;'.
     */
    public $lastPageLabel;

    /**
     * @var string the text shown before page buttons. Defaults to 'Go to page: '.
     */
    public $header;

    /**
     * @var string the text shown after page buttons.
     */
    public $footer = '';

    /**
     * @var mixed the CSS file used for the widget. Defaults to null, meaning
     * using the default CSS file included together with the widget.
     * If false, no CSS file will be used. Otherwise, the specified CSS file
     * will be included when using this widget.
     */
    public $cssFile;

    /**
     * @var array HTML attributes for the pager container tag.
     */
    public $htmlOptions = array();
    public $get_cp = 0;
    public $session;
    /**
     * Initializes the pager by setting some default property values.
     */
    public function init() {
        if ($this->nextPageLabel === null)
            $this->nextPageLabel = Yii::t('app', 'PAGER_NEXT');
        if ($this->prevPageLabel === null)
            $this->prevPageLabel = Yii::t('app', 'PAGER_PREV');
        if ($this->firstPageLabel === null)
            $this->firstPageLabel = Yii::t('app', 'PAGER_FIRST');
        if ($this->lastPageLabel === null)
            $this->lastPageLabel = Yii::t('app', 'PAGER_LAST');
        if ($this->header === null)
            $this->header = Yii::t('yii', 'Go to page: ');
        if ($this->showMoreLabel === null)
            $this->showMoreLabel = Yii::t('app', 'PAGER_SHOWMORE');

        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'yiiPager';

        $this->session = Yii::app()->session;
        $cfg = Yii::app()->settings->get($this->gridid, 'current_page');
        //$this->get_cp = (isset($cfg))?$cfg:0;
        $this->get_cp = (isset(Yii::app()->session[$this->gridid.'_current_page']))?Yii::app()->session[$this->gridid.'_current_page']:0;

    }


    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run() {
        $this->registerClientScript();
        $request = Yii::app()->request;

        $buttons = $this->createPageButtons();
        if (empty($buttons))
            return;

        echo $this->header;




        if ($this->enableShowmore) {
            $hideEndResult = true;
            if (($pageCount = $this->getPageCount()) <= 1)
                return array();

            list($beginPage, $endPage) = $this->getPageRange();
            $currentPage = $this->getCurrentPage(true); // currentPage is calculated in getPageRange()

            if (($page = $currentPage + 1) >= $pageCount - 1)
                $page = $pageCount - 1;
        }else {
            $page = true;
            $currentPage = false;
        }



        if ($page != $currentPage) { //remove all pagination in latast
            if ($this->enableShowmore) {
                echo Html::tag('div', array('class' => 'showmore-block'), $this->createButtonShowMore(), true);
      
            }
        }
        echo Html::tag('ul', $this->htmlOptions, implode("\n", $buttons));

        echo $this->footer;
    }

    protected function createButtonShowMore() {


        if (($pageCount = $this->getPageCount()) <= 1)
            return array();

        list($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(true); // currentPage is calculated in getPageRange()

        if (($page = $currentPage + 1) >= $pageCount - 1)
            $page = $pageCount - 1;


        if ($endPage - 1 == $currentPage) {
            $total = $this->getItemCount();
            for ($x = 0; $x < $endPage; $x++) {
                $total -= $this->getPages()->pageSize;
            }
            $labelCount = $total;
        } else {
            $labelCount = $this->getPages()->pageSize;
        }



        $limit = $this->getPages()->pageSize;
        $offset = 0;

        $result = array();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $offset+=$this->getPages()->pageSize;
            $limit+=$this->getPages()->pageSize;
            $result['limit'][$i] = $limit;
            $result['offset'][$i] = $offset - $this->getPages()->pageSize;
        }
        $test2 = array();
        for ($i = 0; $i <= $currentPage; ++$i) {
            $test2[$i] = $result['limit'][$i];
        }
        // print_r($test2[$currentPage]);
        /* $offsets=array();
          foreach($result['limit'] as $k=>$off){
          $offsets[]=abs($result['limit'][$currentPage]-$off);
          }
          print_r($offsets); */
        /* echo '<hr>';
          print_r($result['limit']);
          echo '<hr>';
          echo 'limit: ' . ($result['limit'][$currentPage] - $result['offset'][$currentPage]);
          echo '<br>';
          // $test= array_reverse($result['limit']);
          //print_r($test);
          echo 'offset: ' . $result['offset'][$currentPage];
          echo '<br>'; */


        // if($this->session['current_page']==0){
        if (isset($_GET['offset']) && isset($_GET['limit'])) {

            //  echo 'calc';
        } else {

            //   if ($value === false) {
            // устанавливаем значение $value заново, т.к. оно не найдено в кэше,
            // и сохраняем его в кэше для дальнейшего использования:
            Yii::app()->cache->set('m1', $result['offset'][$currentPage], 60 * 120);
            //   }
            // print_r($_GET);
            $value = Yii::app()->cache->get('m1');

            //  echo $value;
            $data = array('offset' => $result['offset'][$currentPage], 'limit' => $result['limit'][$currentPage]);

            //$data=array();
        }
        // }else{
        //     echo 'no calc222222';
        // }






        $showmoreLabel = ($this->autoShowMoreLabel) ? self::GetFormatWord($labelCount) : $this->showMoreLabel;
        $buttons = $this->createShowMoreButton(strtr($showmoreLabel, array('{count}' => $labelCount)), $page, $data);
        return $buttons;
    }

    public static function GetFormatWord($number) {
        $num = $number % 10;
        $modClass = ucfirst(Yii::app()->controller->module->id) . 'Module';
        if ($num == 1)
            return Yii::t("{$modClass}.default", 'PAGER_SHOWMORE', 0);
        elseif ($num > 1 && $num < 5)
            return Yii::t("{$modClass}.default", 'PAGER_SHOWMORE', 1);
        else
            return Yii::t("{$modClass}.default", 'PAGER_SHOWMORE', 2);
    }

    protected function createShowMoreButton($label, $page, $data) {
        //  unset($_GET['page']);

        return Html::link($label, $this->createShowMoreUrl($this->getController(), $page, $data), array('id' => 'showmore','data-page'=>$page));
    }

    public function getCurrentPage2($recalculate = true) {
        return $this->getPages()->getCurrentPage2($recalculate);
    }

    public function createShowMoreUrl($controller, $page, $data) {


        $params = $this->getPages()->params === null ? $_GET : $this->getPages()->params;
          //    $params['tester'] = 1;
        if ($page > 0) { // page 0 is the default
            $result = array();
            $offset = 0;
            $limit = $this->getPages()->pageSize;
            for ($x = 0; $x < $this->getPageCount(); $x++) {
                $offset+=$this->getPages()->pageSize;
                $result['offset'][$x] = $offset - $this->getPages()->pageSize;
                $limit+=$this->getPages()->pageSize;
                $result['limit'][$x] = $limit;
            }
            $params[$this->getPages()->pageVar] = $page + 1;


            /// $params['offset'] = $data['offset'];
            //CVarDumper::dump($result, 30, true) ;
            // // echo Yii::app()->settings->get('news','current_page');
      
           // $params['current_page'] = Yii::app()->user->setState('cp',$page);//$this->get_cp; //-1
           // $params['offset'] = $result['offset'][$this->get_cp];
        } else {
            unset($params[$this->getPages()->pageVar]);
        }

        return $controller->createUrl($this->getPages()->route, $params);
    }

    /**
     * Creates the page buttons.
     * @return array a list of page buttons (in HTML code).
     */
    protected function createPageButtons() {
        if (($pageCount = $this->getPageCount()) <= 1)
            return array();



        list($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(false);
        $currentPage2 = $this->get_cp; // currentPage is calculated in getPageRange()
        $buttons = array();
        // first page

        if ($this->firstPageLabel) {
            $buttons[] = $this->createPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }
        // prev page
        if ($this->prevPageLabel) {
            if (($page = $currentPage - 1) < 0)
                $page = 0;
            $buttons[] = $this->createPageButton($this->prevPageLabel, $page, $this->previousPageCssClass, $currentPage <= 0, false);
        }
        // internal pages
        $arrayRange = range($currentPage2,$currentPage);

        for ($i = $beginPage; $i <= $endPage; ++$i) {
            if ($this->enableShowmore) {

                
                //echo '<br>';
                //$active = $i == $currentPage;

                if ($i <= $currentPage) {
                   // $active = true;

                } else {
                   // $active = false;
                    //  $active = ($i == $currentPage) ? true : false;
                }
                if ($currentPage == $endPage) {
                   // $active = ($i == $currentPage) ? true : false;
                }
                //if(isset($_GET['current_page'])){
                if(in_array($i, $arrayRange)){
                         $active = true;
                }else{
                    $active=false;
                }
                //}else{
                //    $active = $i == $currentPage;
                //}

                /* if ($currentPage == $endPage) {
                  $active = ($i == $currentPage) ? true : false;
                  } else {
                  if ($currentPage2 >= $i) {
                  $active = true;
                  } else {
                  $active = false;
                  }
                  } */
            } else {
                $active = $i == $currentPage;
                $this->internalPageCssClass = self::CSS_INTERNAL_PAGE;
            }

            //$this->getPages()->params['limit']=$this->getItemCount();
            $buttons[] = $this->createPageButton($i + 1, $i, $this->internalPageCssClass, false, $active);
        }
        // next page
        if ($this->nextPageLabel) {
            if (($page = $currentPage + 1) >= $pageCount - 1)
                $page = $pageCount - 1;
            $buttons[] = $this->createPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }
        // last page
        if ($this->lastPageLabel) {
            $buttons[] = $this->createPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }
        return $buttons;
    }

    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button.
     * @param boolean $hidden whether this page button is visible
     * @param boolean $selected whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label, $page, $class, $hidden, $selected) {

        if ($hidden || $selected)
            $class.=' ' . ($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
        unset($_GET['ajax'],$_GET['scroll']); //Append by PANIX
        if (!$hidden) {
            unset($_GET['limit'], $_GET['offset'], $_GET['current_page']);
            //$_GET['lm']='false';
            return '<li class="' . $class . '">' . Html::link($label, $this->createPageUrl($page),array('class'=>'page-link')) . '</li>';
        }
    }

    protected function createPageUrl($page) {
        return $this->getPages()->createPageUrl($this->getController(), $page);
    }

    /**
     * @return array the begin and end pages that need to be displayed.
     */
    protected function getPageRange() {
        $currentPage = $this->getCurrentPage();
        $pageCount = $this->getPageCount();

        $beginPage = max(0, $currentPage - (int) ($this->maxButtonCount / 2));
        if (($endPage = $beginPage + $this->maxButtonCount - 1) >= $pageCount) {
            $endPage = $pageCount - 1;
            $beginPage = max(0, $endPage - $this->maxButtonCount + 1);
        }
        return array($beginPage, $endPage);
    }

    /**
     * Registers the needed client scripts (mainly CSS file).
     */
    public function registerClientScript() {
        $assets = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('ext.adminList.assets'), false, -1, YII_DEBUG
        );
        //Yii::app()->getClientScript()->registerScriptFile($assets . '/jquery.session.js', CClientScript::POS_HEAD);
       // Yii::app()->getClientScript()->registerScriptFile($assets . '/url.min.js', CClientScript::POS_HEAD);
        if ($this->cssFile !== false) {
            self::registerCssFile($this->cssFile);
        }
    }

    /**
     * Registers the needed CSS file.
     * @param string $url the CSS URL. If null, a default CSS URL will be used.
     */
    public static function registerCssFile($url = null) {
        if ($url === null) {
            $url = Html::asset(Yii::getPathOfAlias('system.web.widgets.pagers.pager') . '.css');
        }
        Yii::app()->getClientScript()->registerCssFile($url);
    }

}
