<?php
$this->beginClip('help');
Yii::app()->tpl->alert('info', Rights::t('default', 'A task is a permission to perform multiple operations, for example accessing a group of controller action.') . '<br/>' . Rights::t('default', 'Tasks exist below roles in the authorization hierarchy and can therefore only inherit from other tasks and/or operations.') . '<br/>' . Rights::t('default', 'Values within square brackets tell how many children each item has.'), false);

$this->endClip();
?>
<?= $this->clips['help']; ?>


<div id="tasks">
    <?php
    $this->widget('ext.adminList.GridView', array(//zii.widgets.grid.CGridView
        'dataProvider' => $dataProvider,
        'template' => '{items}',
        'name' => $this->pageName,
        'autoColumns' => false,
        'emptyText' => Rights::t('default', 'No tasks found.'),
        'htmlOptions' => array('class' => 'grid-view task-table'),
        'columns' => array(
            array(
                'name' => 'name',
                'header' => Rights::t('default', 'Name'),
                'type' => 'raw',
                'htmlOptions' => array('class' => 'name-column'),
                'value' => '$data->getGridNameLink()',
            ),
            array(
                'name' => 'description',
                'header' => Rights::t('default', 'Description'),
                'type' => 'raw',
                'htmlOptions' => array('class' => 'description-column'),
            ),
            array(
                'name' => 'bizRule',
                'header' => Rights::t('default', 'Business rule'),
                'type' => 'raw',
                'htmlOptions' => array('class' => 'bizrule-column'),
                'visible' => Rights::module()->enableBizRule === true,
            ),
            array(
                'name' => 'data',
                'header' => Rights::t('default', 'Data'),
                'type' => 'raw',
                'htmlOptions' => array('class' => 'data-column'),
                'visible' => Rights::module()->enableBizRuleData === true,
            ),
            array(
                'header' => '&nbsp;',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'actions-column text-center'),
                'value' => '$data->getDeleteTaskLink()',
            ),
        )
    ));
    ?>

</div>
