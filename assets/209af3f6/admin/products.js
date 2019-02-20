
// Process checked categories

$("#ShopProduct").submit(function () {
    var checked = $("#ShopCategoryTree li a.jstree-checked");
    checked.each(function (i, el) {
        var id = $(el).attr("id").replace('node_', '').replace('_anchor', '');
        $("#ShopProduct").append('<input type="hidden" name="categories[]" value="' + id + '" />');
    });

});
//$('#ShopCategoryTree').delegate("a", "click", function (event) {
//	$('#ShopCategoryTree').jstree('checkbox').check_node($(this));
//	var id = $(this).parent("li").attr('id').replace('ShopCategoryTreeNode_', '');
//});

;
(function ($) {
    $.fn.checkNode = function (id) {

        $(this).bind('loaded.jstree', function () {
            $(this).jstree('check_node', 'node_' + id);
        });
    };
})(jQuery);

var price_id = Math.random();

$('.remove-price').click(function (e) {
   // e.preventDefault();
    var id = $(this).data('price-id');
    $('#price-row-'+$(this).data('price-id')).remove();
    return false;
});

$('#ShopProduct_unit').change(function(){
    $('.unit-name').text($(this).find(":selected").text());
});

$('#ShopProduct_currency_id').change(function(){
    $('.currency-name').text($(this).find(":selected").text());
});


$('#add-price').click(function (e) {
    e.preventDefault();
    var rand = parseInt(Math.random()*100000);
    var selector = $("#extra-prices");
    console.log('add price');



    selector.prepend('<div id="price-row-'+rand+'"><hr/><div class="row">' +
        '<label class="col-sm-3 col-md-3 col-lg-2 col-form-label required" for="ShopProductPrices_'+rand+'_value">Цена <span class="required">*</span></label>' +
        '<div class="col-sm-9 col-md-3 col-lg-3">' +
        '<div class="input-group mb-2">' +
        '<input class="form-control flashing-input" type="text" value="0.00" name="ShopProductPrices['+rand+'][value]" id="ShopProductPrices_'+rand+'_value">' +
        '<div class="input-group-append"><span class="col-form-label ml-3"><span class="currency-name">грн.</span> за <span class="unit-name">шт.</span> <a href="#" onClick="$(\'#price-row-'+rand+'\').remove(); return false;" data-price-id="'+rand+'" class="remove-price btn btn-sm btn-danger"><i class="icon-delete"></i></a></span></div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="row">' +
        '<label class="col-sm-3 col-md-3 col-lg-2 col-form-label required" for="ShopProductPrices_'+rand+'_order_from">При заказе от <span class="required">*</span></label>' +
        '<div class="col-sm-9 col-md-3 col-lg-3">' +
        '<div class="input-group mb-3 mb-sm-0">' +
        '<input class="form-control flashing-input" type="text" value="2" name="ShopProductPrices['+rand+'][order_from]" id="ShopProductPrices_'+rand+'_order_from">' +
        '<div class="input-group-append"><span class="col-form-label ml-3 unit-name">шт.</span></div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>');


});

// On change `use configurations` select - load available attributes
$('#ShopProduct_use_configurations, #ShopProduct_type_id').change(function () {
    var attrs_block = $('#availableAttributes');
    var type_id = $('#ShopProduct_type_id').val();
    attrs_block.html('');

    if ($('#ShopProduct_use_configurations').val() == '0')
        return;

    $.getJSON('/admin/shop/products/loadConfigurableOptions?type_id=' + type_id, function (data) {
        var items = [];
        if (data.status === 'success') {
            $.each(data.response, function (key, option) {
                items.push('<li><label class="control-label"><input type="checkbox" class="check" name="ShopProduct[configurable_attributes][]" value="' + option.id + '" name=""> ' + option.title + '</label></li>');
            });
            $('#availableAttributes').removeClass('d-none');
            $('<ul/>', {
                'class': 'list-unstyled',
                'style': 'margin-left:20px',
                html: items.join('')
            }).appendTo(attrs_block);
        } else {
            $('#availableAttributes').html('<div class="alert alert-danger">' + data.message + '</div>').removeClass('hidden');
        }
    });
});