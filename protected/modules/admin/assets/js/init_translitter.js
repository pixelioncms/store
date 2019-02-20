function init_translitter(model_path, isnew, usexhr) {
    var xhr;
    var alias = $('#alias');
    alias.parent().append('<div id="alias_result"></div>');
    if (translate_object_url==0) {
        $('#title').keyup(function (event) {
            var title = $.trim($(this).val());
            if (usexhr) {
                alias.val(ru2en.translit(title.toLowerCase())).addClass('loading');
                // alias.parent().append('<div id="alias_result"></div>');
            } else {
                alias.val(ru2en.translit(title.toLowerCase()));
            }
            if (alias.val().length > 2) {
                $("#alias_result").hide();
                if (usexhr) {
                    if (typeof xhr !== 'undefined')
                        xhr.abort();
                    xhr = $.ajax({
                        url: '/admin/app/ajax/checkalias',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            model_path: model_path,
                            value: alias.val(),
                            isNew: isnew
                        },
                        success: function (data) {
                            alias.removeClass("loading");
                            if (data.result) {
                                alias.parent().parent().addClass('has-error').removeClass('has-success');
                                $("#alias_result").html('<span class="badge badge-danger">URL занят</span>').show();
                                //common.notify('URL занят','danger');
                            } else {
                                alias.parent().parent().addClass('has-success').removeClass('has-error');
                                $("#alias_result").html('<span class="badge badge-success">URL свободен</span>').show();
                                //common.notify('URL свободен','success');
                            }
                        }
                    });
                }
            }
        });
    }
}


var onlineTranslite = function (to, text) {
    // var returned;
    var lang = lang_name + '-' + to;
    //this.getFormula = function(name) {
    return $.ajax({
        url: 'https://translate.yandex.net/api/v1.5/tr.json/translate',
        type: 'POST',
        data: {
            key: yandex_translate_apikey,
            format: "json",
            text: text,
            lang: lang
        },
        dataType: 'json',
        async: false
    }).responseText;
//return returned.text;
//}
}
