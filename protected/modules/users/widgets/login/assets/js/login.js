function login(){
    $.ajax({
        url     : '/users/login',
        type    : 'POST',
        data    : $('#login-form').find('form').serialize(),
        success : function(result){
            $('#login-form').html(result);
           
        },
        error   : function(result){
            $('#login-form').html(result);
        }
    });
}

$(function(){
    $('#login-form form').keydown(function(event){ 
        if (event.which == 13) {
            // event.preventDefault();
            login();
        }
    });

    $('body').append($('<div/>',{
        'id':'login-form'
    }));

});