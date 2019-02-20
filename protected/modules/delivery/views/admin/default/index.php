<?php


$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $deliveryRecord->search(),
    'enableCustomActions' => false,
    'selectableRows' => false,
    'name'=>$this->pageName,
    'autoColumns'=>false,
    'columns' => array(
        array(
            'class'=>'EmailColumn',
            'name' => 'email',
            'type' => 'raw',
            //'value' => '$data->email'
            ),
        array('name' => 'date_create', 'type' => 'html', 'value' => 'CMS::date("$data->date_create")'),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));

?>
