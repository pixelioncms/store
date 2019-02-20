<?php

/**
 * 
 * @package widgets.modules.shop
 * @uses CWidget
 */
class CategoriesWidget extends Widget {

    public $htmlOptions = array();
    public $totalCount = true;
    public $itemOptions = array();
    public $submenuHtmlOptions = array();

    public function init() {
        //$this->publishAssets();
    }

    public function run() {
        Yii::import('mod.shop.models.ShopCategory');

        $model = ShopCategory::model()
                ->findByPk(1);

        if (!$model) {
            throw new CHttpException(500, 'Error CategoriesWidget');
        } else {
            $result = $model->menuArray();
        }
        $this->render($this->skin, array('result' => $result));
    }

    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            $cs->registerCssFile($baseUrl . '/menu.css');
            $cs->registerScriptFile($baseUrl . '/menu.js', CClientScript::POS_END);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

    public function recursive($data, $i = 0) {
        $html = '';

        if (isset($data)) {
            $html .= Html::tag('ul');
            foreach ($data as $obj) {
                $i++;
                if (stripos($_GET['seo_alias'], $obj['url']['seo_alias']) !== false) {
                    $ariaExpanded = 'true';
                    $collapseClass = 'collapse in';
                } else {
                    $ariaExpanded = 'false';
                    $collapseClass = 'collapse';
                
                }
                $activeClass = ($obj['url']['seo_alias']=== $_GET['seo_alias']) ? 'active':'';
                
                $html .= Html::tag('li', array('class' => $activeClass));
                if (isset($obj['items'])) {
                    $html .= Html::link($obj['label'], '#collapse' . $obj['id'], array(
                                'data-toggle' => 'collapse',
                                'aria-expanded' => $ariaExpanded,
                                'aria-controls' => 'collapse' . $obj['id'],
                                'class' => 'collapsed plus-minus'
                    ));
                    $html .= Html::tag('div', array('class' => $collapseClass, 'id' => 'collapse' . $obj['id']));
                    $html .= $this->recursive($obj['items'], $i);

                    $html .= Html::closeTag('div');
                } else {

                    $html .= Html::link($obj['label'], Yii::app()->createUrl($obj['url'][0], array('seo_alias' => $obj['url']['seo_alias'])));
                }
                $html .= Html::closeTag('li');
            }
            $html .= Html::closeTag('ul');
        } else {
          //  $parent[$obj['id']] = $obj['id'];
            $html .= Html::link($data['label'], '');
        }
        return $html;
    }

}
