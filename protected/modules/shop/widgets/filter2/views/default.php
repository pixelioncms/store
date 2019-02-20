<script>
    $(document).ready(function () {
        $('.card .card-collapse').collapse({
            toggle: false
        });
        var panels = $.cookie();

        for (var panel in panels) {
            if ($("#" + panel).hasClass('card-collapse')) {
                if ($.cookie(panel) === '1') {
                    $("#" + panel).collapse("show");
                } else {
                    $("#" + panel).collapse("hide");
                }
            }
        }

        $(".card .card-collapse").on('show.bs.collapse', function () {
            var active = $(this).attr('id');
            $.cookie(active, "1");

        });

        $(".card .card-collapse").on('hide.bs.collapse', function () {
            var active = $(this).attr('id');
            $.cookie(active, null);
        });
    });
</script>

<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('cookie');
$config = Yii::app()->settings->get('shop');
/**
 * @var $this FilterWidget
 */
/**
 * Render filters based on the next array:
 * $data[attributeName] = array(
 *        'title'=>'Filter Title',
 *        'selectMany'=>true, // Can user select many filter options
 *        'filters'=>array(array(
 *            'title'      => 'Title',
 *            'count'      => 'Products count',
 *            'queryKey'   => '$_GET param',
 *            'queryParam' => 'many',
 *        ))
 *  );
 */
// Render active filters


echo Html::openTag('div', array('id' => 'filters'));
// Currency selected filters
echo $this->render('_current', array(), true);

// Filter by prices
if (!empty($manufacturers['filters']) || !empty($attributes))
    echo $this->render('_price', array('config' => $config, 'prices' => $prices), true);

// Filter by manufacturer
//if (!empty($manufacturers->filters) && $config->filter_enable_brand)
    echo $this->render('_manufacturer', array(
        'config' => $config,
        'manufacturers' => $manufacturers,
        'attributes' => $attributes
    ), true);

// Filters by attributes
echo $this->render('_attributes', array(
    'config' => $config,
    'attributes' => $attributes
), true);


echo Html::closeTag('div');
?>
