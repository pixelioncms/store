<?php


$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'name'=>$this->pageName,
    'enableHeader'=>true
));

?>
