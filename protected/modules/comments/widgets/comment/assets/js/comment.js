if (typeof jQuery === 'undefined') {
    throw new Error('Comments requires jQuery');
}
if (typeof $.session === 'undefined') {
    throw new Error('Comments requires jQuery.session');
}
//if (typeof jQuery.jGrowl === 'undefined') {
//  throw new Error('Comments requires jQuery.jGrowl');
//}


/*new comments js functions
 var fn = {};
 fn.comment = {
 add:function(form_id){
 var form = $(form_id);
 this.ajax(form.attr('action'),form.serialize(),function(data, textStatus, xhr){
 console.log(xhr);
 if(data.errors){
 $.jGrowl(data.errors, {
 position:"bottom-right"
 });
 }else{
 $.jGrowl(data.message, {
 position:"bottom-right"
 });

 }
 },'POST','json');
 return false;
 },
 ajax:function(url,data,success,type,dataType){
 console.log(type);
 type = (type==undefined)?'POST':type;
 dataType = (dataType==undefined)?'html':dataType;
 $.ajax({
 url: url,
 type:type,
 data:data,
 dataType:dataType,
 success:success
 });
 }
 };

 */
(function ($) {
    var methods = {
        init: function (options) {
            var settings = $.extend({
                placeholder: "_",
                completed: null
            }, options);
            console.log(settings);
        },
        currentTime: function () {
            return Math.round((new Date()).getTime() / 1000)
        },
        reply_form: function (config) {
            var caf = $.session.get("caf");
           // console.log(caf);
          // console.log(methods.currentTime());
            //if (caf < methods.currentTime()) {
                $('.container-reply').html('');
                var selector = $('#comment-reply-form-' + config.pk);
                $.ajax({
                    url: '/comments/reply/' + config.pk,
                    type: 'POST',
                    data: {
                        token: common.token
                    },
                    success: function (data) {
                        $(selector).html(data);
                    }
                });
           // } else {
           //     if (comment.foodAlert) {
           //         common.notify('StopFood', 'danger');
           //     }
           // }
        },
        reply_submit: function (id) {
            var form = $('#comment-reply-form-' + id);
            var data = $(form).find('form').serialize();
            console.log(data);
            $.ajax({
                url: $(form).find('form').attr('action'),
                type: 'POST',
                data: data + "&reply_id=" + id,
                dataType: 'json',
                success: function (dataz) {
                    if (dataz.code == 'success') {
                        $.session.set("caf", methods.currentTime() + comment.foodTime);
                        $.fn.yiiListView.update('comment-list');
                        common.notify(dataz.flash_message, 'success');
                    } else if (dataz.code == 'fail') {
                        common.notify(dataz.response.text, 'error');
                    }
                }
            });
        },
        remove: function (config) {
            var container = this.selector;
            if (methods.currentTime() < config.time) {

                $('body').append('<div id="dialog"></div>');
                $('#dialog').dialog({
                    modal: true,
                    resizable: false,
                    width: '200',
                    height: 150,
                    closeOnEscape: true,
                    open: function () {
                        $(this).html('Удалить комментарий?');
                    },
                    close: function () {
                        $(this).remove();
                    },
                    buttons: [{
                        text: 'OK',
                        click: function () {
                            var dialog = this;
                            $(dialog).remove();
                            xhr = $.ajax({
                                url: '/comments/delete/' + config.pk,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    token: common.token
                                },
                                success: function (data) {
                                    if (data.code == 'success') {
                                        //$(dialog).dialog('close');
                                        /*Обновляем список комментариев*/
                                        $.fn.yiiListView.update('comment-list');

                                        common.notify(data.flash_message, 'success');
                                    } else if (data.code == 'fail') {

                                        common.notify(data.response.text, 'error');
                                    }
                                }
                            });

                        }
                    }, {
                        text: 'Cancel',
                        click: function () {
                            $(this).dialog('close');
                        }
                    }]
                });
            } else {
                common.notify('Time Out!!!');
                $('#comment-panel' + config.pk).remove();
            }
        },
        update: function (config) {
            var container = this.selector;
            var xhr;
            if (methods.currentTime() < config.time) {
                $('body').append('<div id="dialog"></div>');
                $('#dialog').dialog({
                    modal: true,
                    resizable: false,
                    width: '60%',
                    height: 200,
                    closeOnEscape: true,
                    open: function () {
                        var dialog_container = this;
                        xhr = $.ajax({
                            url: '/comments/edit',
                            type: 'POST',
                            data: {
                                _id: config.pk,
                                token: common.token
                            },
                            success: function (data) {
                                $(dialog_container).html(data);
                                $('.ui-dialog-buttonset').show();

                            }
                        });
                    },
                    close: function () {
                        $(this).remove();
                        xhr.abort();
                    },
                    create: function () {
                        $('.ui-dialog-buttonset').hide();
                    },
                    buttons: [{
                        text: 'Save',
                        click: function () {
                            var data = $('form:first', this).serialize();
                            var dialog = this;
                            xhr = $.ajax({
                                url: '/comments/edit',
                                type: 'POST',
                                dataType: 'json',
                                data: data + "&_id=" + config.pk,
                                success: function (data) {
                                    if (data.code == 'success') {
                                        $(dialog).dialog('close');
                                        $(container).html(data.response);
                                        $.jGrowl(data.flash_message, {
                                            position: "top-right"
                                        });
                                        common.notify(data.flash_message, 'success');
                                    } else if (data.code == 'fail') {
                                        common.notify(data.response.text, 'error');
                                    }
                                }
                            });

                        }
                    }, {
                        text: 'Cancel',
                        click: function () {
                            $(this).dialog('close');
                        }
                    }]

                });
            } else {

                common.notify('Time Out!!!');
                $('#comment-panel' + config.pk).remove();
            }
        }
    };

    $.fn.comment = function (method) {

        // логика вызова метода
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Метод с именем ' + method + ' не существует для jQuery.comment');
        }
    };

})(jQuery);
