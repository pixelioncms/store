<?php
$value = (isset($_GET['q'])) ? $_GET['q'] : '';
?>
<div class="search-area">
    <?= Html::form(array('/shop/category/search'), 'post', array('id' => 'search-form-footer')) ?>
    <div class="input-group">
        <?php
        $this->widget('app.jui.JuiAutoComplete', array(
            'value' => $value,
            'sourceUrl' => array('/products/search'),
            'name' => 'q',
            'options' => array(
                'minLength' => 3,
                'select' => "js: function(event, ui) {
                    location.href = ui.item.url;
                }",
            ),
            'methodChain' => ".data('ui-autocomplete')._renderItem = function(ul, item) {

                    return $('<li></li>')
                        .data('item.autocomplete', item)
                        .append('<div><img src=\"' + item.image + '\"><div class=\"product-right-side\">' + item.label + '<br/>' + item.price + ' <sup>' + item.symbol + '</sup></div></div>')
                        .appendTo(ul);
                };",
            'htmlOptions' => array(
                'size' => 45,
                'maxlength' => 45,
                'placeholder' => 'Поиск...',
                'id' => 'searchQueryFooter',
                'class' => 'search-field form-control'
            ),
        ));
        ?>
        <div class="input-group-append">
        <?= Html::link('<i class="icon-search"></i>', 'javascript:void(0);', array('onClick' => '$("#search-form-footer").submit();', 'class' => 'input-group-addon btn btn-primary search-button')); ?>
        </div>
    </div>
    <?= Html::endForm() ?>

</div>
<?php
Yii::app()->clientScript->registerScript("w".$this->id,"
    $(function () {
        $('#searchQueryFooter').keydown(function (event) {
            if (event.which == 13) {
                $('#search-form-footer').submit();
            }
        });
    });
",CClientScript::POS_END);

?>
<script>

</script>



