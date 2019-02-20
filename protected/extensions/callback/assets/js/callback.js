function callbackSend(){
    var form = $("#callback-form");
    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data:form.serialize(),
        dataType:'html',
        success:function(data){
            $('#callback-dialog').html(data);
            //$('.btn-callback').attr('disabled',false);
            common.removeLoader();

        },
        beforeSend:function(){
          //  $.jGrowl('loading...');
           // $('.btn-callback').attr('disabled',true);
           common.addLoader();
        },
        error: function(data) {
            common.notify('Ошибка','error');
        }
    });

}