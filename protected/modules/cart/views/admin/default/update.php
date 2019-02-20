<?php
// Render tabs
$tabs = array(
    Yii::t('CartModule.admin', 'ORDER', 1) => $this->renderPartial('_order_tab', array(
        'model' => $model,
        'statuses' => $statuses,
        'deliveryMethods' => $deliveryMethods,
        'paymentMethods' => $paymentMethods
            ), true),
);

if (!$model->isNewRecord) {
    // Add history tab
    $tabs[Yii::t('CartModule.admin', 'ORDER_HISTORY')] = array(
        'ajax' => $this->createUrl('history', array('id' => $model->id))
    );
}
?>
<?php
if ($model->buyOneClick) {
    echo Yii::app()->tpl->alert('warning', Yii::t('CartModule.admin', 'MSG_BUYONECLICK'), false);
}
if ($model->is_deleted) {
    echo Yii::app()->tpl->alert('danger', Yii::t('CartModule.admin', 'MSG_ORDER_DELETED'), false);
}
?>
<div class="row">
    <div class="col-sm-6 col-xs-12">
        <?php
        /* Yii::app()->tpl->openWidget(array(
          'title' => $this->pageName,
          )); */

        $this->widget('app.jui.JuiTabs', array(
            'tabs' => $tabs
        ));
//Yii::app()->tpl->closeWidget();
        ?>
    </div>
    <div class="col-sm-6 col-xs-12">
        <?php if (!$model->isNewRecord) { ?>

            <div id="dialog-modal" style="display: none;" title="<?php echo Yii::t('CartModule.admin', 'CREATE_PRODUCT') ?>">
                <?php
                $this->renderPartial('_addProduct', array(
                    'model' => $model,
                ));
                ?>
            </div>

            <div id="orderedProducts">
                <?php
                if (!$model->isNewRecord) {
                    $this->renderPartial('_orderedProducts', array(
                        'model' => $model,
                    ));
                }
                ?>
            </div>
        <?php } else { ?>
            <?php Yii::app()->tpl->alert('info', Yii::t('CartModule.admin', 'ALERT_CREATE_PRODUCT'), false); ?>
        <?php } ?>
    </div>
</div>
