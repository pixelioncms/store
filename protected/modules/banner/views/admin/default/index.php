<?php

$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name'=>$this->pageName,
    'enableHeader'=>true
));

