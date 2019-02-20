$(function(){
    
    var cook = 'visible';
    var cookName = 'admin-panel';
    var nameClass = 'hidden';
    
    if($.cookie(cookName)!=null){
       /* if($.cookie(cookName)==nameClass){
            $('#admin-panel').addClass(nameClass);
        }else{
            $('#admin-panel').removeClass(nameClass);
        }*/
    }
    $('#panel').click(function(){
        if($('#admin-panel').hasClass(nameClass)){
            cook = 'visible';
        }else{
            cook = 'hidden';
        }
        $('#admin-panel').toggleClass(nameClass);
        $.cookie(cookName,cook,{
            expires:30,
            path: '/'
        });
    })
});
function editmode(user_id) {


    var em = ($('#ap-edit_mode').is(':checked')) ? 1 : 0;
    $.ajax({
        url: '/admin/users/default/update?id=' + user_id,
        type: 'POST',
        data: {'User[edit_mode]': em, json: true},
        success: function (data) {
            if(data.status == 'success'){
                common.notify(data.message, data.status);
                //location.reload();
                setTimeout(location.reload.bind(location), 1000);
            }

        }
    })
}


