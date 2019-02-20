<?php
$langModel = new LanguageModel;
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
?>
<div style="padding:15px;">
    <div class="row body">
        <div class="col-md-5"><?=
            Html::dropDownList('from', 'ru', $langModel->dataLangList, array(
                'empty' => Yii::t('app', 'EMPTY_LIST'),
                'class' => 'form-control'
            ));
            ?>
            <br/>
            Введите текст:
            <br />
            <?= Html::textArea('text', null, array('class' => 'form-control noresize')); ?></div>
        <div class="col-md-2 text-center">
            <?=
            Html::button(html_entity_decode('Перевести &raquo;'), array(
                'id' => 'submit',
                'class' => 'btn btn-success',
                'style' => 'margin-top:80px'
            ));
            ?>
        </div>
        <div class="col-md-5">
            <?=
            Html::dropDownList('to', 'en', $langModel->dataLangList, array(
                'empty' => Yii::t('app', 'EMPTY_LIST'),
                'class' => 'form-control'
            ));
            ?>
            <br/>
            Результат:
            <br />
            <?php echo Html::textArea('result', null, array('class' => 'form-control noresize')); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php Yii::app()->tpl->closeWidget(); ?>


<script>
    $(function () {
        $('#submit').click(function () {
            $.ajax({
                url: "/admin/app/languages/ajaxOnlineTranslate",
                type: 'POST',
                data: {
                    token: common.token,
                    text: $('#text').val(),
                    lang: [$('#from').val(), $('#to').val()]
                },
                beforeSend: function () {
                    $('#submit').attr('disabled', true).val('Загрузка...');
                },
                success: function (response) {
                    $('#result').val(response);
                    $('#submit').attr('disabled', false).val('Перевести >>');
                }
            });
        });
    });
</script>
