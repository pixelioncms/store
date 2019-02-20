<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName
));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'enableHeader'=>false,
    'autoColumns'=>false,
    'columns' => array(
        array(
            'class' => 'CheckBoxColumn',
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/suppliers/update", "id"=>$data->id))',
        ),
        'phone',
        'email',
        'address',
        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
));
Yii::app()->tpl->closeWidget();
?>
