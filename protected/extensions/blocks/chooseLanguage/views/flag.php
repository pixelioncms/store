<?php

echo Html::openTag('ul', array('id' => 'choose-lang', 'class' => 'list-inline'));
foreach (Yii::app()->languageManager->getLanguages() as $lang) {

    $classLi = ($lang->code == Yii::app()->language) ? $lang->code . ' active' : $lang->code;
    $link = ($lang->is_default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();

    echo Html::openTag('li', array('class' => $classLi));
    echo Html::link(Html::image('/uploads/language/' . $lang->flag_name, $lang->name), $link, array('title' => $lang->name));
    echo Html::closeTag('li');
}
echo Html::closeTag('ul');
?>
