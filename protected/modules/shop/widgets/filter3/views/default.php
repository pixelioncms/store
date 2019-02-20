

<div id="current-filters">

</div>
<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('cookie');

Yii::app()->clientScript->registerScript('filter', "
    $(document).ready(function () {
        $('.card .card-collapse').collapse({
            toggle: false
        });
        var panels = $.cookie();

        for (var panel in panels) {
            if ($('#' + panel).hasClass('card-collapse')) {
                if ($.cookie(panel) === '1') {
                    $('#' + panel).collapse('show');
                } else {
                    $('#' + panel).collapse('hide');
                }
            }
        }

        $('.card .card-collapse').on('show.bs.collapse', function () {
            var active = $(this).attr('id');
            $.cookie(active, '1');

        });

        $('.card .card-collapse').on('hide.bs.collapse', function () {
            var active = $(this).attr('id');
            $.cookie(active, null);
        });
    });
", CClientScript::POS_END);


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
echo Html::openTag('div', array('id' => 'filters'));
// Render active filters
echo Html::beginForm(array('/shop/category/view', 'seo_alias' => $this->model->full_path), 'POST', array('id' => 'filter-form'));


// Currency selected filters
echo $this->render('_current', array(), true);

// Filter by prices
if ($config->filter_enable_price)
    echo $this->render('_price', array('config' => $config, 'prices' => $prices), true);

// Filter by manufacturer

if ($config->filter_enable_brand) {
    echo $this->render('_manufacturer', array(
        'config' => $config,
        'manufacturers' => $manufacturers,
        'attributes' => $attributes
    ), true);
}
// Filters by attributes
echo $this->render('_attributes', array(
    'config' => $config,
    'attributes' => $attributes
), true);


//echo Html::submitButton('Применить',array('class'=>'btn btn-success btn-submit-filter'));
echo Html::endForm();
echo Html::closeTag('div');
Yii::app()->clientScript->registerScript('filters', "
    $(function () {
        $('#filter-form').change(function (e) {
            e.preventDefault();
            
            //find .card-body
            var block = $(e.target).parent().parent().parent().parent();
            
            var btn = $('.btn-submit-filter');
            btn.remove();
            block.append('<input class=\"btn btn-success btn-submit-filter\" type=\"submit\" value=\"Применить\" />')
            $('.btn-submit-filter').css({
                right : - $('.btn-submit-filter').width(),
                top: block.height()/2 - $('.btn-submit-filter').height() / 2
            });
            console.log(btn.height());
            block.addClass('dsadsadasdasdsasad');
            
            console.log();
            console.log( $( this ).serialize() );
            //$.ajax({
            //    type:'POST',
            //    url:window.location.href,
            //    data:$(this).serialize()
            //});
            //return false;
            //$('.btn-submit-filter')
        });
    });
", CClientScript::POS_END);

//print_r(Yii::app()->request->getPost('filter'));
?>
