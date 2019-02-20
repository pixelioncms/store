<?php

/**
 * HighchartsWidget class file.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @version 4.2.6
 * 
 */

class HighchartsWidget extends CWidget {

    protected $_constr = 'chart';
    protected $_baseScript = 'highcharts';
    protected $_baseScript3D = 'highcharts-3d';
    public $options = array();
    public $htmlOptions = array();
    public $setupOptions = array();
    public $scripts = array();
    public $callback = false;

    /**
     * Renders the widget.
     */
    public function run() {
        if (isset($this->htmlOptions['id'])) {
            $this->id = $this->htmlOptions['id'];
        } else {
            $this->htmlOptions['id'] = $this->getId();
        }

        echo CHtml::openTag('div', $this->htmlOptions);
        echo CHtml::closeTag('div');

        // check if options parameter is a json string
        if (is_string($this->options)) {
            if (!$this->options = CJSON::decode($this->options)) {
                throw new CException('The options parameter is not valid JSON.');
            }
        }

        // merge options with default values
        $defaultOptions = array(
            'credits' => array(
                'enabled' => true,
                'text' => 'PIXELION CMS',
                'href' => 'http://pixelion.com.ua',
            ),
            'chart' => array('renderTo' => $this->id)
        );
        $this->options = CMap::mergeArray($defaultOptions, $this->options);
        array_unshift($this->scripts, $this->_baseScript);

        $this->registerAssets();
    }

    /**
     * Publishes and registers the necessary script files.
     */
    protected function registerAssets() {
        $basePath = dirname(__FILE__) . DS . 'assets' . DS;
        $baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);

        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');

        // register additional scripts
        $extension = YII_DEBUG ? '.src.js' : '.js';
        foreach ($this->scripts as $script) {
            $cs->registerScriptFile("{$baseUrl}/{$script}{$extension}",CClientScript::POS_END);
        }

        // highcharts and highstock can't live on the same page
        if ($this->_baseScript === 'highstock') {
            $cs->scriptMap["highcharts{$extension}"] = "{$baseUrl}/highstock{$extension}";
        }

        // prepare and register JavaScript code block
        $jsOptions = CJavaScript::encode($this->options);
        $setupOptions = CJavaScript::encode($this->setupOptions);
        $js = "Highcharts.setOptions($setupOptions); var chart = new Highcharts.{$this->_constr}($jsOptions);";
        $key = __CLASS__ . '#' . $this->id;
        if (is_string($this->callback)) {
            $callbackScript = "function {$this->callback}(data) {{$js}}";
            $cs->registerScript($key, $callbackScript, CClientScript::POS_END);
        } else {
            $cs->registerScript($key, $js, CClientScript::POS_LOAD);
        }
         
            //$cs->registerScript($key.'resize', '$("#menu-toggle").click(function (e) {
            //     chart.reflow();
           //});', CClientScript::POS_LOAD);
    }

}
