<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'name' => $this->pageName,
    'enableHeader' => true,
    /*'rowCssStyleExpression' => function($row, $data) {
        if (!empty($data->status_color)) {
            return 'background-color:' . $data->status_color . '';
        } else {
            return '';
        }
    },*/


    'rowHtmlOptionsExpression' => 'array("style" => "background-color:$data->status_color")',
));

