// Process checked categories
$("#ShopMarkup").submit(function(){
    var checked = $("#ShopMarkupCategoryTree li a.jstree-checked");
    checked.each(function(i, el){
        var id = $(el).attr("id").replace('node_', '').replace('_anchor', '');
        $("#ShopMarkup").append('<input type="hidden" name="ShopMarkup[categories][]" value="' + id + '" />');
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