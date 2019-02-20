<?php
$langModel = new LanguageModel;

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));


?>
<div style="padding: 15px">
<?php
Yii::app()->tpl->alert('info',Yii::t('app','TRANSLATE_ALERT_INFO'),false);

$lang = Yii::app()->request->getParam('lang');
if (!$lang) {

    echo Html::form('', 'GET');
    echo Html::dropDownList('lang',null, $langModel->dataLangList,array('class'=>'form-control'));
    echo Html::submitButton('Начать перевод', array('class' => 'btn btn-success'));
    echo Html::endForm();
    ?>

    <?php
   // return;
}?>
    </div>
<div style="padding: 15px">
<?php




if ($lang) {
$defaultLang = Yii::app()->languageManager->default->code;
$modules = Yii::app()->getModules();

$this->remove_old_lang_dir("webroot.protected.messages.{$lang}");
$result = Yii::app()->cache->get('CACHE_LANGUAGE_TRANSLATE');
if ($result === false) {
    $result = array();

    $dir = Yii::getPathOfAlias("webroot.protected.messages.{$defaultLang}");
    if (file_exists($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $result['application'][] = array('file' => $file, 'path' => "webroot.protected.messages");
            }
        }
    }

        $extPath = "webroot.protected.extensions";
        $extdirs = scandir(Yii::getPathOfAlias($extPath));
        foreach ($extdirs as $entry) {
            if ($entry != '.' && $entry != '..' && $entry != 'index.html' && !preg_match("/\.([a-zA-Z0-9]+)/", $entry)) {
                if (file_exists(Yii::getPathOfAlias("{$extPath}.{$entry}.messages.{$defaultLang}"))) {
                    $files = scandir(Yii::getPathOfAlias("{$extPath}.{$entry}.messages.{$defaultLang}"));
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            $result['extensions.' . $entry][] = array('file' => $file, 'path' => "{$extPath}.{$entry}.messages");
                        }
                    }
                }
            }
        }

    foreach ($modules as $mod => $obj) {
        $this->remove_old_lang_dir("mod.{$mod}.messages.{$lang}");
        $dir = Yii::getPathOfAlias("mod.{$mod}.messages.{$defaultLang}");
        if (file_exists($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $result[$mod][] = array('file' => $file, 'path' => "mod.{$mod}.messages");
                }
            }
        }
    }
    Yii::app()->cache->set('CACHE_LANGUAGE_TRANSLATE', $result, 3600);
}
?>

<?php
Yii::app()->tpl->alert('info', 'Пожалуйста дождитесь окончание результата.', false);
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title" style="padding-right: 15px;">
            <div class="row">
                <div class="col-sm-4">
                    <div id="progress-send"></div>
                </div>
                <div class="col-sm-8">
                    <div class="progress hidden">
                        <div class="progress-bar progress-bar-success progress-bar-striped progress-bar-animated" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-container">
        <table id="result" class="table table-striped table-bordered">
            <thead>
            <th width="70%">Файл</th>
            <th width="15%">Раздел</th>
            <th width="15%"><?= Yii::t('app', 'STATUS') ?></th>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
</div>
<?php } ?>
<?php Yii::app()->tpl->closeWidget(); ?>
<script>
    var json = <?= CJSON::encode($result); ?>;
    var counter = 0;
    var lang = '<?= $lang; ?>';

    function getFilesTotalCount() {
        var result = 0;
        $.each(json, function (i, v) {
            result += v.length;
        });
        return result;
    }

    function doTask(taskNum, next, i, mod, num) {
        var time = Math.floor(Math.random() * 3000);
        $('.progress').removeClass('hidden');

        setTimeout(function () {
            $.ajax({
                type: "POST",
                url: "/admin/app/translates/ajaxApplication?lang=" + lang,
                data: {
                    file: taskNum[i].file,
                    path: taskNum[i].path
                },
                dataType: 'json',
                success: function (data) {
                    var status = (data.status == 'success')?data.status:'danger';
                    var result = Math.round((num / getFilesTotalCount() * 100), 2);
                    $('.progress .progress-bar').css({'width': result + '%'}).html(result + '%');
                    $("#sended").text(num - 1 + 1);
                    $('#result tbody #row' + num).html('<td>' + taskNum[i].file + ' </td><td class="text-center">' + mod + '</td><td class="text-center"><span class="badge badge-' + status + '">' + data.message + '</span></td>');
                    next();
                },
                beforeSend: function () {
                    $('#result tbody').prepend($('<tr/>', {'id': 'row' + num}));
                    $('#result tbody #row' + num).html('<td colspan="3"><div  class="ajax-loading"></div>Подождите, идет процес перевода.</td>');
                    //$(".senden-row" + i).text("Идет отправка...");
                },
                complate: function () {

                },
                error: function (XHR, textStatus, errorThrown) {
                    $('#result tbody #row' + num).html('<td colspan="2">Error: ' + XHR.status + ' ' + XHR.statusText + '</td>');
                    common.notify("Error: " + XHR.status + " " + XHR.statusText,'error');

                }
            });



        }, time);
    }

    function createTask(taskNum, i, mod, num) {
        return function (next) {
            doTask(taskNum, next, i, mod, num);
        };
    }

    $("#progress-send").html("Переведино: <span id='sended'>0</span> из " + getFilesTotalCount());

    $.each(json, function (mod, files) {
        for (var i = 0; i < files.length; i++) {
            counter++;
            $(document).queue('tasks', createTask(files, i, mod, counter));
        }
    });

    $(document).queue('tasks', function () {
        console.log("Translate: all done");
        $('.progress .progress-bar').removeClass('progress-bar-animated').html('Готово');
    });

    $(document).dequeue('tasks');

</script>




