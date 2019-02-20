
<?php


echo CHtml::ajaxLink($this->btn_title, array('/ajax/orderproject.action'), array(
    'type' => 'GET',
    'beforeSend' => "function(){
              $('body').append('<div id=\"orderproject-dialog\"></div>');
              }
    ",
    'success' => "function( data ){
        var result = data;
        $('#orderproject-dialog').dialog({
      //  modal:true,
       // autoOpen:false,
        dialogClass:'dialog-orderproject',
        //responsive: false,
        resizable: false,
        draggable: true,
       // height: 'auto',
        //minHeight: 95,
        title:'" . Yii::t('OrderprojectWidget.default', 'TITLE') . "',
        //width: 400,
        scaleH: 1,
        scaleW: 1,
        responsive: false,
        show:{
            effect: 'fadeIn',
            duration: 800
        },
        open:function(){
            $('#orderproject-form').keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                      $('#orderproject-form').submit();
                }
            });
            $('.ui-widget-overlay').bind('click', function() {
                $('#orderproject-dialog').dialog('close');
            });
            //test();
            //$('.ui-dialog :button').blur();
        },
        close:function(){
            $('#orderproject-dialog').remove();
            $('a.btn-callback').removeClass(':focus');        
        },
       /* buttons: [
            {
                text: '" . Yii::t('OrderprojectWidget.default', 'BUTTON_SEND') . "',
                'class':'btn btn-default btn-orderproject wait',
                click: function() {
                    orderprojectSend();
                }
            }
        ]*/
        
        });

        $('#orderproject-dialog').html(result);

        /*$('.ui-dialog').position({
                  my: 'center',
                  at: 'center',
                  of: window,
                  collision: 'fit'
            });
            */
            $('.dialog-orderproject').css({
                'width': $(window).width(),
                'height': $(window).height(),
                'left': '0px',
                'top': '0px',
                'position':'fixed',
                'z-index':1031
            });
        }",
    // 'data' => array('val1' => '1', 'val2' => '2'), // посылаем значения
    'cache' => 'false' // если нужно можно закэшировать
        ), array(// самое интересное
    // 'href' => Yii::app()->createUrl('ajax/new_link222'), // подменяет ссылку на другую
    'class' => "btn btn-default btn-orderproject" // добавляем какой-нить класс для оформления
        )
);