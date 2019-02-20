<?php
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name' => $this->pageName,
    'headerOptions' => false,
    'autoColumns'=>false,
    'columns' => array(
          array('class' => 'ext.sortable.SortableColumn'),
        'name',
        array(
            'name' => 'module',
            'value' => 'Yii::app()->getModule($data->module)->info["name"]',
        ),
        array(
            'name' => 'parent_id',
            'value' => '($data->parent_id)?Yii::t("app","YES"):Yii::t("app","NO")',
        ),
        array(
            'class' => 'ButtonColumn',
            'header' => Yii::t('app', 'OPTIONS'),
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
?>
