<?php

Yii::app()->tpl->openWidget(array('title' => $this->pageName));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    //'filter'=>$model,
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Html::image("/uploads/language/$data->flag_name")." ".Html::link(Html::encode($data->name), array("update", "id"=>$data->id))',
        ),
        array(
            'name' => 'is_default',
            'htmlOptions' => array('class' => 'text-center'),
            'type' => 'text',
            'filter' => array(
                '1' => Yii::t('app', 'YES'),
                '0' => Yii::t('app', 'NO')
            ),
            'value' => strtr('$data->is_default ? "{yes}":"{no}"', array(
                '{yes}' => Yii::t('app', 'YES'),
                '{no}' => Yii::t('app', 'NO')
            )),
        ),
        array(
            'name' => 'code',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => '$data->code',
        ),
        array(
            'name' => 'locale',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'text-center'),
            'value' => '$data->locale',
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
            'hidden' => array(
                'delete' => array(1),
            )
        ),
    ),
));
Yii::app()->tpl->closeWidget();
?>