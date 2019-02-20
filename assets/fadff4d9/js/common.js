if ($.ui.spinner) {

    $.widget("ui.spinner", $.ui.spinner, {
        _buttonHtml: function () {
            return "" +
                "<span class='ui-spinner-button ui-spinner-up " + this.options.icons.up + "'>+</span>" +
                "<span class='ui-spinner-button ui-spinner-down " + this.options.icons.down + "'>-</span>";
        }
    });
}

//var common = window.CMS_common || {};
common.getMsg = function (code) {
    return this.lang[this.language][code];
};
common.clipboard = function (selector) {
    var clipboard = new ClipboardJS(selector);
    clipboard.on('success', function (e) {
        $(selector).addClass('copy');
        common.notify('Скопировано', 'info', {
            onClosed: function () {
                $(selector).removeClass('copy');
            }
        });
    });
};
common.switchInputPass = function (inputId) {
    var s = $('#' + inputId);
    s.attr('type', (s.attr('type') === 'input') ? 'password' : 'input');
};
common.notify = function (text, type, params) {
    var t = (type === 'error') ? 'danger' : type;
    var default_params;
    if (common.isDashboard) {
        default_params = {
            type: t,
            allow_dismiss: false,
            placement: {
                from: "bottom",
                align: "left"
            }
        };
        $.notify({message: text}, $.extend(default_params, params));
    } else {
        default_params = {
            type: t,
            allow_dismiss: false
        };
        $.notify({message: text}, $.extend(default_params, params));
    }

};
common.geoip = function (ip) {
    // common.flashMessage = true;


    $.ajax({
        url: '/admin/app/ajax/geo/ip/' + ip,
        type: 'GET',
        dataType: 'html',
        beforeSend: function () {
            $('body').append('<div id=\"geo-dialog\"></div>');
        },
        success: function (result) {
            $('#geo-dialog').dialog({
                model: true,
                responsive: true,
                resizable: false,
                height: 'auto',
                minHeight: 95,
                title: 'Информация о ' + ip,
                width: 700,
                draggable: false,
                modal: true,
                open: function () {
                    $('.ui-widget-overlay').bind('click', function () {
                        $('#geo-dialog').dialog('close');
                    });
                },
                close: function () {
                    $('#geo-dialog').remove();
                }
            });

            $('#geo-dialog').html(result);

            $('.ui-dialog').position({
                my: 'center',
                at: 'center',
                of: window,
                collision: 'fit'
            });
        }
    });
};
common.close_alert = function (aid) {
    $('#alert' + aid).fadeOut(1000);
    $.cookie('alert' + aid, true, {
        expires: 1, // one day
        path: '/'
    });
};
common.hasChecked = function (has, classes) {
    if ($(has).is(':checked')) {
        $(classes).removeClass('hidden');
    } else {
        $(classes).addClass('hidden');
    }
};
common.addLoader = function (text) {
    if (text !== undefined) {
        var t = text;
    } else {
        var t = common.message.loading;
    }
    $('body').append('<div class="common-ajax-loading">' + t + '</div>');

};
common.removeLoader = function () {
    $('.common-ajax-loading').remove();
};
common.init = function () {
    console.log('common.init');
};
common.ajax = function (url, data, success, dataType, type) {
    var t = this;
    $.ajax({
        url: url,
        type: (type == undefined) ? 'POST' : type,
        data: data,
        dataType: (dataType == undefined) ? 'html' : dataType,
        beforeSend: function (xhr) {
            // if(t.ajax.beforeSend.message){
            //t.report(t.ajax.beforeSend.message);
            //}else{
            // t.report(t.getText('loadingText'));
            //}

        },
        error: function (xhr, textStatus, errorThrown) {
            t.notify(textStatus + ' ajax() ' + xhr.status + ' ' + xhr.statusText, 'error');
            //t.report(textStatus+' ajax() '+xhr.responseText);
        },
        success: success

    });
};
common.setText = function (param, text) {
    this.lang[this.language][param] = text;
};
common.getText = function (param) {
    return common.lang[this.language][param];
};
common.enterSubmit = function (formid) {
    $(formid).keydown(function (event) {
        if (event.which === 13) {
            // event.preventDefault();
            $(formid).submit();
        }
    });
};


common.init();


