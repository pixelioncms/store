<?php
$value = (isset($_GET['q'])) ? $_GET['q'] : '';
?>
<div class="search-area">
    <?= Html::form(Yii::app()->controller->createUrl('/shop/category/search'), 'post', array('name' => 'searchform','id' => 'searchform')) ?>
    <div class="control-group">
        <input type="text" value="<?= $value ?>" placeholder="Поиск..." name="q" class="search-field" id="searchQuery" />
        <a class="search-button" href="javascript:void(0)" onClick="$('#searchform').submit();"></a>    
    </div>
    <?= Html::endForm() ?>
</div>

<script>
    $(function(){
        $('#searchQuery').keydown(function(event){ 
            if (event.which == 13) {
                $('#searchform').submit();
            }
        });
    });
</script>