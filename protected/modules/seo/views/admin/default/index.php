<?php

$this->widget('ext.adminList.GridView', array(
    'id' => 'seo-grid',
    'name' => $this->pageName,
    'dataProvider' => $model->search(),
    'filter' => $model,
    'autoColumns' => false,
    'rowCssStyleExpression' => function($row, $data) {
        if (empty($data->title)) {
            return 'background-color:#ffbfc5';
        } else {
            return '';
        }
    },
    'columns' => array(
           array('class' => 'CheckBoxColumn'),
        'url',
		'h1',
        array(
            'name'=>'title',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            'name'=>'keywords',
			'value'=>'$data->getGridKeywords()',
			'type'=>'html',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            'name'=>'description',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
        ),

    ),
));

