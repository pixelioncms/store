<?php
$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $data,
    'enableCustomActions' => false,
    'selectableRows' => false,
    'enablePagination' => false,
    'autoColumns' => false,
    'enableHeader' => false,
    'selectableRows' => false,
    'columns' => array(
        array(
            'type' => 'html',
            'sortable' => false,
            'htmlOptions' => array('class' => 'image text-center'),
            'value' => '$data->renderGridImage()'
        ),
        array(
            'name' => 'name',
            'sortable' => false,
            'value' => '$data->name',
        ),
        array(
            'name' => 'views',
            'sortable' => false,
            'htmlOptions' => array('class' => 'text-center'),
            'value' => '$data->views',
        ),
        array(
            'name' => 'added_to_cart_count',
            'sortable' => false,
            'htmlOptions' => array('class' => 'text-center'),
            'value' => '$data->added_to_cart_count',
        ),
    ),
));
?>
<div class="clear"></div>
