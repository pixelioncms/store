
    <div id="webcam-response">
        <?php Yii::app()->tpl->alert('info','Разрешите доступ к вашей веб-камере.',false) ?>
    </div>
<a href="javascript:cam.opt.connect();" class="ui-widget-button" id="webcam-button-connect">Подключится к веб-камере</a>
<div id="webcam" class="">
    <div class="webcam-side" id="webcam-sideleft">
        <div id="webcam_load" class="camera" style="width:320px; height:240px;"></div>
        <a href="javascript:cam.opt.snap();" id="webcam-button-snap" class="ui-widget-button hidden">Сделать снимок</a>
    </div>
    <div class="webcam-side hidden" id="webcam-sideright">
        <div id="webcam_result"></div>
        <a href="javascript:cam.opt.save();" id="webcam-button-save" class="ui-widget-button">Сохранить мнимок</a>
    </div>
</div>

