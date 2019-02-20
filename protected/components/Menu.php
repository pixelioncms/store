<?php

Yii::import('zii.widgets.CMenu');

/**
 * This is the class for MenuModel "Menu".
 * 
 * @uses CMenu
 * @package components
 * @property array $items
 * @property array $submenuHtmlOptions
 * @property array $htmlOptions
 * @property array $linkLabelWrapperHtmlOptions
 * @property boolean $encodeLabel
 * @property boolean $activateItems
 * @property boolean $activateParents
 * @property boolean $hideEmptyItems
 * @property string $activeCssClass
 * @property string $firstItemCssClass
 * @property string $lastItemCssClass
 * @property string $itemCssClass
 */
class Menu extends CMenu {

    public $items = array();
    public $itemTemplate;
    public $encodeLabel = true;
    public $activeCssClass = 'active';
    public $activateItems = true;
    public $activateParents = false;
    public $hideEmptyItems = true;
    public $htmlOptions = array('id' => 'menu', 'class' => 'nav navbar-nav');
    public $submenuHtmlOptions = array();
    public $linkLabelWrapper;
    public $linkLabelWrapperHtmlOptions = array();
    public $firstItemCssClass = 'first-item';
    public $lastItemCssClass = 'last-item';
    public $itemCssClass = 'menu-item';
    public $sort = 'ASC';
   // public function init() {
       // return parent::init();
   // }

    /**
     * 
     * @param int $var
     * @return int
     */
    private function checkVisible($var) {
        return $var;
    }

    public function run() {
        $menus = MenuModel::model()->published()->findAll();
        $req = Yii::app()->request->hostInfo . Yii::app()->request->requestUri;
        $pagesItems=array();
        foreach ($menus as $item) {
           // if(isset($item->page)){
           //     $uri = $item->page->getUrl();
           // }else{
                $uri = $item->url;
            //}

            $url = Yii::app()->request->hostInfo . $uri;
            $active = ($url == $req) ? true : false;
            // $active = (preg_match('#'.$url.'#ui', $req))?true:false;
            $pagesItems[] = array(
                'label' => $item->label,
                'active' => $active,
                'url' => $url,
                'visible' => $this->checkVisible($item->switch)
            );
        }
        $items = CMap::mergeArray($pagesItems, $this->items);

        $this->renderMenu($items);
    }

}
