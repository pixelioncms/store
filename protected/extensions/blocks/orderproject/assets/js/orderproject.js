function orderprojectSend() {
    var form = $("#orderproject-form");
    if (!$('.btn-orderproject').hasClass('disabled')) {
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'html',
            success: function (data) {
                $('#orderproject-dialog').html(data);
                $('.btn-orderproject').attr('disabled', false);
                $('.btn-orderproject').removeClass('disabled');
                common.removeLoader();

                if (window.location.hostname == 'pixelion.moscow') {
                    dataLayer.push({'event': 'submitForm'});
                }

            },
            beforeSend: function () {
                //  $.jGrowl('loading...');
                $('.btn-orderproject').attr('disabled', true);
                $('.btn-orderproject').addClass('disabled');
                common.addLoader();
            },
            error: function (data) {
                common.notify('Ошибка', 'error');
            }
        });
    }
}