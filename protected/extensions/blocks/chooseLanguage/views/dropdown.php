<script>
    function changeLanguage(that, url) {
        var selected = $('option:selected', that).val();
        if (selected == undefined) {
            window.location.pathname = url;
        } else {
            window.location.pathname = selected + '' + url;
        }
    }
</script>
<?php
/*Yii::app()->controller->widget('ext.bootstrap.selectinput.SelectInput', array(
    'name' => 'language',
    'value' => Yii::app()->languageManager->getUrlPrefix(),
    'data' => Yii::app()->languageManager->getLangs(),
    'htmlOptions' => array(
        'data-width' => 'auto',
        'data-style'=>'btn-sm btn-default',
        'onChange' => 'changeLanguage(this, "' . CMS::currentUrl() . '")'
    )
));*/

echo Html::dropDownList('language',
    Yii::app()->languageManager->getUrlPrefix(),
    Yii::app()->languageManager->getLangs(), array(

        'class'=>'form-control custom-select',
        'onChange' => 'changeLanguage(this, "' . CMS::currentUrl() . '")'
    )
);
?>
