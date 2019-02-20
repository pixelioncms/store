<?php
$this->pageName = 'Jquery UI';
?>

<script>
    $(function(){



        $('#spinner').spinner({
            icons: {
             //down: "icon-arrow-down",
             //up: "icon-arrow-up"
             }


        });
        $( "#slider" ).slider({range: true,values:[10,40]});

       /* $('#dialog').dialog({
            title:'Title',
            modal:true
        });*/
        $( "#datepicker" ).datepicker();
    });
</script>
<input id="spinner" >

<div id="slider"></div>

<div id="dialog">
    dsasad
</div>
    <div id="datepicker"></div>
<?php
$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    'name' => 'city',
    'source' => array('123','321'),
    // additional javascript options for the autocomplete plugin
    'options' => array(
        'minLength' => 2,
        'select' => new CJavaScriptExpression('function(event, ui){

        }')
    ),
    'htmlOptions' => array(
        'class' => 'form-control'
    ),
));
?>