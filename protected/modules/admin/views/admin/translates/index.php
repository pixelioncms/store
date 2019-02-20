
<?php
Yii::app()->tpl->openWidget(array(
    'title' => Yii::t('admin', 'TRANSLATE_FILES')
));
?>

<form method="post" action="/" id="translate-choose-form">
    <div style="padding: 15px;">
        <div class="row">
            <div class="col-md-3">
                <?php
                echo CHtml::dropDownList('type', '', array(
                    'app' => Yii::t('app', 'SYSTEM'),
                    'modules' => Yii::t('app', 'MODULES')
                        ), array(
                    'empty' => '--- Выбор переводов ---',
                    'class' => 'custom-select',
                    'onchange' => '
                    $("#load-section-2,#load-section-3").html("");
                    ajaxTranslate("#load-section-1","ajaxGet"); return false;
                    
                    '));
                ?>

            </div>
            <div class="col-md-3" id="load-section-1"></div>
            <div class="col-md-3" id="load-section-2"></div>
            <div class="col-md-3" id="load-section-3"></div>
        </div>
    </div>
</form>
<?php
Yii::app()->tpl->closeWidget();
?>


<div id="translateContainer"></div>


<script>
    function ajaxTranslate(selector, action) {
        $.ajax({
            type: 'POST',
            url: '/admin/app/translates/' + action,
            data: $('#translate-choose-form').serialize(),
            success: function (result) {
                $(selector).html(result);
            },
            error: function () {
                $('#translateContainer').html('');
                $(selector).html('');
                common.notify('Ошибка', 'error');

            },
            beforeSend: function () {
                $('#translateContainer').html('');
                $(selector).text(common.message.loading);
            }
        });

    }
</script>