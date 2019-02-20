function favorites(pid, model,action,mod)
{
    var xhr;
    if(xhr){
        xhr.abort();
    }
    xhr = $.ajax({
        url: '/users/favorites/widget.favorite',
        type: "POST",
        data:{
            id:pid,
            model:model,
            mod:mod,
            token:common.token,
            action:action
        },
        success:function(data){
            $('#fav'+pid).html(data);
        },
        beforeSend:function(){
            $('#fav'+pid).html($('<div/>',{'class':'loading'}));
        }
    });

}


function fav_remove(model_id,pid,model,mod){

    var xhr;
    if(xhr){
        xhr.abort();
    }
    xhr = $.ajax({
        url: '/users/favorites/widget.favorite',
        type: "POST",
        data:{
            id:pid,
            model:model,
            mod:mod,
            action:'delete',
            token:common.token
        },
        success:function(data){
            $('#fav'+model_id).html(data);
        },
        beforeSend:function(){
            $('#fav'+model_id).html($('<div/>',{'class':'loading'}));
        }
    });

}


