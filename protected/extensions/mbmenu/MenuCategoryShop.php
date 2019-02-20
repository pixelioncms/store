<?php

Yii::import('zii.widgets.CMenu');

class MenuCategoryShop extends CMenu {

    private $baseUrl;
    private $nljs;
    public $cssFile;
    public $activateParents = true;
    public $totalCount = false;
    public $level=0;

    /**
     * Give the last items css 'parent' style 
     */
    protected function cssParentItems($items) {

        foreach ($items as $i => $item) {
            if (isset($item['items'])) {
                if (isset($item['itemOptions']['class']))
                    $items[$i]['itemOptions']['class'].=' ';
                else
                    $items[$i]['itemOptions']['class'] = ''; //parent

                $items[$i]['items'] = $this->cssParentItems($item['items']);
            }
        }

        return array_values($items);
    }

    /**
     * Initialize the widget
     */
    public function init() {
       // if (!$this->getId(false))
        //    $this->setId('cssmenu');

        $this->nljs = "\n";
        $this->items = $this->cssParentItems($this->items);
        //$this->items=$this->cssLastItems($this->items);

        parent::init();
    }

    /**
     * Publishes the assets
     */
    public function publishAssets() {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'source';
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
    }

    /**
     * Registers the external javascript files
     */
    public function registerClientScripts() {
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($this->cssFile, 'screen');
    }

    public function registerCssFile($url = null) {

        if ($this->baseUrl === '')
            throw new CException(Yii::t('MbMenu', 'baseUrl must be set. This is done automatically by calling publishAssets()'));

    }

    protected function renderMenuRecursive($items) {
            $this->level++;
        foreach ($items as $item) {

            if ($this->totalCount) {
                $totalCount = '<span class="total_count">(' . $item['total_count'] . ')</span>';
            } else {
                $totalCount = '';
            }
            if($this->level==2){
                 echo Html::openTag('li', array('class'=>'yamm-content'));
            }else{
                 echo Html::openTag('li', isset($item['itemOptions']) ? $item['itemOptions'] : array());
            }
           
            if (isset($item['url'])){
                $activeClass = (Yii::app()->request->url == '/'.$item['url']['url']) ? 'active' : '';

                if(isset($item['linkOptions'])){
                        $item['linkOptions']['class'].=' '.$activeClass;
                    $linkOptions = $item['linkOptions'];
                
                }else{
                    $linkOptions = array();
                }
                echo Html::link($item['label'] . ' ' . $totalCount.$this->level, $item['url'], $linkOptions);
            }else{
                echo Html::link($item['label'] . ' ' . $totalCount, "javascript:void(0);", $linkOptions);
            }
            if (isset($item['items']) && count($item['items'])) {
                echo "\n" . Html::openTag('ul', $this->submenuHtmlOptions) . "\n";
                $this->renderMenuRecursive($item['items']);
                echo Html::closeTag('ul') . "\n";
            }
            echo Html::closeTag('li') . "\n";
        }
    }

    protected function normalizeItems($items, $route, &$active, $ischild = 0) {

        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if ($this->encodeLabel)
                $items[$i]['label'] = CHtml::encode($item['label']);
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $route, $hasActiveChild, 1);
                if (empty($items[$i]['items']) && $this->hideEmptyItems)
                    unset($items[$i]['items']);
            }
            print_r($item['active']);
            if (!isset($item['active'])) {
                if (($this->activateParents && $hasActiveChild) || $this->isItemActive($item, $route)) {
                     $active = $items[$i]['active'] = true;
                } else {

                   
                   /* foreach($items[$i]['items'] as $item){
                        if(Yii::app()->request->url == '/'.$item['url']['url']){
                            $item['linkOptions']=array('class'=>'active');
                            echo 'act';
                        }else{
                             $item['active']=false; 
                        }

                    }*/
                     $items[$i]['active'] = false;
                 
                }
            } else if ($item['active'])
                $active = true;
            if ($items[$i]['active'] && $this->activeCssClass != '' && !$ischild) {
                if (isset($item['itemOptions']['class'])) {
                    $items[$i]['itemOptions']['class'].=' ' . $this->activeCssClass;
                    $items[$i]['linkOptions']['class'].=' ' . $this->activeCssClass;
                    // print_r($this->getController()->getRoute());
                } else {
                    $items[$i]['linkOptions']['class']=$this->activeCssClass;
                    $items[$i]['itemOptions']['class'] = $this->activeCssClass;
                }
            }
        }
        return array_values($items);
    }

    /**
     * Run the widget
     */
    public function run() {
        $this->publishAssets();
        $this->registerClientScripts();

        parent::run();

    }

}