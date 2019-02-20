/**
 * Common functions
 */


$(document).ready(function() {
    
    $('.spinner').spinner({
        max: 999,
        min: 1,
        icons: {
            down: "btn btn-default spinner-down",
            up: "btn btn-default spinner-up"
        },
        //клик по стрелочкам spinner
        spin: function( event, ui ) {

        },
        stop:function(event, ui ){
            //запрещаем ввод числа больше 999;
            if($(this).val() > 999) $(this).val(999);
        },
        change: function (event, ui) {

        }
    }); 
    
    // Hide flash messages block
    $(".flash_messages .close").click(function(){
        $(".flash_messages").fadeOut();
    });

    // Search box
    var searchQuery = $("#searchQuery");
    var defText = searchQuery.val();
    searchQuery.focus(function(){
        if($(this).val()==defText)
            $(this).val('');
    });
    searchQuery.blur(function(){
        if($(this).val()=='')
            $(this).val(defText);
    });

});



var shop = window.shop || {};
shop = {

};
