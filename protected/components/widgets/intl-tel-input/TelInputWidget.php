<?php

/**
 *
 * https://github.com/jackocnr/intl-tel-input
 */
class TelInputWidget extends CInputWidget
{

    public $options = array();
    public $defaultOptions = array();
    public $baseUrl;

    public function run()
    {

        list($name, $id) = $this->resolveNameID();

        if (isset($this->htmlOptions['id'])) {
            $id = $this->htmlOptions['id'];
            //$this->setId($id);
        } else {
            $this->htmlOptions['id'] = $id;
        }

        if (isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] .= $this->htmlOptions['class'];
        } else {
            $this->htmlOptions['class'] = 'form-control';
        }
        if ($this->hasModel()) {
            $this->htmlOptions['id'] = CHtml::getIdByName(CHtml::activeName($this->model, $this->attribute));
            $this->setId($this->htmlOptions['id']);
            echo Html::activeTelField($this->model, $this->attribute, $this->htmlOptions);
        } else {
            echo Html::telField($name, $this->value, $this->htmlOptions);
        }
        $this->registerScript();

    }

    public function registerScript()
    {

        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
        $cs = Yii::app()->getClientScript();
        //$cs->registerScriptFile($baseUrl . "/js/data.js", CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . "/js/intlTelInput-jquery.min.js", CClientScript::POS_END);
        $cs->registerCssFile($baseUrl . "/css/intlTelInput.css");


        $this->defaultOptions = array(
            'nationalMode' => true,
            'separateDialCode' => false,
            //'placeholderNumberType'=>'MOBILE',
            // 'localizedCountries'=>array('ua'=> 'Украина'),
            //'initialCountry'=> "auto", //Lookup user's country
            'preferredCountries' => array('ua', 'ru', 'by'),
            /*'geoIpLookup'=> 'js:function(callback) {
                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            }',*/
            //'customPlaceholder'=> 'js:function(selectedCountryPlaceholder, selectedCountryData) {
            // return selectedCountryData.dialCode + "e.g. " + selectedCountryPlaceholder;
            //}',
            'utilsScript' => $baseUrl . "/js/utils.js"
        );

        $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));


        $js = "
        
    var input = $('#{$this->getid()}');
    input.intlTelInput({$options});
        
        
    input.on('countrychange', function(e, countryData) {
      console.log(countryData);
    });
        
        
    input.on('keyup change', function(e) {
        var intlNumber = input.intlTelInput('getNumber');
        var countryData = input.intlTelInput('getSelectedCountryData');
        var isValid = $(this).intlTelInput('isValidNumber');
        
        if(isValid){
            input.addClass('is-valid').removeClass('is-invalid');
            //input.val(intlNumber.replace('+',''));
            input.val(intlNumber);
        }else{
            input.addClass('is-invalid').removeClass('is-valid');
        }
             console.log(countryData.dialCode);

        if (intlNumber) {
            console.log(intlNumber);
        } else {
            console.log('Please enter a number below');
        }
    });
    ";
        $cs->registerScript(__CLASS__ . '#' . $this->getid(), $js);
    }


}