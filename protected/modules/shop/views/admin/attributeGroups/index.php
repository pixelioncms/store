<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'enableHeader'=>true,
    'selectableRows'=>false,
    'name'=>$this->pageName,
));

?>