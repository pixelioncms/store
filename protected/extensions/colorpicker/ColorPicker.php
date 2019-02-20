<?php

class ColorPicker extends CInputWidget {
    //***************************************************************************
    // Properties
    //***************************************************************************

    /**
     * ColorPicker mode. Possible values are:
     *
     * textfield - presents a textfield with a color picker attached (default)
     * flat - presents a color picker in flat mode
     * selector - attached to a square selector
     *
     * @var string
     */
    private $mode = 'textfield';

    /**
     * The default color. String for hex color
     *
     * @var <type>
     */
    public $value = '000000';

    /**
     * Whatever if the color values are filled in the fields while changing
     * values on selector or a field. If false it may improve speed. Default true
     *
     * @var boolean
     */
    private $livePreview = true;

    /**
     * Whetever the color picker will be animated
     *
     * @var boolean
     */
    private $fade = false;

    /**
     * Whetever the color picker will slide
     *
     * @var boolean
     */
    private $slide = false;

    /**
     * Whetever the color picker will appear as a curtain
     *
     * @var boolean
     */
    private $curtain = false;

    /**
     * Times for the effect delays
     *
     * @var integer
     */
    private $timeFade = 500;
    private $timeSlide = 500;
    private $timeCurtain = 500;

    /**
     * Callback function triggered when the color picker is shown
     *
     * @var string
     */
    public $onShow = '';

    /**
     * Callback function triggered before the color picker is shown
     *
     * @var string
     */
    public $onBeforeShow = '';

    /**
     * Callback function triggered when the color picker is hidden
     *
     * @var string
     */
    public $onHide = '';

    /**
     * Callback function triggered when the color is changed
     *
     * @var string
     */
    public $onChange = '';

    /**
     * Callback function triggered when the color it is chosen
     *
     * @var string
     */
    public $onSubmit = '';

    /**
     * ID of the element which will be the selector in "selector" mode. Ideally
     * this should be a div or another widget with a more complex design
     * 
     * @var string
     */
    public $selector = '';

    //***************************************************************************
    // Setters and getters
    //***************************************************************************

    /**
     * Check valid modes
     * 
     * @param string $value 
     */
    public function setMode($value) {
        if (!in_array($value, array('textfield', 'flat', 'selector')))
            throw new CException(Yii::t('ColorPicker', 'Invalid mode.'));
        $this->mode = $value;
    }

    /**
     *
     * @return string
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * Hexadecimal value for the starting color.
     *
     * @param string $value
     */
    public function setValue($value) {
        if (!preg_match('/^[0-9A-F]{6}$/i', $value))
            throw new CException(Yii::t('ColorPicker', 'Invalid color.'));
        $this->value = $value;
    }

    /**
     *
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Boolean value for livePreview
     *
     * @param boolean $value
     */
    public function setLivePreview($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('ColorPicker', 'Invalid value.'));
        $this->livePreview = $value;
    }

    /**
     *
     * @return boolean
     */
    public function getLivePreview() {
        return $this->livePreview;
    }

    public function setFade($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('ColorPicker', 'Invalid value.'));
        $this->fade = $value;
    }

    public function getFade() {
        return $this->fade;
    }

    public function setSlide($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('ColorPicker', 'Invalid value.'));
        $this->slide = $value;
    }

    public function getSlide() {
        return $this->slide;
    }

    public function setCurtain($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('ColorPicker', 'Invalid value.'));
        $this->curtain = $value;
    }

    public function getCurtain() {
        return $this->curtain;
    }

    public function setTimeFade($value) {
        if (!is_int($value))
            throw new CException(Yii::t('ColorPicker', 'Invalid value.'));
        $this->timeFade = $value;
    }

    public function getTimeFade() {
        return $this->timeFade;
    }

    public function setTimeSlide($value) {
        if (!is_int($value))
            throw new CException(Yii::t('ColorPicker', 'Invalid value.'));
        $this->timeSlide = $value;
    }

    public function getTimeSlide() {
        return $this->timeSlide;
    }

    public function setTimeCurtain($value) {
        if (!is_int($value))
            throw new CException(Yii::t('ColorPicker', 'Invalid value.'));
        $this->timeCurtain = $value;
    }

    public function getTimeCurtain() {
        return $this->timeCurtain;
    }

    //***************************************************************************
    // Utilities
    //***************************************************************************

    private function jsOptions() {
        $options = array();
        $options['color'] = "'" . $this->value . "'";
        $options['livePreview'] = "'" . $this->livePreview . "'";
        $options['onShow'] = $this->onShow;
        $options['onBeforeShow'] = $this->onBeforeShow;
        $options['onHide'] = $this->onHide;
        $options['onSubmit'] = $this->onSubmit;

        switch ($this->mode) {
            case 'textfield':
                $options['flat'] = 'false';
                break;

            case 'flat':
                $options['flat'] = 'true';
                break;

            case 'selector':
                $options['flat'] = 'false';
                break;
        }
        return CJavaScript::encode($options);
    }

    //***************************************************************************
    // Paint the widget
    //***************************************************************************

    public function run() {
        list($name, $id) = $this->resolveNameID();

        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl . '/js/colorpicker.js',CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . '/js/eye.js',CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . '/js/utils.js',CClientScript::POS_END);
        $cs->registerCssFile($baseUrl . '/css/colorpicker.css');

        $options = $this->jsOptions();

        $js_effects = '';
        if (($this->mode != 'flat') && ($this->fade || $this->slide || $this->curtain)) {
            $fi = $fo = $si = $so = $ci = $co = '';
            if ($this->fade) {
                $fi = "$(colpkr).fadeIn({$this->timeFade});";
                $fo = "$(colpkr).fadeOut({$this->timeFade});";
            }
            if ($this->slide) {
                $si = "$(colpkr).slideDown({$this->timeSlide});";
                $so = "$(colpkr).slideUp({$this->timeSlide});";
            }
            if ($this->curtain) {
                $ci = "$(colpkr).show({$this->timeCurtain});";
                $co = "$(colpkr).hide({$this->timeCurtain});";
            }
            $js_effects = <<<EOP
onShow: function (colpkr) {
   {$fi}
   {$si}
   {$ci}
   return false;
},
onHide: function (colpkr) {
   {$fo}
   {$so}
   {$co}
   return false;
},
EOP;
        }

        switch ($this->mode) {
            case 'textfield':
                $this->htmlOptions['id'] = $id;
                if(!isset($this->htmlOptions['class'])){
                    $this->htmlOptions['class'] = 'form-control';
                }
                
                $this->htmlOptions['size'] = !isset($this->htmlOptions['size']) ? 7 : $this->htmlOptions['size'];
                $this->htmlOptions['maxlength'] = !isset($this->htmlOptions['maxlength']) ? 7 : $this->htmlOptions['maxlength'];
                if ($this->hasModel())
                    $html = Html::activeTextField($this->model, $this->attribute, $this->htmlOptions);
                else
                    $html = Html::textField($name, $this->value, $this->htmlOptions);
                $js = <<<EOP
$('#{$this->htmlOptions['id']}').ColorPicker({
   {$js_effects}
	onSubmit: function(hsb, hex, rgb) {
		$('#{$this->htmlOptions['id']}').val('#'+hex);
	},
	onBeforeShow: function() {
		$(this).ColorPickerSetColor(this.value);
	},
   onChange: function(hsb, hex, rgb) {
      $('#{$this->htmlOptions['id']}').val('#'+hex);
   }
})
.bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
});
EOP;
                $cs->registerScript(get_class($this) . '_' . $id, $js,CClientScript::POS_END);
                echo $html;
                break;

            case 'flat':
                $html = <<<EOP
<div id="{$id}"></div>
EOP;
                $js = <<<EOP
$('#{$id}').ColorPicker({$options});
EOP;
                $cs->registerScript(get_class($this) . '_' . $id, $js,CClientScript::POS_END);
                echo $html;
                break;

            case 'selector':
                if (empty($this->selector)) {
                    throw new CException(Yii::t('ColorPicker', 'A selector must be specified.'));
                } else {
                    $selector = $this->selector;
                }
                $js = <<<EOP
$('#{$selector}').ColorPicker({
   {$js_effects}
	onChange: function (hsb, hex, rgb) {
		$('#{$selector}').css('backgroundColor', '#' + hex);
	}
});
EOP;
                $cs->registerScript(get_class($this) . '_' . $id, $js,CClientScript::POS_END);
                echo '';
                break;
        }
    }

}