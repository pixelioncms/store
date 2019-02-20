<script>


    var tasks = jQuery.unique(<?= json_encode($mails) ?>);

    function doTask(taskNum, next, i, num) {
        var time = Math.floor(Math.random() * 3000);
        $('.progress').removeClass('d-none');
        $("#sended-result").prepend('<div id="send-' + i + '" class="list-group-item">Подготовка...</div>');
        setTimeout(function () {
            //    console.log(taskNum);

            $.ajax({
                type: "POST",
                url: "/admin/delivery/default/sendmail",
                data: {
                    email: taskNum,
                    text: "<?php echo $model->text ?>",
                    themename: "<?php echo $model->themename ?>"
                },
                success: function () {
                    var result = Math.round((num / tasks.length * 100), 2);
                    $('.progress .progress-bar').css({'width': result + '%'});
                    $("#sended").text(i + 1);
                    $("#send-" + i).html('<i class="icon-check text-success"></i> ' + taskNum);

                    next();
                },
                beforeSend: function () {
                    $("#send-" + i).html('<i class="icon-refresh text-secondary"></i> ' + taskNum);
                },
                complate: function () {

                },
                error: function (XHR, textStatus, errorThrown) {
                    $("#send-" + i).text("Error: " + XHR.status + " " + XHR.statusText);
                    common.notify("Error: " + XHR.status + " " + XHR.statusText,'error');
                }
            });



        }, time)
    }

    function createTask(taskNum, i, num) {
        return function (next) {
            doTask(taskNum, next, i, num);
        }
    }


    $("#progress-send").html("Отправлено: <span id='sended'>0</span> из <span id='total'>" + tasks.length + "</span>");
    for (var i = 0; i < tasks.length; i++) {
        var num = i + 1;
        $(document).queue('tasks', createTask(tasks[i], i, num));
    }

    $(document).queue('tasks', function () {
        console.log("all done");
        // $("#sended-result").prepend("<div><b>Готово!</b></div>");
        $('.progress .progress-bar').removeClass('progress-bar-animated').html('Готово');
    });

    $(document).dequeue('tasks');













</script>
<div class="container">
    <div class="form-group row">
        <div class="col-sm-4">Тема письма:</div>
        <div class="col-sm-8"><?php echo $model->themename ?></div>

    </div>
    <div class="form-group row">
        <div class="col-sm-4">Содержание письма:</div>
        <div class="col-sm-8"><?php echo $model->text ?></div>

    </div>
    <div class="form-group row">
        <div class="col-sm-4">Кому отправлять</div>
        <div class="col-sm-8"><?php echo $model->from ?></div>

    </div>

</div>


