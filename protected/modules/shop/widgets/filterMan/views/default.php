<script>
    $(document).ready(function () {
$('.panel .panel-collapse').collapse({
  toggle: false
});
        var panels = $.cookie();
        
        for (var panel in panels) {
            if ($("#" + panel).hasClass('panel-collapse')) {
               if($.cookie(panel) === '1'){
                   $("#" + panel).collapse("show");
               }else{
                   $("#" + panel).collapse("hide");
               }
           }
        }

        $(".panel .panel-collapse").on('show.bs.collapse', function (){
            var active = $(this).attr('id');
            $.cookie(active, "1");
            
        });

        $(".panel .panel-collapse").on('hide.bs.collapse', function (){
            var active = $(this).attr('id');
            $.cookie(active, null);
        });
    });
</script>
<?php

$config = Yii::app()->settings->get('shop');
/**
 * @var $this FilterWidget
 */
/**
 * Render filters based on the next array:
 * $data[attributeName] = array(
 * 	    'title'=>'Filter Title',
 * 	    'selectMany'=>true, // Can user select many filter options
 * 	    'filters'=>array(array(
 * 	        'title'      => 'Title',
 * 	        'count'      => 'Products count',
 * 	        'queryKey'   => '$_GET param',
 * 	        'queryParam' => 'many',
 * 	    ))
 *  );
 */
// Render active filters



echo Html::openTag('div', array('id' => 'filters'));
echo $this->render('_currentFilter', array(), true);

//echo Html::openTag('div', array('class' => 'filter-block'));

echo $this->render('_attributesFilter', array(
    'config' => $config,
    'attributes' => $attributes
        ), true);
//if (!empty($manufacturers['filters']) || !empty($attributes))
  //  echo $this->render('_priceFilter', array('config' => $config, 'prices' => $prices), true);

echo Html::closeTag('div');
?>
