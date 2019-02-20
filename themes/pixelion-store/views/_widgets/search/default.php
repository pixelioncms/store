<?php
$value = (isset($_GET['q'])) ? $_GET['q'] : '';
?>
<div class="search-area">
    <?= Html::form(Yii::app()->controller->createUrl('/shop/category/search'), 'GET', array('id' => 'search-form')) ?>
    <div class="input-group">
        <?php
        echo Html::label('Поиск','searchQuery',array('class'=>'sr-only'));
        echo Html::textField('q', $value, array(
            'size' => 45,
            'maxlength' => 45,
            'placeholder' => 'Поиск...',
            'id' => 'searchQuery',
            'class' => 'search-field form-control'
        ))
        ?>
        <div class="input-group-append">
            <?= Html::link('Поиск', '#', array('onClick' => '$("#search-form").submit(); return false;', 'class' => 'btn btn-primary search-button')); ?>
        </div>

    </div>
    <div id="search-query-result" style="position: absolute;width: 100%;z-index: 1001"></div>
    <?= Html::endForm() ?>

    <small>Например: <i>Телевизор Samsung</i></small>
</div>
<?php

Yii::app()->clientScript->registerScript("w".$this->id,"
    $(function () {
        var xhr;
        $('#searchQuery').keyup(function (event) {
            var that = $(this);
            if (event.which == 13) {
                $('#search-form').submit();
            }

            xhr = $.ajax({
                url: $('#search-form').attr('action'),
                type: 'GET',
                data: {'term': $(this).val()},
                beforeSend:function(){
                    $('.overlay').remove();
                    console.log(xhr);
                    if(xhr !== undefined){
                        xhr.abort();
                    }
                    that.addClass('loading');
                    $('#search-query-result').html('');
                },
                success: function (data) {
                    $('body').append('<div class=\"overlay\" style=\"position: fixed;left:0;top:0;width:100%;height:100%;z-index: 1000;\"></div>');
                    $('#search-query-result').html(data);
                    that.removeClass('loading');
                }
            });
        });

        $(document).on('click','.overlay',function (e) {
            $('#search-query-result').html('');
            $('.overlay').remove();
        });
    });
",CClientScript::POS_END);
?>




