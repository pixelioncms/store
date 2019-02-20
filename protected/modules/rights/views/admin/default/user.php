<?php
$this->breadcrumbs = array(
    Rights::t('default', 'MODULE_NAME') => Rights::getBaseUrl(),
    Rights::t('default', 'Assignments') => array('admin/default/view'),
    $model->getName(),
);

$this->pageName = Rights::t('default', 'Assignments for :username', array(
            ':username' => $model->getName()
        ));
?>

     <?php Yii::app()->tpl->openWidget(array(
         'title' => $this->pageName,
         'htmlOptions' => array('class' => 'grid6')
         )); 
        $this->widget('ext.adminList.GridView', array(
            'dataProvider' => $dataProvider,
            'template' => '{items}',
            'hideHeader' => true,
            'selectableRows' => false,
            'enableHeader'=>false,
            'autoColumns'=>false,
            'enableCustomActions' => false,
            'emptyText' => Rights::t('default', 'This user has not been assigned any items.'),
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => Rights::t('default', 'Name'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'name-column'),
                    'value' => '$data->getNameText()',
                ),
                array(
                    'name' => 'type',
                    'header' => Rights::t('default', 'Type'),
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'type-column'),
                    'value' => '$data->getTypeText()',
                ),
                array(
                    'header' => '&nbsp;',
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'actions-column'),
                    'value' => '$data->getRevokeAssignmentLink()',
                ),
            )
        ));
        ?>
    <?php Yii::app()->tpl->closeWidget(); ?>

    <?php Yii::app()->tpl->openWidget(array('title' => Rights::t('default', 'Assign item'), 'htmlOptions' => array('class' => 'grid6'))); ?>


    <?php if ($formModel !== null): ?>

        <div class="form">

            <?php
            $this->renderPartial('_form', array(
                'model' => $formModel,
                'itemnameSelectOptions' => $assignSelectOptions,
            ));
            ?>

        </div>

    <?php else: ?>


        <?php Yii::app()->tpl->alert('icon', Rights::t('default', 'No assignments available to be assigned to this user.')); ?>
    <?php endif; ?>
    <?php Yii::app()->tpl->closeWidget(); ?>

<