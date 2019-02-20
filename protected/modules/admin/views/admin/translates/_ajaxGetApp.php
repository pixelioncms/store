<?php echo CHtml::dropDownList('locale', '', $tree, array(
    'empty' => Yii::t('app', 'EMPTY_LIST'),
    'class' => 'custom-select',
    'onchange' => 'ajaxTranslate("#load-section-2","ajaxGetLocaleFile"); return false;'
));


