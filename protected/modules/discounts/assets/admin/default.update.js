// Process checked categories
/*$("#ShopDiscount").submit(function(){
    var checked = $("#ShopDiscountCategoryTree li.jstree-checked");
    checked.each(function(i, el){
        var cleanId = $(el).attr("id").replace('ShopDiscountCategoryTreeNode_', '');
        $("#ShopDiscount").append('<input type="hidden" name="ShopDiscount[categories][]" value="' + cleanId + '" />');
    });
});*/

// Check node
/*;(function($) {
    $.fn.checkNode = function(id) {
        $(this).bind('loaded.jstree', function () {
            $(this).jstree('checkbox').check_node('#ShopDiscountCategoryTreeNode_' + id);
        });
    };
})(jQuery);*/

// Process checked categories
$("#ShopDiscount").submit(function(){
    var checked = $("#ShopDiscountCategoryTree li a.jstree-checked");
    checked.each(function(i, el){
        var id = $(el).attr("id").replace('node_', '').replace('_anchor', '');
        $("#ShopDiscount").append('<input type="hidden" name="ShopDiscount[categories][]" value="' + id + '" />');
    });

});

// Check node
;(function($) {
    $.fn.checkNode = function(id) {
        $(this).bind('loaded.jstree', function () {
            $(this).jstree('check_node','node_' + id);
        });
    };
})(jQuery);