<?php


$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name'=>$this->pageName,
    'headerOptions'=>false,
    'enableCustomActions' => false,
    'autoColumns'=>false,
    'columns' => array(
        array('class' => 'ext.sortable.SortableColumn'),
        array(
            'name' => 'label',
            'type' => 'raw',
            'value' => 'Html::link("$data->label",Html::encode($data->url))',
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));

?>
