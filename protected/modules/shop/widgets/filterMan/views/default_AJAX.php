<?php
$config = Yii::app()->settings->get('shop');

echo Html::tag('div', array('id' => 'current_filter'), $this->render('_currentFilters', array(),true)
);
?>
<?php echo Html::form() ?>
<?php if ($config['filter_enable_price'] && ($this->currentMinPrice > 0 && $this->currentMaxPrice > 0)) { ?>
    <div id="filter-price" class="">
        <div class="filter-header">
    <?= Yii::t('ShopModule.default', 'FILTER_PRICE_HEADER') ?> (<?= Yii::app()->currency->active->symbol ?>)
        </div>

        <div class="filter-content">

            <div class="fluid">
                <div class="grid4">
                    от: <?php echo Html::textField('min_price', (isset($_GET['min_price'])) ? (int) $this->getCurrentMinPrice() : null ) ?>
                </div>
                <div class="grid4">
                    до: <?php echo Html::textField('max_price', (isset($_GET['max_price'])) ? (int) $this->getCurrentMaxPrice() : null ) ?>
                </div>
                <div class="clear"></div>
            </div>



    <?php
    $cm = Yii::app()->currency;
    $min = (int) floor($cm->convert($this->controller->getMinPrice()));
    $max = (int) ceil($cm->convert($this->controller->getMaxPrice()));
    echo $this->widget('zii.widgets.jui.CJuiSlider', array(
        'options' => array(
            'range' => true,
            'min' => $min,
            'max' => $max,
            'disabled' => (int) $this->controller->getMinPrice() === (int) $this->controller->getMaxPrice(),
            'values' => array($this->currentMinPrice, $this->currentMaxPrice),
            'stop' => 'js:function( event, ui ) {
                        var dataArr = $(".block-filters form").serialize();

                         dataArr+="&baseURL="+categoryFullUrl;
                        $.ajax({
                            type:"POST",
                           url:"/shop/test",
                           data:dataArr,
                           dataType:"json",
                           success:function(response){
                               console.log(response);
                               updateGrid(response.url);
                           }
                        });
                        }',
            'slide' => 'js:function(event, ui) {
				$("#min_price").val(ui.values[0]);
				$("#max_price").val(ui.values[1]);
                                


			}',
            'create' => 'js:function(event, ui){
				$("#min_price").val(' . $min . ');
				$("#max_price").val(' . $max . ');
                    }'
        ),
        'htmlOptions' => array(
        //  'style' => 'margin:5px',
        ),
            ), true);
    ?>
        </div>
    </div>
        <?php } ?>
        <?php
        if (!empty($manufacturers['filters']) || !empty($attributes))
            echo Html::openTag('div', array('id' => 'filter-brands'));

// Render manufacturers
        if (!empty($manufacturers['filters']) && $config['filter_enable_brand']) {

            echo Html::tag('div', array('class' => 'filter-header'), 'Производитель', true);
            echo Html::openTag('div', array('class' => 'filter-content'));
            echo Html::openTag('ul', array('id' => 'filter_manufacturer_list'));
            foreach ($manufacturers['filters'] as $filter) {
                $url = Yii::app()->request->addUrlParam('/shop/category/view', array($filter['queryKey'] => $filter['queryParam']), $manufacturers['selectMany']);
                $queryData = explode(',', Yii::app()->request->getQuery($filter['queryKey']));

                echo Html::openTag('li');
                // Filter link was selected.
                if (in_array($filter['queryParam'], $queryData)) {
                    echo Html::checkBox($filter['queryKey'] . '[]', true, array(
                        'value' => $filter['queryParam'],
                        'data-id' => $filter['queryKey'],
                        'data-url'=>$url,
                        'id' => $filter['queryKey'] . '_' . $filter['queryParam'],
                        'class' => 'filter'
                    ));
                } else {
                    echo Html::checkBox($filter['queryKey'] . '[]', false, array(
                        'value' => $filter['queryParam'],
                        'data-id' => $filter['queryKey'],
                           'data-url'=>$url,
                        'class' => 'filter',
                        'id' => $filter['queryKey'] . '_' . $filter['queryParam'],
                    ));
                }
                echo Html::label($filter['title'] . ' (' . $filter['count'] . ')', $filter['queryKey'] . '_' . $filter['queryParam']);
                echo Html::closeTag('li');
            }
            echo Html::closeTag('ul');
            echo Html::closeTag('div');
            //if (Yii::app()->request->getQuery($filter['queryKey'])) {
            //    echo Html::link(Yii::t('ShopModule.core', 'сбросить фильтры'), $this->getOwner()->model->viewUrl, array('class' => 'cancel_filter'));
            //}
            //echo '<div class="clear"></div>';
        }
        if ($config['filter_enable_attr']) {
            // Display attributes
            foreach ($attributes as $attrData) {
                echo Html::tag('div', array('class' => 'filter-header'), Html::encode($attrData['title']), true);

                echo Html::openTag('div', array('class' => 'filter-content'));
                echo Html::openTag('ul', array('class' => 'filter_links'));
                foreach ($attrData['filters'] as $filter) {
                    $url = Yii::app()->request->addUrlParam('/shop/category/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany']);
                    $queryData = explode(',', Yii::app()->request->getQuery($filter['queryKey']));
                    echo Html::openTag('li');
                    // Filter link was selected.


                    if (in_array($filter['queryParam'], $queryData)) {
//print_r($queryData);
//var_dump(array_search($filter['queryKey'],$queryData));
                        echo Html::checkBox('filter[' . $filter['queryKey'] . '][]', true, array(
                            'value' => $filter['queryParam'],
                            'data-id' => $filter['queryKey'],
                             'data-url'=>$url,
                            'id' => $filter['queryKey'] . '_' . $filter['queryParam'],
                        ));
                    } elseif (!$filter['count']) {
                        echo Html::checkBox('filter[' . $filter['queryKey'] . '][]', false, array(
                            'value' => $filter['queryParam'],
                            'data-id' => $filter['queryKey'],
                             'data-url'=>$url,
                            'id' => $filter['queryKey'] . '_' . $filter['queryParam'],
                            'disabled' => true
                        ));
                    } else {
                        echo Html::checkBox('filter[' . $filter['queryKey'] . '][]', false, array(
                            'value' => $filter['queryParam'],
                            'data-id' => $filter['queryKey'],
                            'data-url'=>$url,
                            'id' => $filter['queryKey'] . '_' . $filter['queryParam'],
                                //'disabled'=>true
                        ));

                        //  echo $filter['title'] . ' <small>(0)</small>';
                    }
                    echo Html::label($filter['title'] . ' (' . $filter['count'] . ')', $filter['queryKey'] . '_' . $filter['queryParam']);
                    echo Html::closeTag('li');
                }
                echo Html::closeTag('ul');
                echo Html::closeTag('div');
            }
        }
        if (!empty($manufacturers['filters']) || !empty($attributes))
            echo Html::closeTag('div');
        echo Html::endForm();