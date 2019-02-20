

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
// Render active filters
echo Html::beginForm(array('/shop/category/view', 'seo_alias' => $this->model->full_path), 'POST', array('id' => 'filter-form'));

echo Html::openTag('div', array('id' => 'filters'));
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

echo Html::closeTag('div');
echo Html::submitButton('GO');
echo Html::endForm();

$uriTest = '';
if (Yii::app()->request->getPost('filter')) {
    foreach (Yii::app()->request->getPost('filter') as $key => $ids) {
        $uriTest .= '/' . $key . '/' . implode(',', $ids);
    }
}
//print_r(Yii::app()->request->getPost('filter'));
?>


<script>

    function filterUncheck(id, name) {
        console.log('Uncheck' + id);
        $('#filter_' + name + '_' + id).click();

    }

    $(function () {


        $("#filter-form").on("change", function (event) { //submit
            event.preventDefault();
            var form = $(this).serialize();
            var url = $(this).attr('action');
            $.fn.yiiListView.update('shop-products', {data: form});

            var serializeJSON = $(this).serializeJSON();
            var uri = '/' + categoryFullUrl;
            console.log(serializeJSON);
            var currentHtml = '';
            if (serializeJSON.filter !== undefined) {
                $.each(serializeJSON.filter, function (name, val) {
                    var label;

                    $.each(val, function (i, id) {

                        label = $('#filter_' + name + '_' + id).next('label');
                        currentHtml += '<div class="remove" onClick="filterUncheck(' + id + ', \'' + name + '\');" data-filter-id="">' + label.text() + '</div>';
                    });


                    uri += '/' + name + '/' + val.join(',');

                });
                $('#current-filters').html(currentHtml);
            }
            history.pushState({}, $('title').text(), uri);


           console.log(serializeJSON);
            $.ajax({
                url: '/shop/filter?seo_alias=' + categoryFullUrl,
                data: form,
                type: 'POST',
                success: function (data) {
                    $('#current-filters').html(data);
                }
            });

            console.log(uri);
        });

        $(".filter-list li22").on('click', function (e) {
            e.preventDefault();
            var label = $(this).find('label');
            var id = $(this).find('label').attr('for');
            console.log(label.text());


        });

        /*var filterName;
         var filterId;
         var option;
         var filter_options = [];
         var full_url = '';
         var data_option;
         var data_name;
         $("#filters .filter-list").each(function (index) {
         filter_options = [];
         filterName = $(this).attr('id').replace("filter_", "");
         filterId = $(this).attr('id');

         if ($('#' + filterId + ' li a').hasClass('active')) {
         full_url += '/' + filterName;



         $('#' + filterId + ' li a').each(function (index) {
         if ($(this).hasClass('active')) {
         data_name = $(this).attr('data-filter-name');
         //if(full_url.search( data_name)){
         //  full_url += data_name+'/';
         // }

         option = $(this).text();
         data_option = $(this).attr('data-filter-option');

         filter_options.push(data_option);
         // full_url += '/'+sss;
         //console.log( filter_options + " value: " +  option.trim());
         }
         });

         }
         full_url += '/' + filter_options.join(',');


         console.log(filter_options);
         });
         console.log(full_url);


         /*$('#filters li a').click(function () {
         var that = $(this);
         var url = that.attr('href');
         console.log(url);
         $.ajax({
         url: url,
         type: 'GET',
         beforeSend: function () {
         that.addClass('load');
         },
         success: function (data) {
         that.toggleClass('active');
         that.removeClass('load');
         window.History.pushState({url: url}, document.title, decodeURIComponent(url));
         $('#ajax-grid').html(data);
         }
         });

         return false;
         })*/
    });
</script>
