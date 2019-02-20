<?php
Yii::app()->tpl->alert('info', Rights::t('default', 'A role is group of permissions to perform a variety of tasks and operations, for example the authenticated user.'));
Yii::app()->tpl->alert('info', Rights::t('default', 'Roles exist at the top of the authorization hierarchy and can therefore inherit from other roles, tasks and/or operations.'));

Yii::app()->tpl->openWidget(array('title' => $this->pageName));

Yii::app()->tpl->alert('info', Rights::t('default', 'Values within square brackets tell how many children each item has.'), false);
?>
<div id="roles">



    <?php
    $this->widget('ext.adminList.GridView', array(//zii.widgets.grid.CGridView
        'dataProvider' => $dataProvider,
        'template' => '{items}',
        'enableHeader' => false,
        'selectableRows'=>false,
        'autoColumns' => false,
        'htmlOptions' => array('class' => 'grid-view role-table'),
        'id' => 'rights-grid',
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
                'value' => '$data->getDeleteRoleLink()',
            ),
        )
    ));
    ?>



</div>
<?php
Yii::app()->tpl->closeWidget();
?>
