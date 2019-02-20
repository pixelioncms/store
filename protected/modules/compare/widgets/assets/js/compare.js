var compare = window.compare || {};
compare = {
    add: function (product_id) {
        common.ajax('/compare/add/' + product_id, {}, function (data) {
            $('#countCompare').html(data.count);
            common.notify(data.message, 'success');
            var selector = $('#compare-' + product_id);
            selector.addClass('added');
            //selector.html(data.btn_message);
        }, 'json', 'GET');
    }
}