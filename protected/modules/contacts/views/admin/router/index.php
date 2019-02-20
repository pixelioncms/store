<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));

if ($mapsCount > 0) {
    $this->widget('ext.adminList.GridView', array(
        'dataProvider' => $model->search(),
        'autoColumns' => false,
        'enableHeader' => false,
        'filter' => $model,
        'columns' => array(
            array(
              'name' => 'name',
              'type' => 'raw',
              'value' => 'Html::encode($data->name)',
              ), 

            array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
        ),
    ));
} else {
    Yii::app()->tpl->alert('danger', Yii::t('ContactsModule.default', 'NO_MAPS'), false);
}
Yii::app()->tpl->closeWidget();
?>



