

<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'name' => $this->pageName,
    'headerOptions' => false,
    'autoColumns' => false,
        'rowCssStyleExpression' => function($row, $data) {
        if (!empty($data->color)) {
            return 'background-color:' . $data->color . '';
        } else {
            return '';
        }
    },
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => '(!in_array($data->id,$data->disallow_update)) ? Html::link(Html::encode($data->name), array("/admin/cart/statuses/update", "id"=>$data->id)):"$data->name"',
        ),
        'color',
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
        /* 'hidden'=>array(
          'delete'=>array(1,2,3,4),
          'update'=>array(1,2,3,4),
          ) */
        ),
    ),
));
?>
