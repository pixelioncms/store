
    <?php
    $this->widget('ext.adminList.GridView', array(
        'dataProvider' => $dataProvider,
        //'afterAjaxUpdate'=>"function(){registerDatePickers()}",
        //'filter'=>$model,
        'name'=>$this->pageName,
    ));
    ?>

    <?php
    Yii::app()->clientScript->registerScript("discountDatepickers", "
    $('input[name=\"ShopDiscount[start_date]\"], input[name=\"ShopDiscount[end_date]\"]').css({'position':'relative','z-index':101});
function registerDatePickers(){

    $('input[name=\"ShopDiscount[start_date]\"]').datepicker({'dateFormat':'yy-mm-dd'});
    $('input[name=\"ShopDiscount[end_date]\"]').datepicker({'dateFormat':'yy-mm-dd'});
}
registerDatePickers();
");