<?php
/**
 *
 * https://github.com/jackocnr/intl-tel-input
 */
class TelInputWidget extends CInputWidget {

    public $options = array();
    public $defaultOptions = array();

    public function run() {

        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id'])) {
            $id = $this->htmlOptions['id'];
            //$this->setId($id);
        }else{
            $this->htmlOptions['id'] = $id;
        }
        if(isset($this->htmlOptions['class'])){
            $this->htmlOptions['class'] = $this->htmlOptions['class'];
        }else{
            $this->htmlOptions['class'] = 'form-control';
        }
        if ($this->hasModel())
            echo Html::activeTelField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::telField($name, $this->value, $this->htmlOptions);

    }

    public function init() {


        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . "/js/data.js", CClientScript::POS_END);
        $cs->registerScriptFile($baseUrl . "/js/intlTelInput.min.js", CClientScript::POS_END);
        $cs->registerCssFile($baseUrl . "/css/intlTelInput.css");


        $this->defaultOptions = array(
            'nationalMode'=> false,
            //'separateDialCode'=>false,
           // 'localizedCountries'=>array('ua'=> 'Украина'),
            //'initialCountry'=> "auto", //Lookup user's country
            'preferredCountries'=>array('ua','ru','by'),
            /*'geoIpLookup'=> 'js:function(callback) {
                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            }',*/
            'utilsScript'=> $baseUrl."/js/utils.js"
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
      
    var isValid = $(this).intlTelInput('isValidNumber');
       console.log(intlNumber);
      console.log(isValid);
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