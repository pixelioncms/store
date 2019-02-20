
<?php

$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs' => $tabs, 
    'options' => array(
        'collapsible' => false,
        'beforeLoad'=>'js:function(event, ui){

 if(ui.jqXHR && ui.jqXHR.readyState != 4){
           // ui.jqXHR.abort();
             console.log(ui.jqXHR);
        }
        
           
            $(ui.panel.selector).html(common.message.loading);
           // $(ui.panel.selector).html("<div class=\"osahanloading\"></div>");
            //$(".loaderArea").fadeIn("slow");
        

        }',
        "activate" => 'js:function(event, ui){
            //var n = ui.newTab.find("a").attr("href").indexOf("#");
            //console.log(n);
            //if(!n){
           // window.location.hash = ui.newTab.find("a").attr("href");
            $(document).scrollTop(0);
           // }

        }'
    ),
    'htmlOptions' => array(
        'class' => ''
    )
));
?>









