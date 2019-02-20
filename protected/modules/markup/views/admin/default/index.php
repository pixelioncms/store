
    <?php
    $this->widget('ext.adminList.GridView', array(
        'dataProvider' => $dataProvider,
        //'afterAjaxUpdate'=>"function(){registerDatePickers()}",
        //'filter'=>$model,
        'name'=>$this->pageName,
    ));
    ?>

    <?php
    Yii::app()->clientScript->registerScript("markupDatepickers", "
    $('input[name=\"ShopMarkup[start_date]\"], input[name=\"ShopMarkup[end_date]\"]').css({'position':'relative','z-index':101});
function registerDatePickers(){

    $('input[name=\"ShopMarkup[start_date]\"]').datepicker({'dateFormat':'yy-mm-dd'});
    $('input[name=\"ShopMarkup[end_date]\"]').datepicker({'dateFormat':'yy-mm-dd'});
}
registerDatePickers();
");