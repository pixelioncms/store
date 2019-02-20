<?php
$this->breadcrumbs = array(
    Rights::t('default', 'MODULE_NAME') => Rights::getBaseUrl(),
    Rights::t('default', 'Generate items'),
);
?>
<?php
Yii::app()->tpl->openWidget(array(
    'title' => Rights::t('default', 'Generate items'),
));
?>



<div id="generator">

    <p><?php echo Rights::t('default', 'Please select which items you wish to generate.'); ?></p>

    <div class="form">
        <?php $form = $this->beginWidget('CActiveForm'); ?>

        <table class="items generate-item-table table table-striped">
            <tbody>
                <tr class="application-heading-row">
                    <th colspan="3"><?php echo Rights::t('default', 'Application'); ?></th>
                </tr>
                <?php
                $this->renderPartial('_generateItems', array(
                    'model' => $model,
                    'form' => $form,
                    'items' => $items,
                    'existingItems' => $existingItems,
                    'displayModuleHeadingRow' => true,
                    'basePathLength' => strlen(Yii::app()->basePath),
                ));
                ?>
            </tbody>
        </table>



        <?php
        echo Html::link(Rights::t('default', 'Select all'), '#', array(
            'onclick' => "jQuery('.generate-item-table').find(':checkbox').attr('checked', 'checked'); return false;",
            'class' => 'selectAllLink'));
        ?>
        /
        <?php
        echo Html::link(Rights::t('default', 'Select none'), '#', array(
            'onclick' => "jQuery('.generate-item-table').find(':checkbox').removeAttr('checked'); return false;",
            'class' => 'selectNoneLink'));
        ?>


        <div class="form-group">
            <?php echo Html::submitButton(Rights::t('default', 'Generate'), array('class' => 'btn btn-success')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>



<?php Yii::app()->tpl->closeWidget(); ?>