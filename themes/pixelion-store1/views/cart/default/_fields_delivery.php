<h5><?= Yii::t('CartModule.default', 'DELIVERY_METHODS'); ?></h5>

<script>

    var searchRequest = null;

    $(function () {
        var minlength = 3;

        $("#OrderCreateForm_user_city").keyup(function () {
            var that = this,
                value = $(this).val();

            if (value.length >= minlength) {
                if (searchRequest != null)
                    searchRequest.abort();
                searchRequest = $.ajax({
                    type: "GET",
                    url: "/cart/delivery/novaposhtaCities/",
                    data: {
                        'word': value
                    },
                    dataType: "text",
                    success: function (msg) {
                        //we need to check if the value is the same
                        if (value == $(that).val()) {
                            //Receiving the result of search here
                        }
                    }
                });
            }
        });




        $( "#OrderCreateForm_user_city" ).autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: "search.php",
                    dataType: "jsonp",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                } );
            },
            minLength: 2,
            select: function( event, ui ) {
                log( "Selected: " + ui.item.value + " aka " + ui.item.id );
            }
        } );
    });
</script>
<?php
if ($deliveryMethods) {
    echo '<div class="form-group form-check">';
    foreach ($deliveryMethods as $delivery) {
        echo Html::activeRadioButton($form, 'delivery_id', array(
            'checked' => ($form->delivery_id == $delivery->id),
            'uncheckValue' => null,
            'value' => $delivery->id,
            'data-price' => Yii::app()->currency->convert($delivery->price),
            'data-free-from' => Yii::app()->currency->convert($delivery->free_from),
            'onClick' => 'cart.recountTotalPrice(this); ',
            'data-value' => Html::encode($delivery->name),
            'id' => 'delivery_id_' . $delivery->id,
            'data-delivery-system'=>($delivery->delivery_system)?$delivery->delivery_system:'false',
            'class' => 'delivery_checkbox form-check-input'
        ));
        echo Html::label(Html::encode($delivery->name),'delivery_id_' . $delivery->id,array('class'=>'form-check-label'));

        if (isset($delivery->description)) {
            ?><p><?= $delivery->description ?></p>
        <?php }
    }
    echo '</div>';
} else {
    Yii::app()->tpl->alert('danger', Yii::t('CartModule.default','ALERT_DELIVERY'));
}
?>
<div class="form-group">
    <?= Html::activeLabel($form, 'user_city', array('required' => true, 'class' => 'info-title control-label')); ?>
    <?= Html::activeTextField($form, 'user_city', array('class' => 'form-control')); ?>
    <?= Html::error($form, 'user_city'); ?>
</div>
<div class="form-group">
    <?= Html::activeLabel($form, 'user_address', array('required' => true, 'class' => 'info-title control-label')); ?>
    <?= Html::activeTextField($form, 'user_address', array('class' => 'form-control')); ?>
    <?= Html::error($form, 'user_address'); ?>
</div>