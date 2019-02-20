var cam=cam||{};
cam.resultID='#webcam_result';
cam.webcamID='#webcam_load';
cam.opt=cam.opt||{
    
    snap:function(){
        Webcam.snap( function(data_uri) {
            $(cam.resultID).html('<img src=\"'+data_uri+'\"/>');
                $('#webcam-button-save').removeClass('hidden');
        });
    },
    save:function(){
        var data_uri = $(cam.resultID+' img').attr('src');
        Webcam.upload( data_uri, '/myscript.php', function(code, text) {
            if(code==200){
                $.jGrowl('Изображение успешно сохранено!');
                Webcam.reset(); //завершаем работу веб-камеры.
                $(cam.resultID).remove(); //удаляем диалоговое окно.
            }else{
                $.jGrowl('Ошибка '+code);
            }
        });
    },
    connect:function(){
        Webcam.attach(cam.webcamID);
         $.jGrowl('Это может занять некоторое время');
    },
    init:function(){
        Webcam.on('error', function(err) {
            if(err=='Could not access webcam.'){
                var err = 'Не удалось получить доступ веб-камера.';
                $('#webcam-response .alert div').addClass('failure');
                $('#webcam-response .alert div').html(err+' Попробуйте подключится заного.');
            }
        });
        
        Webcam.on('load', function() {
            $('#webcam-sideright').toggleClass('hidden');
            $('#webcam-button-save').toggleClass('hidden');
            $('#webcam-button-snap').toggleClass('hidden');
            $('#webcam-button-connect').remove();
            
            $('#webcam-response .alert div').addClass('success');
            $('#webcam-response .alert div').html('Веб-камера загружена');
            $('#webcam').removeClass('hidden');
            
        });

        Webcam.on('live', function() {
            // $('#webcam-response').html('Веб-камера загружена');
            });
    }
}
console.log(cam.opt);
cam.opt.init();