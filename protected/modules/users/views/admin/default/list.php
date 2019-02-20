<?php
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'filter'=>$model,
    'name'=>$this->pageName,
    'enableHeader'=>true,
    'selectableRows' => false,
));
