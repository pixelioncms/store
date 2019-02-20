<?php
//Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
echo Html::form('','post',array('id'=>'edit_grid_columns_form'));
echo Html::hiddenField('grid_id', $grid_id);
echo Html::hiddenField('module', $module);
echo Html::hiddenField('model', $modelClass);
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $provider,
    'id'=>'edit_grid_columns_grid',
    
    'itemsCssClass' => 'table table-striped table-bordered',
   // 'summaryText'=>'Показано {start}-{end} ({count})',
    'selectableRows' => false,
    'headerOptions'=>false,
    'enableHeader'=>false,
    'enablePagination' => false,
    'columns' => array(
        array(
            'name' => 'checkbox',
            'type' => 'raw',
            'header' => '',
            'class' => 'IdColumn',
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'header' => 'Название поля',
            'htmlOptions' => array('class' => 'text-left'),
        ),
        array(
            'name' => 'sort',
            'type' => 'raw',
            'header' => 'Сортировка',
            'htmlOptions' => array('class' => 'text-left','style'=>'width:60px'),
        ),
    )
        )
);
echo Html::endForm();
?>
