<?php


$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name'=>$this->pageName,
    'enableHeader'=>true
));

