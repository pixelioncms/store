<?php
if ($config->filter_enable_attr) {

    foreach ($attributes as $attrData) {

        if (count($attrData['filters']) > 0) {

            ?>

            <div class="card filter-block">
                <div class="card-header collapsed" data-toggle="collapse"
                     data-target="#filter<?= md5($attrData['title']); ?>">
                    <h5><?= Html::encode($attrData['title']) ?></h5>
                </div>
                <div class="card-collapse collapse" id="filter<?= md5($attrData['title']); ?>">
                    <div class="card-body overflow">

                        <?php

                        if ($attrData['slider']) {


                            echo $this->widget('zii.widgets.jui.CJuiSlider', array(
                                'options' => array(
                                    'range' => true,
                                    'min' => 5,
                                    'cssFile' => false,
                                    'max' => 45,
                                    //'disabled' => (int) $getMin === (int) $getMax,
                                    'values' => array(10, 38),
                                    'slide' => 'js:function(event, ui) {

			                            }',
                                    'stop' => 'js:function(event, ui) {
                                            console.log(ui.values[0]);
                                            console.log(ui.values[1], "slider");
			                            }',
                                    'create' => 'js:function(event, ui){
		                                }'
                                ),
                                'htmlOptions' => array('class' => 'price-slider'),
                            ), true);
                        } else { ?>
                            <ul class="filter-list" id="filter_<?=$attrData['queryKey']?>">
                                <?php
                                foreach ($attrData['filters'] as $filter) {


                                    if ($filter['count'] > 0) {
                                        $url = Yii::app()->request->addUrlParam('/shop/category/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany']);
                                    } else {
                                        $url = 'javascript:void(0)';
                                    }

                                    $queryData = explode(',', Yii::app()->request->getQuery($filter['queryKey']));

                                    echo Html::openTag('li',array('class'=>(!empty($filter['spec']))?'color':'','style'=>$filter['spec']));
                                    // Filter link was selected.
                                    if (in_array($filter['queryParam'], $queryData)) {
                                        // Create link to clear current filter
                                        $checked=true;
                                        $url = Yii::app()->request->removeUrlParam('/shop/category/view', $filter['queryKey'], $filter['queryParam']);
                                       // echo Html::link($filter['title'] . $filter['abbreviation'], $url, array('class' => 'active', 'rel' => 'nofollow','data-filter-option'=>$filter['queryParam'],'data-filter-name'=>$filter['queryKey']));
                                    } else {
                                        $checked=false;
                                        //echo Html::link($filter['title'] . $filter['abbreviation'], $url, array('rel' => 'nofollow','data-filter-option'=>$filter['queryParam'],'data-filter-name'=>$filter['queryKey']));
                                    }
                                    echo Html::checkBox('filter['.$filter['queryKey'].'][]',$checked,array('value'=>$filter['queryParam'],'id'=>'filter_'.$filter['queryKey'].'_'.$filter['queryParam']));
                                    echo Html::label($filter['title'],'filter_'.$filter['queryKey'].'_'.$filter['queryParam']);


if(empty($filter['spec'])){
                                    echo $this->getCount($filter,$checked);
}
                                    echo Html::closeTag('li');
                                } ?>
                            </ul>
                        <?php }

                        ?>
                        </ul>
                    </div>
                </div>
            </div>

            <?php
        }
    }
}
?>
