
<?php
echo $this->id;
//index.php?r=main/ajax/widget.actionCallback
echo CHtml::ajaxLink(Yii::t('CallbackWidget.default', 'BUTTON'), array('/ajax/callback.action'), array(
    'type' => 'GET',
    'beforeSend' => "function(){
              $('body').append('<div id=\"callback-dialog\"></div>');
              }
    ",
    'success' => "function( data ){
        var result = data;
        $('#callback-dialog').dialog({
      //  modal:true,
       // autoOpen:false,
        dialogClass:'window',
        //responsive: false,
       // resizable: false,
       // height: 'auto',
        //minHeight: 95,
        title:'" . Yii::t('CallbackWidget.default', 'TITLE') . "',
        //width: 400,
        open:function(){
            $('#callback-form').keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                      $('#callback-form').submit();
                }
            });
            $('.ui-widget-overlay').bind('click', function() {
                $('#callback-dialog').dialog('close');
            });

            //$('.ui-dialog :button').blur();
        },
        close:function(){
            $('#callback-dialog').remove();
            // $('#jGrowl').jGrowl('shutdown').remove();
            $('a.btn-callback').removeClass(':focus');        
        },
        buttons: [
            {
                text: '" . Yii::t('CallbackWidget.default', 'BUTTON_SEND') . "',
                'class':'btn btn-default btn-callback wait',
                click: function() {
                  //  $('.btn-callback').hide();
                    callbackSend('#callback-dialog');
                }
            }
        ]
        
        });

        $('#callback-dialog').html(result);

        /*$('.ui-dialog').position({
                  my: 'center',
                  at: 'center',
                  of: window,
                  collision: 'fit'
            });*/
            $('.ui-dialog').css({
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
    'class' => "btn btn-default btn-callback" // добавляем какой-нить класс для оформления
        )
);