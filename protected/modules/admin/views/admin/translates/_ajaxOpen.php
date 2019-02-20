<?php
$langModel = new LanguageModel;
Yii::app()->tpl->openWidget(array(
    'title' => 'Перевроды файла ' . $file,
));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'translate-form',
    'enableAjaxValidation' => false,
));
echo Html::hiddenField('module', $module);
echo Html::hiddenField('locale', $locale);
echo Html::hiddenField('file', $file);
echo Html::hiddenField('type', $type);
?>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th width="20%">Ключь</th>
        <th width="70%">Перевод</th>
        <th width="10%"><a href="javascript:addTranslate()" class="btn btn-sm btn-success"><i class="icon-add"></i></a>
        </th>
    </tr>
    </thead>
    <tbody id="list">
    <?php
    foreach ($return as $key => $translate) {
        $pos = strpos($translate, '|');
        if ($pos) {
            $exp = explode('|', $translate);
            ?>
            <tr id="f<?= $key ?>">
                <td class="text-left"><?= Html::label($key, 'TranslateForm[' . $key . ']'); ?></td>
                <td><?php foreach ($exp as $key2 => $translate) { ?>
                        <?= Html::textField('TranslateForm[' . $key . '][]', substr($translate, 2), array('class' => 'form-control')); ?>
                        <br/>
                    <?php } ?>
                    <a href="javascript:void(0)" onclick="addParam('<?= $key ?>')" class="btn btn-xs btn-success">добавить
                        параметр</a></td>
                <td class="text-center"><a href="javascript:void(0)" class="btn btn-sm btn-secondary"
                                           onClick="removeTranslate('#f<?= $key ?>');"><i class="icon-delete"></i></a>
                </td>
            </tr>
        <?php } else { ?>
            <tr id="f<?= $key ?>">
                <td class="text-left"><?= Html::label($key, 'TranslateForm[' . $key . ']'); ?></td>
                <td><?= Html::textField('TranslateForm[' . $key . ']', $translate, array('class' => 'form-control')); ?></td>
                <td class="text-center"><a class="btn btn-sm btn-danger" href="javascript:void(0)"
                                           onClick="removeTranslate('#f<?= $key ?>');"><i class="icon-delete"></i></a>
                </td>
            </tr>
        <?php } ?>

    <?php } ?>
    </tbody>
</table>

<div class="form-group">
    <div class="text-center"><?= Html::link(Yii::t('app', 'SAVE'), 'javascript:ajaxSave()', array('class' => 'btn btn-success')); ?>
        <a href="javascript:options()" class="btn btn-secondary">Дополнительные параметры</a></div>
</div>


<div id="options" class="hidden">
    <div class=""><?= Yii::app()->tpl->alert('warning', 'Важно! Если файл выбранного перевода уже существует он будет перезаписан.', false); ?></div>
    <div class="clearfix">
        <div class="col-sm-4"><label for="lang" class="col-form-label">Перевести эти переводы на язык:</label></div>
        <div class="col-sm-8">
            <?php echo Html::dropDownList('lang', null, $langModel->dataLangList, array('empty' => Yii::t('app', 'EMPTY_LIST'), 'class' => 'form-control')); ?>
            <div class="hint">Данный файл будет переведен с помощью Yandex translate API. После выбора перевода, нажмите
                сохранить
            </div>
        </div>

    </div>
</div>


<?php
$this->endWidget();
?>

<script>
    function options() {
        $('#options').toggleClass('hidden');
    }
    function ajaxSave() {
        $.ajax({
            type: 'POST',
            url: '/admin/app/translates/ajaxOpen',
            data: $('#translate-form').serialize(),
            success: function (result) {
                // $('#tester').html(result);
                $.jGrowl('Перевод успешно сохранен');
            },
            beforeSend: function () {
                $.jGrowl('Сохранение...');
            },
            error: function () {
                $.jGrowl('Ошибка');
            }
        });
    }
    function addParam(key) {
        var valid = false;
        // var paramName = prompt('Введите ключь перевода','NEW_PARAM');
        alert('В разработке.');
    }
    function removeTranslate(obj) {
        $(obj).remove();
    }
    function addTranslate() {
        var valid = false;
        var paramName = prompt('Введите ключь перевода', 'NEW_PARAM');
        if (paramName != null) {
            paramName = paramName.replace(" ", "_");
            paramName = paramName.toUpperCase();
            $('#list tr td.textL').each(function (k, index) {
                if (paramName == $(index).text()) {
                    valid = false;
                    $.jGrowl('Такой параметр уже существует.');
                } else {
                    valid = true;
                }

            });
            if (valid) {
                $('#list').prepend($('<tr/>', {
                    'id': paramName
                }).append($('<td/>', {
                    'class': 'text-left'
                }).append($('<label>').attr('for', 'TranslateForm[' + paramName + ']').text(paramName)
                )).append($('<td/>').append($('<input/>', {
                    'type': 'text',
                    'name': 'TranslateForm[' + paramName + ']'
                }))).append($('<td/>').append($('<a/>', {
                    click: function () {
                        removeTranslate('#' + paramName);
                    }
                }).append($('<span/>', {'class': 'icon-trashcan icon-medium'})))));
            }
        }
    }
</script>