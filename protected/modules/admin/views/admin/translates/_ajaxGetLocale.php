<?php
echo CHtml::dropDownList('locale', '', $array, array(
    'empty' => '--- Выбор языка ---',
    'class' => 'custom-select',
    'onchange' => 'ajaxTranslate("#load-section-3","ajaxGetLocaleFile"); return false;'
));



