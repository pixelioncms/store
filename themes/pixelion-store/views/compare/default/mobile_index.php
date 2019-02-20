<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile($this->assetsUrl . "/js/jquery.mCustomScrollbar.concat.min.js", CClientScript::POS_END);
$cs->registerScriptFile($this->assetsUrl . "/js/jquery.mCustomScrollbar.js");
$cs->registerCssFile($this->assetsUrl . "/css/jquery.mCustomScrollbar.css");



$isType = isset($_POST['CompareForm']['type']) ? (int) $_POST['CompareForm']['type'] : 0;

?>
<script>
    $(function ($) {
        $(".scroller").mCustomScrollbar({
            setHeight: 'auto',
            setWidth: "100%",
            theme: "dark",
            axis: "x",
            scrollButtons: {enable: false},
            horizontalScroll: true,
            advanced: {autoExpandHorizontalScroll: true}

        });
        $(".scroller2").mCustomScrollbar({
            setHeight: '471px',
            setWidth: "100%",
            theme: "dark",
            axis: "y",
        });
        $('.radio-block div:first-child span').html('');
        $('#CompareForm_type input').change(function () {
            $('#compare-form').submit();
        });
    });
</script>
<?php

if(!$catId){ ?>
    <div class="col-xs-18 compare-page">
    <h1><?= $this->pageName ?></h1>
    <?=Yii::app()->tpl->alert('info',Yii::t('app','NO_INFO')); ?>
    </div>
<?php return false;}
?>
<div class="col-sm-18 compare-page">
    <h1><?= $this->pageName ?></h1>
    <div class="compare-count-products">/ <?= count($this->model->getIds()) ?> товаров</div>

    <div class="scroller22 mCustomScrollbar2">

        <?php
        $gp = array();
        if (count($this->model->products) > 0) {
            $categoryArray = array();
            $gp = array();
            ?>
            <div class="col-sm-9">
                <div class="help-block">Категория</div>


                <div class="city-list">
                    <ul>
                        <?php
                        foreach ($this->model->products as $id => $group) {
                            $categoryArray[] = $id;
                            $gp[$id] = $group;
                            $class = ($catId == $id) ? 'active' : '';
                            ?>
                            <li class="<?= $class ?>"><?= Html::link($group['name'], array('/compare', 'catId' => $id)) ?></li>
                        <?php } ?>

                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-sm-9">
             
   
             
            </div>


        <?php } ?>

    </div>
</div>
<div class="col-sm-18 compare-page">
    <?php if (count($gp) > 0) { ?>
        <div class="scroller mCustomScrollbar">
            <ul class="compare-products-list list-unstyled">


                <li style="">
                    <div style="height:520px;margin: 0 10px 20px 0;width:275px;">
                           <div class="radio-block" style="margin-top:50px">
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
                    <table class="table" style="margin-top:50px;">
                        <?php
                        if (count($this->model->attributes[$catId]['filter_name']) > 0) {
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
                                        <td class="attr"><b><?= $attribute->title ?></b></td>

                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </table>
                </li>


                <?php foreach ($gp[$catId]['items'] as $index => $p) { ?>
                    <?php
                    $this->renderPartial('_product', array(
                        'isType' => $isType,
                        'data' => $p,
                        'type' => $type,
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

