<?php

Yii::import('zii.widgets.CMenu');

class AdminMenu extends CMenu
{

    private $nljs;
    public $cssFile;
    public $activateParents = true;
    public $totalCount = false;
    public $ajax = false;
    //public $items = array();
    public $submenuHtmlOptions = array('class' => 'dropdown-menu');
    public $htmlOptions = array('class' => 'nav navbar-nav mr-auto', 'id' => 'navbar');

    const CACHE_ID = 'EngineMainMenu';

    /**
     * Give the last items css 'parent' style
     */
    protected function cssParentItems($items)
    {

        foreach ($items as $i => $item) {
            if (isset($item['items'])) {
                if (isset($item['itemOptions']['class']))
                    $items[$i]['itemOptions']['class'] .= 'nav-item dropdown';
                else
                    $items[$i]['itemOptions']['class'] = 'nav-item dropdown';

                $items[$i]['items'] = $this->cssParentItems($item['items']);
            }
        }

        return array_values($items);
    }

    /**
     * Initialize the widget
     */
    public function init()
    {
        if (!$this->getId(false))
            $this->setId('cssmenu');

        $this->nljs = "\n";
        $found = $this->findMenu();
        $defaultItems = array(
            'system' => array(
                'label' => Yii::t('app', 'SYSTEM'),
                'icon' => Html::icon('icon-tools'),
                'visible' => (isset($found['system'])) ? count($found['system']['items']) : false
            ),
            'modules' => array(
                'label' => Yii::t('app', 'MODULES'),
                'icon' => Html::icon('icon-puzzle'),
                'visible' => (isset($found['modules'])) ? count($found['modules']['items']) : false
            ),
        );
        $items = CMap::mergeArray($defaultItems, $found);
        $this->items = $this->cssParentItems($items);
        parent::init();
    }

    protected function renderMenuRecursive($items)
    {

        foreach ($items as $k=>$item) {
            $count = (isset($item['count']) && $item['count'] > 0) ? '<span class="badge badge-success">' . $item['count'] . '</span>' : '';
            /* if ($this->totalCount) {
              $totalCount = '<span class="total_count">(' . $item['total_count'] . ')</span>';
              } else {
              $totalCount = '';
              } */
            echo Html::openTag('li', isset($item['itemOptions']) ? $item['itemOptions'] : array('class' => 'nav-item'));
            if (isset($item['url']))
                echo Html::link('<span class="">' . $item['icon'] . '</span> ' . $item['label'] . ' ' . $count, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array('class' => 'nav-link'));
            else
                echo Html::link('<span class="">' . $item['icon'] . '</span> ' . $item['label'] . ' ' . $count, "/admin/test", isset($item['linkOptions']) ? $item['linkOptions'] : array(
                    'aria-haspopup'=>'true',
                    'aria-expanded'=>'false',
                    'id'=>'dashboard-dropdown-'.$k,
                   // 'data-target'=>'#test-dropdown-'.$k,
                    'data-target'=>'#',
                    'class' => 'nav-link dropdown-toggle',
                    'data-toggle' => "dropdown"
                ));

            if (isset($item['items']) && count($item['items'])) {
               // $this->submenuHtmlOptions['aria-labelledby']='dashboard-dropdown-'.$k;
               // $this->submenuHtmlOptions['id']='test-dropdown-'.$k;

                echo "\n" . Html::openTag('ul', $this->submenuHtmlOptions) . "\n";
                $this->renderMenuRecursive($item['items']);
                echo Html::closeTag('ul') . "\n";
            }

            echo Html::closeTag('li') . "\n";
        }
    }

    protected function normalizeItems($items, $route, &$active, $ischild = 0)
    {

        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if ($this->encodeLabel)
                $items[$i]['label'] = Html::encode($item['label']);
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $route, $hasActiveChild, 1);
                if (empty($items[$i]['items']) && $this->hideEmptyItems)
                    unset($items[$i]['items']);
            }
            if (!isset($item['active'])) {
                if (($this->activateParents && $hasActiveChild) || $this->isItemActive($item, $route)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } else if ($item['active'])
                $active = true;
            if ($items[$i]['active'] && $this->activeCssClass != '' && !$ischild) {
                if (isset($item['itemOptions']['class']))
                    $items[$i]['itemOptions']['class'] .= ' ' . $this->activeCssClass;
                else
                    $items[$i]['itemOptions']['class'] = $this->activeCssClass;
            }
        }
        return array_values($items);
    }

    public function findMenu($mod = false)
    {
        $result = array();
        $modules = Yii::app()->getModules();
        foreach ($modules as $mid => $module) {
            $moduleName = ucfirst($mid);
            Yii::import("mod.{$mid}.{$moduleName}Module");
            if (isset(Yii::app()->getModule($mid)->adminMenu)) {
                $result = CMap::mergeArray($result, Yii::app()->getModule($mid)->getAdminMenu());
            }
        }

        $resultFinish = array();
        foreach ($result as $mid => $res) {
            $resultFinish[$mid] = $res;
            if (isset($res['items'])) {
                foreach ($res['items'] as $k => $item) {
                    if (isset($item['visible'])) {
                        if (!$item['visible']) {
                            unset($resultFinish[$mid]['items'][$k]);
                        }
                    }
                }
            }
        }
        return ($mod) ? $resultFinish[$mod] : $resultFinish;
    }

}
