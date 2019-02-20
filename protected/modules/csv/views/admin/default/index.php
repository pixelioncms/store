<?php

/*Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs' => array(
        Yii::t('CsvModule.admin', 'IMPORT') => array('ajax' => $this->createUrl('/admin/csv/default/import'), 'id' => 'import'),
        Yii::t('CsvModule.admin', 'EXPORT') => array('ajax' => $this->createUrl('/admin/csv/default/export'), 'id' => 'export'),
    ),
    'options' => array(
        'collapsible' => true,
        'beforeLoad' => 'js:function (e, ui) {
            common.addLoader();
        }',
        'load' => 'js:function(e, ui) {
            common.removeLoader();
        }',
    ),
));
Yii::app()->tpl->closeWidget();*/
?>
<div class="text-center">
<?=Html::link(Yii::t('CsvModule.admin', 'IMPORT'),$this->createUrl('/admin/csv/default/import'),array('class'=>'btn btn-info'));?>

<?=Html::link(Yii::t('CsvModule.admin', 'EXPORT'),$this->createUrl('/admin/csv/default/export'),array('class'=>'btn btn-info'));?>
</div>