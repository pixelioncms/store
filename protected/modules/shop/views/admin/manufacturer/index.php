<?php
$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'enableHeader'=>true,
    'name'=>$this->pageName,
    'filter' => $model,
    'filterCssClass' => 'tfilter'
));

?>

