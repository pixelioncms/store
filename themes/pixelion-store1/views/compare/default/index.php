<?php
$cs = Yii::app()->clientScript;
//$cs->registerScriptFile($this->assetsUrl . "/js/jquery.mCustomScrollbar.concat.min.js", CClientScript::POS_END);
//$cs->registerScriptFile($this->assetsUrl . "/js/jquery.mCustomScrollbar.js");
//$cs->registerCssFile($this->assetsUrl . "/css/jquery.mCustomScrollbar.css");

//if(isset($this->model->products)){
//    foreach ($this->model->products as $id => $group) {
//        $firstCat = $id;
//        break;
 //   }
//}


//if ($firstCat!=NULL) {
//    $this->redirect(array('/compare/default/index', 'catId' => $firstCat));
//}else{
 //   $this->redirect('/');
//}
$isType = isset($_POST['CompareForm']['type']) ? (int) $_POST['CompareForm']['type'] : 0;

?>
<style>
    
.compare-products-list li{
    float:left;
    max-width: 295px;
    margin-top: 17px;
}
.compare-page .scroller2{
    margin-bottom: 24px;
}
.compare-products-list .table{
    margin-top: 100px;
}
.compare-products-list .product{
    float:none;
}
.compare-page .radio-block{
    margin-left: 0;
}
.compare-page .attr{
    color:#2b2b2b;
    font-weight: 700;
    padding:10px 0;
    border-top: 0;
}
.compare-count-products{
    color:#2b2b2b;
    font-size:20px;
    font-weight:600;
    margin: 40px 0;
}
.compare-categories-list{
    margin-bottom: 60px;
}
.compare-categories-list > li.active a{
    color:#f34235;
}
.compare-categories-list > li > a{
    color:#2b2b2b;
    font-weight:600;
    font-size:16px;
}
.compare-categories-list > li{
    margin: 5px 0;
}
.loading-address{
    background-image: url(../images/ajax.gif);
    background-position: center center;
    background-repeat: no-repeat;


}
.mCSB_scrollTools.mCSB_scrollTools_horizontal{
    top:500px;
}

    
</style>
<script>
    $(function ($) {

  $('[data-toggle="tooltip"]').tooltip();

        
        
        /*$(".scroller").mCustomScrollbar({
            setHeight: 'auto',
            setWidth: "100%",
            theme: "dark",
            axis: "x",
            scrollButtons: {enable: false},
            horizontalScroll: true,
            advanced: {autoExpandHorizontalScroll: true}

        });
        $(".scroller2").mCustomScrollbar({
            setHeight: '320px',
            setWidth: "100%",
            theme: "dark",
            axis: "y",
        });*/
        $('.radio-block div:first-child span').html('');
        $('#CompareForm_type input').change(function () {
            $('#compare-form').submit();
        });
    });
</script>

<?php

if(!$catId){ ?>
    <div class="col compare-page">
    <h1><?= $this->pageName ?></h1>
    <?=Yii::app()->tpl->alert('info',Yii::t('app','NO_INFO')); ?>
    </div>
<?php return false;}
?>
<div class="col-sm-5 compare-page">
    <h1><?= $this->pageName ?></h1>
    <div class="compare-count-products">/ <?= count($this->model->getIds()) ?> товаров</div>

    <div class="scroller2 mCustomScrollbar">

        <?php

        $gp = array();
        if (count($this->model->products) > 0) {
            $categoryArray = array();
            $gp = array();
            ?>
            <div class="help-block">Категория</div>
            <ul class="list-unstyled compare-categories-list text-uppercase">
                <?php
                foreach ($this->model->products as $id => $group) {
                    $categoryArray[] = $id;
                    $gp[$id] = $group;
                    $class = ($catId == $id) ? 'active' : '';
                    ?>
                    <li class="<?= $class ?>"><?= Html::link($group['name'], array('/compare', 'catId' => $id)) ?></li>
                <?php } ?>
            </ul>

            <div class="radio-block">
                <?php
                        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'compare-form',
            'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
            'enableClientValidation' => false,
            'htmlOptions' => array('name' => 'compare-form', 'class' => '')
        ));
                echo $form->radioButtonList($compareForm, 'type', array(0 => 'Все параметры', 1 => 'Только отличия'), array(
                    'template' => '<div>{input}{label}<span>(параметры в которых есть отличия)</span></div>',
                ));
                ?>          
                <?php $this->endWidget(); ?>       
            </div>


        </div>
    <?php } ?>
    <table class="table">
        <?php

 if(isset($this->model->attributes[$catId]['filter_name'])){
            foreach ($this->model->attributes[$catId]['filter_name'] as $attribute) {
                if ($isType) {
                    $unq = array();

                    foreach ($gp[$catId]['items'] as $product) {

                        $unq[] = (string) $product->{'eav_' . $attribute->name};
                    }

                    foreach (array_count_values($unq) as $pid => $count) {
                        $flag = true;

                        if ($count == count($gp[$catId]['items'])) {
                            $flag = false;
                        }
                    }
                } else {
                    $flag = true;
                }
                if ($flag) {//$flag
          
                    ?>
                    <tr>
                        <td class="attr"><b data-toggle="tooltip" data-placement="top" title="<?= $attribute->title ?>"><?= CMS::truncate($attribute->title, 30) ?></b></td>

                    </tr>
                    <?php 
                }
            }
        }
       
        ?>
    </table>

</div>
<div class="col-sm-13 compare-page">
    <?php if (count($gp) > 0) { ?>
        <div class="scroller mCustomScrollbar">
            <ul class="compare-products-list list-unstyled">
                <?php foreach ($gp[$catId]['items'] as $index => $p) { ?>
                    <?php
                    $this->renderPartial('_product', array(
                        'isType' => $isType,
                        'data' => $p,
                        //'type' => $type,
                        'gp' => $gp,
                        'catId' => $catId,
                        'attrs' => $this->model->attributes
                    ));
                    ?>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>

