<?php
$value = (isset($_GET['q'])) ? $_GET['q'] : '';
?>

<div id="search-box">
    <?= Html::form(Yii::app()->controller->createUrl('/shop/category/search'), 'post', array('id' => 'search-form')) ?>
    <div class="input-group"> <?= Html::textField('q', $value, array('class' => 'form-control ', 'placeholder' => 'Поиск...', 'id' => 'searchQuery')); ?>
        <div class="input-group-btn"><?= Html::submitButton('Найти', array('class' => 'btn btn-default')); ?></div>
    </div>
    <?= Html::endForm() ?>

</div>
<script>
    $(function () {
        $('#searchQuery').keydown(function (event) {
            if (event.which == 13) {
                $('#search-form').submit();
            }
        });
    });
</script>
