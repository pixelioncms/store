<?php
$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));

Yii::app()->clientScript->registerScript('qustioni18n', '
	var deleteQuestion = "' . Yii::t('CartModule.admin', 'Вы действительно удалить запись?') . '";
	var productSuccessAddedToOrder = "' . Yii::t('CartModule.admin', 'Продукт успешно добавлен к заказу.') . '";
', CClientScript::POS_BEGIN);

$addonsButtonsGrid = array();
if (Yii::app()->user->openAccess(array('Cart.Default.*', 'Cart.Default.AddProduct'))) {
    $addonsButtonsGrid[] = array(
        'label' => Yii::t('CartModule.admin', 'CREATE_PRODUCT'),
        'url' => 'javascript:openAddProductDialog(' . $model->id . ');',
        'htmlOptions' => array('class' => 'btn btn-success btn-xs')
    );
}

$this->widget('ext.adminList.GridView', array(
    'id' => 'orderedProducts',
    'enableHeader' => true,
    'name' => Yii::t('CartModule.admin', 'Продукты'),
    'headerButtons' => $addonsButtonsGrid,
    'enableSorting' => false,
    'enablePagination' => false,
    'dataProvider' => $model->getOrderedProducts(),
    'selectableRows' => 0,
    'template' => '{items}',
));
?>

<script type="text/javascript">
    var orderTotalPrice = '<?php echo $model->total_price ?>';
</script>
<?php
$symbol = Yii::app()->currency->active->symbol;
?>
<div class="card card-default">
    <div class="card-body">
        <div class="card-container">
            <ul class="list-group">
                <li class="list-group-item"><?php echo Yii::t('CartModule.admin', 'FOR_PAYMENT') ?> <span class="badge pull-right"><?= Yii::app()->currency->number_format($model->full_price) ?> <?= $symbol ?></span></li>
                <?php if ($model->delivery_price > 0) { ?>
                    <li class="list-group-item"><?php echo Yii::t('CartModule.Order', 'DELIVERY_PRICE') ?> <span class="badge pull-right"><?= Yii::app()->currency->number_format($model->delivery_price); ?> <?= $symbol; ?></span></li>
                    <li class="list-group-item"><?php echo Yii::t('CartModule.admin', 'Сумма товаров') ?> <span class="badge pull-right"><?= Yii::app()->currency->number_format($model->total_price) ?> <?= $symbol ?></span></li>
                    <?php } ?>

            </ul>
        </div>
    </div>
</div>