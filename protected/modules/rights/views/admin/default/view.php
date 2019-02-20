<?php

$this->breadcrumbs = array(
    Rights::t('default', 'MODULE_NAME') => Rights::getBaseUrl(),
    Rights::t('default', 'Assignments'),
);

$this->pageName = 'Привязки';


$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'autoColumns' => false,
    //'enableHeader'=>false,
    //'template'=>"{items}{summary}{pager}",
    'selectableRows' => false,
    'emptyText' => Rights::t('default', 'No users found.'),
    'htmlOptions' => array('class' => 'grid-view'),
    'name' => $this->pageName,
    'columns' => array(
        array(
            'name' => 'name',
            'header' => Rights::t('default', 'Name'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'name-column'),
            'value' => '$data->getAssignmentNameLink()',
        ),
        array(
            'name' => 'assignments',
            'header' => Rights::t('default', 'Roles'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'role-column'),
            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_ROLE)',
        ),
        array(
            'name' => 'assignments',
            'header' => Rights::t('default', 'Tasks'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'task-column'),
            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_TASK)',
        ),
        array(
            'name' => 'assignments',
            'header' => Rights::t('default', 'Operations'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'operation-column'),
            'value' => '$data->getAssignmentsText(CAuthItem::TYPE_OPERATION)',
        ),
    )
));
?>
