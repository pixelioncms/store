<?php
echo CHtml::dropDownList('module', '', $tree, array(
    'empty' => '--- Выбор модуля ---',
    'class' => 'custom-select',
    'onchange' => 'ajaxTranslate("#load-section-2","ajaxGetLocale"); return false;'
));