<?php
$this->breadcrumbs = array(
    Rights::t('default', 'MODULE_NAME') => Rights::getBaseUrl(),
    Rights::t('default', 'Create :type', array(':type' => Rights::getAuthItemTypeName($_GET['type']))),
);

$this->pageName = Rights::t('default', 'Create :type', array(
            ':type' => Rights::getAuthItemTypeName($_GET['type']),
        ));
?>
<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
?>

<div class="createAuthItem">
    <?php $this->renderPartial('_form', array('model' => $formModel)); ?>
</div>

<?php Yii::app()->tpl->closeWidget(); ?>