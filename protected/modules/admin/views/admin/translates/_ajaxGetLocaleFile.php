<?php
echo CHtml::dropDownList('file', '', $tree, array(
    'empty' => '--- Выбор файла перевода ---',
    'class' => 'custom-select',
    'onchange' => 'ajaxTranslate("#translateContainer","ajaxOpen"); return false;'
));

