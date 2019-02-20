var wishlist = window.wishlist || {};
wishlist = {
    add: function (product_id) {
        common.ajax('/wishlist/add/' + product_id, {}, function (data, textStatus, xhr) {
            $('#countWishlist').html(data.count);
            common.notify(data.message, 'success');
            var selector = $('#wishlist-' + product_id);
            selector.addClass('added');
            //selector.html(data.btn_message);
        }, 'json', 'GET');
    }
}