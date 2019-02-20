<?php

Yii::app()->clientScript->registerScript("product-index", "
    $(function () {
        $('.dropdown-toggle').dropdown();
        $('.datepicker').datepicker({
            // 'flat':true,
            dateFormat: 'yy-mm-dd',
            constrainInput: false,
            inline: true
        });

    });
            ", CClientScript::POS_END);

?>

<script>
    function customActions(that) {
        var action = $('.CA option:selected').val();
        customAction(action);
    }
    function customAction(name) {
        if (name == 'status-hide') {
            setProductsStatus(0)
        } else if (name == 'status-show') {
            setProductsStatus(1)
        } else if (name == 'delete') {
            alert(name);
        } else {

        }
    }

</script>


<?php

$test= strtotime(date('Y-m-d'));
echo date('Y-m-d H:i:s',$test);
echo '<br>';
echo $test;
echo '<br>';
echo '<hr>';
function get_timezone_offset($remote_tz, $origin_tz = null) {
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    return $offset;
}



$startdate = '2018-04-24 00:00:00';
$enddate = '2018-04-24 23:59:59';


$dateByZone = new DateTime($startdate);
$dateByZone->setTimezone(new DateTimeZone('UTC'));//UTC Europe/Kiev

$s = new DateTimeZone('Europe/Kiev');
var_dump(get_timezone_offset('Europe/Kiev'));
echo '<br>';
$formatted = strtotime($dateByZone->format('Y-m-d'));
$todayDateStart= date('Y-m-d H:i:s',$formatted+get_timezone_offset('Europe/Kiev'));




echo $dateByZone->format('Y-m-d H:i:s');
echo '<br>';
echo $todayDateStart;
echo '<br>';
echo '<br>';
echo '<br>';
echo '<br>';






$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search($params),
    'filter' => $model,
    'name' => $this->pageName,
    'filterCssClass' => 'tfilter',

    'rowCssClassExpression' => function($row, $data) {
        if (!$data->mainCategory) {
            return 'danger';
        }
    },

    'customActions' => array(
        array(
            'label' => Yii::t('ShopModule.admin', 'GRID_OPTION_ACTIVE'),
            'url' => 'javascript:void(0)',
            'icon'=>'icon-eye',
            'linkOptions' => array(
                'onClick' => 'return setProductsStatus(1, this);',
            ),
        ),
        array(
            'label' => Yii::t('ShopModule.admin', 'GRID_OPTION_DEACTIVE'),
            'url' => 'javascript:void(0)',
            'icon'=>'icon-eye-close',
            'linkOptions' => array(
                'onClick' => 'return setProductsStatus(0, this);',
            ),
        ),
        array(
            'label' => Yii::t('ShopModule.admin', 'GRID_OPTION_SETCATEGORY'),
            'url' => 'javascript:void(0)',
            'icon'=>'icon-folder-open',
            'linkOptions' => array(
                'onClick' => 'return showCategoryAssignWindow(this);',
            ),
        ),
        array(
            'label' => Yii::t('ShopModule.admin', 'GRID_OPTION_COPY'),
            'url' => 'javascript:void(0)',
            'icon'=>'icon-copy',
            'linkOptions' => array(
                'onClick' => 'return showDuplicateProductsWindow(this);',
            ),
        ),
        array(
            'label' => Yii::t('ShopModule.admin', 'GRID_OPTION_SETPRICE'),
            'url' => 'javascript:void(0)',
            'icon'=>'icon-currencies',
            'linkOptions' => array(
                'onClick' => 'return setProductsPrice(this);',
            ),
        )
    ),
));
?>
