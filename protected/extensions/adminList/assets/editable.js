$.fn.editable = function (options) {
    // Options
    var options = $.extend({
        "url": '/admin/app/ajax/updateGridRow',
        "paramName": "q",
        "callback": null,
        "saving": common.message.ok,
        "type": "text",
        "submitButton": 0,
        "delayOnBlur": 0,
        "extraParams": {},
        "editClass": null,
        "title": 'Editable'
    }, options);

    // Set up
    this.click(function (e) {
        e.preventDefault();
        //    if (this.editing) return;
        this.editable = function () {
            var me = this;
            /*options = $.extend({
                "url": '/admin/app/ajax/updateGridRow',
                "paramName": "q",
                "callback": null,
                "saving": common.language['success_update'],
                "type": "text",
                "submitButton": 0,
                "delayOnBlur": 0,
                "extraParams": {},
                "editClass": null,
                "title": 'Editable'
            }, $.parseJSON($(this).attr('data-json')));*/


            me.editing = true;
            me.orgHTML = $(me).find('div.editable-value').html();
            //  me.visHTML = $(me).find('div.editable-vision').html();
            $('body').append($('<div/>', {
                'id': 'dialog-update-row'
            }));
            $('#dialog-update-row').dialog({
                model: false,
                responsive: true,
                resizable: false,
                draggable: false,
                title: options.title,
                open: function () {
                    var d = $(this);
                    d.addClass('p-3');
                    var inputPk = document.createElement("input");
                    inputPk.type = 'hidden';
                    inputPk.value = $(me).attr('data-id');
                    inputPk.name = 'pk';


                    /*var inputModelAlias = document.createElement("input");
                    inputModelAlias.type = 'hidden';
                    inputModelAlias.value = options.modelAlias;
                    inputModelAlias.name = 'modelAlias';*/

                   // var inputField = document.createElement("input");
                   // inputField.type = 'hidden';
                   // inputField.value = options.attributeName;
                   // inputField.name = 'attribute';

                    var form = document.createElement("form");
                    form.id = 'editable-row';
                    form.method = 'POST';
                    form.action = options.url;
                    $(this).append(form);
                    $(form).append(inputPk);
                    //$(form).append(inputModelAlias);
                    //$(form).append(inputField);
                    $(form).append(createInputElementMe(me.orgHTML));
                    // common.enterSubmit('#editable-row');

                },
                close: function () {
                    $(this).remove();
                },
                buttons: [{
                    text: 'Обновить',
                    'class': 'btn btn-success',
                    click: function () {
                        var d = $(this);
                        var form = d.find('form');
                        var field = form.find('#editable-field');
                        var values;
                        var text;
                        $.ajax({
                            dataType: 'json',
                            url: options.url,
                            type: form.attr('method'),
                            data: form.serialize()+'&_editable=true',
                            success: function (response) {
                                if (response.success) {

                                    common.notify(response.message, 'success');
                                    //$.fn.yiiGridView.update(options.grid);
                                    d.dialog("close");
                                    var s = options.attributeName;

                                    //if(response.data[s]){
                                        $(me).html('<div class="editable-vision">' + response.data[s] + '</div><div class="editable-value">' + response.data[s] + '</div>');
                                   // }else{

                                        if(field.is("select")) {
                                            values=field.val();
                                            text=field.find(':selected').text();
                                            //$( "select option:selected" )
                                            console.log('select');
                                        }else{
                                            values=field.val();
                                            text=field.val();
                                            console.log('input');
                                        }
                                        $(me).html('<div class="editable-vision">' + text.trim() + '</div><div class="editable-value">' + values + '</div>');
                                  //  }



                                    // this.editable();
                                } else {
                                    if (response.error) {
                                        $.each(response.error, function (key, value) {
                                            common.notify(value, 'error');
                                        });
                                        common.notify(response.message, 'error');
                                    }
                                }

                            }
                        });
                    }
                }]
            });

        };
        this.editable();
    });
    // Don't break the chain
    return this;


    function createInputElementMe(v) {
        if (options.type === "textarea") {
            var input = document.createElement("textarea");
            input.className = 'form-control';
            options.submitButton = true;
            options.delayOnBlur = 100; // delay onBlur so we can click the button
        } else if (options.type === "dropdownlist") {
            if (options.items) {
                var input = document.createElement("select");
                input.className = 'form-control';
                for (var i=0; i < options.items['k'].length; i++) {
                    $(input).append($('<option/>', {
                        'value': options.items['k'][i],
                    }).text(options.items['v'][i]));
                }
            }
        } else {
            var input = document.createElement("input");
            input.type = "text";
            input.className = 'form-control';
            input.value = 'fdasafsd';
        }
        $(input).val(v);
        //input.name = options.paramName;
        input.name = options.modelName+'['+options.attributeName+']';
        input.id = 'editable-field';
        return input;
    }
};