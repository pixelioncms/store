/**
 * Requires compatibility with common.js
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 *
 *
 * @param boolean cart.spinnerRecount Статичный пересчет и/или с ajax
 * @function recountTotalPrice Пересчет общей стоимости
 * @function renderBlockCart Перезагрузка блока корзины (ajax response)
 * @function remove Удаление обэекта с корзины (ajax response)
 * @function add Добавление обэекта в корзину (ajax response)
 * @function recount Пересчет корзины (ajax response)
 * @function notifier Сообщить о появление (ajax response)
 * @function init Инициализация jquery spinner
 */
var cart_recount_xhr;
var cart = window.cart || {};
cart = {
    /**
     * @return boolean
     */
    spinnerRecount: true,
    selectorTotal: '#total',
    skin: 'default',
    /**
     * @param that
     */
    recountTotalPrice: function (that) {
        var total = parseFloat(orderTotalPrice);
        var delivery_price = parseFloat($(that).attr('data-price'));
        var free_from = parseFloat($(that).attr('data-free-from'));
        if (delivery_price > 0) {
            if (free_from > 0 && total > free_from) {
                // free delivery
            } else {
                total = total + delivery_price;
            }
        }

        if ($(that).data('delivery-system')) {
            console.log($(that).data('delivery-system'));
            $.ajax({
                url: '/cart/delivery/process?delivery_id=' + $(that).val(),
                type: 'GET',
                dataType: 'html',
                //  data:{system:$(that).data('delivery-system')},
                success: function (data) {
                    $('#delivery-form').html(data);
                }
            });
        } else {
            $('#delivery-form').html('');
        }
        $(cart.selectorTotal).html(total.toFixed(2));
    },
    renderBlockCart: function () {
        $("#cart").load('/cart/renderSmallCart', {skin: cart.skin});
    },
    /**
     * @param product_id ИД обэекта
     */
    remove: function (product_id) {
        common.setText('loadingText', 'пересчет...');
        console.log(common.getText('loadingText'));
        common.ajax('/cart/remove/' + product_id, {}, function () {
            cart.renderBlockCart();
            common.report('Товар успешно удален!');
        }, 'html', 'GET');
    },
    /**
     * @param formid Id формиы
     */
    add: function (formid) {
        var form = $('#form-add-cart-' + formid);
        common.ajax(form.attr('action'), form.serialize(), function (data, textStatus, xhr) {
            console.log(xhr);
            if (data.errors) {
                common.notify(data.errors, 'error');
            } else {
                cart.renderBlockCart();
                common.notify(data.message, 'success');
                common.removeLoader();
                $('body,html').animate({
                    // scrollTop: 0
                }, 500, function () {
                    $("#cart").fadeOut().fadeIn();
                });

            }
        }, 'json');
    },
    /**
     * @param product_id ИД обэекта
     * @param quantities Количество
     */
    recount: function (quantities, product_id) {
        var disum = Number($('#balance').attr('data-sum'));

        if (cart_recount_xhr !== undefined)
            cart_recount_xhr.abort();

        cart_recount_xhr = $.ajax({
            type: 'POST',
            url: '/cart/recount',
            data: {
                product_id: product_id,
                quantities: quantities
            },
            dataType: 'json',
            success: function (data) {
                $('#row-total-price' + product_id).html(data.rowTotal);
                $('#price-unit-' + product_id).html(data.unit_price);
                var delprice = 0;
                if ($('.delivery-choose').prop("checked")) { //for april
                    delprice = parseInt($('.delivery-choose:checked').attr("data-price"));

                }
                var test = data.totalPrice;
                var total = parseInt(test.replace(separator_thousandth, '').replace(separator_hundredth, '')) + delprice;
                // }


                // $('#balance').text(data.balance);
                //console.log(Number(data.totalPrice));
                // console.log(disum);
                // console.log(data.totalPrice * 2);
                //$('#balance').text((Number(data.totalPrice) * disum / 100));

                common.removeLoader();
                $(cart.selectorTotal).text(price_format(total));
                cart.renderBlockCart();
            }
        });
    },
    /**
     * @param product_id ИД обэекта
     */
    notifier: function (product_id) {
        $('body').append($('<div/>', {
            'id': 'dialog'
        }));
        $('#dialog').dialog({
            title: 'Сообщить о появлении',
            modal: true,
            resizable: false,
            draggable: false,
            responsive: true,
            open: function () {
                var that = this;
                common.ajax('/notify', {
                    product_id: product_id
                }, function (data, textStatus, xhr) {
                    $(that).html(data.data);
                }, 'json');
            },
            close: function () {
                $('#dialog').remove();
                $('a.btn-danger').removeClass(':focus');
            },
            buttons: [{
                text: common.message.cancel,
                'class': 'btn btn-link',
                click: function () {
                    $(this).remove();
                }
            }, {
                text: common.message.send,
                'class': 'btn btn-primary',
                click: function () {
                    common.ajax('/notify', $('#notify-form').serialize(), function (data, textStatus, xhr) {
                        if (data.status == 'OK') {
                            $('#dialog').remove();
                            //common.report(data.message);
                            common.notify(data.message, 'success');
                        } else {
                            $('#dialog').html(data.data);
                        }
                    }, 'json');
                }
            }]
        });
    },

    init: function () {
        console.log('cart.init');
        $(function () {
            $('.spinner').spinner({
                max: 999,
                min: 1,
                mouseWheel: false,
                /*icons: {
                 down: "btn btn-default",
                 up: "btn btn-default"
                 },*/
                //клик по стрелочкам spinner
                spin: function (event, ui) {
                    var product_id = $(this).attr('product_id');
                    if (ui.value >= 1 && cart.spinnerRecount) {
                        cart.recount(ui.value, product_id);
                    }
                },
                stop: function (event, ui) {
                    //запрещаем ввод числа больше 999;
                    if ($(this).val() > 999)
                        $(this).val(999);
                },
                change: function (event, ui) {
                    var product_id = $(this).attr('product_id');
                    if ($(this).val() < 1) {
                        $(this).val(1);
                    }
                    if (cart.spinnerRecount) {


                        cart.recount($(this).val(), product_id);
                    }
                }
            });
            // $('.spinner-down').html('-');
            // $('.spinner-up').html('+');
        });
    }
};

cart.init();

