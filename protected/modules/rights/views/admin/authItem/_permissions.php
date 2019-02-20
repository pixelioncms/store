<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'template' => '{items}',
   // 'enableHeader' => false,
    'name'=>$name,
    'selectableRows'=>false,
    'autoColumns' => false,
    'emptyText' => Rights::t('default', 'No authorization items found.'),
    'htmlOptions' => array('class' => 'grid-view permission-table'),
    'columns' => $columns,
));
?>
