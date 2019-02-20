<?php
Yii::app()->tpl->alert('info', Rights::t('default', 'Here you can view and manage the permissions assigned to each role.'));
Yii::app()->tpl->alert('info', Rights::t('default', 'Authorization items can be managed under {roleLink}, {taskLink} and {operationLink}.', array(
            '{roleLink}' => Html::link(Rights::t('default', 'Roles'), array('/admin/rights/authItem/roles')),
            '{taskLink}' => Html::link(Rights::t('default', 'Tasks'), array('/admin/rights/authItem/tasks')),
            '{operationLink}' => Html::link(Rights::t('default', 'Operations'), array('/admin/rights/authItem/operations')),
        )));
?>

<div id="rights">
    <div id="permissions">
        <?php
        $this->renderPartial('_permissions', array(
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'name' => $this->pageName
        ));
        ?>
    </div>
</div>