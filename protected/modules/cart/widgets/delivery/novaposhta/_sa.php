<?php


echo $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    'name' => 'city',
    'source' => array_values($test),
    // additional javascript options for the autocomplete plugin
    'options' => array(
        'minLength' => 2,
        'select' => new CJavaScriptExpression('function(event, ui){
            $.ajax({
                type:"POST",
                url:"/cart/delivery/process?delivery_id=' . $method->id . '",
                data:{city:ui.item.value}
            });
        }')
    ),
    'htmlOptions' => array(
        'class' => 'form-control'
    ),
), true);