function like(type,obj){
    var json = $.parseJSON(obj);
    $.ajax({
        type:'POST',
        dataType:'json',
        url:'/ajax/like.action',
        data:{
            token:common.token,
            m:json.m,
            object_id:json.object_id,
            type:type
        },
        success:function(data){
            if(data.success){
                var wgt = $('#widget-like-'+data.object_id);
                if(data.status){
                    wgt.addClass(data.status);
                }else{
                    wgt.removeClass('widget-like-status-down');
                    wgt.removeClass('widget-like-status-up');
                }
                wgt.removeClass('loading');
                wgt.find('.like-counter').html(data.count);
                wgt.find('a').attr('href','javascript:void(0)');
                $.jGrowl(data.message);
            } else {
                $.jGrowl(data.message);
            }
        },
        beforeSend:function(){
            $('#widget-like-'+json.object_id).addClass('loading'); 
        }
    });
}

/**
 * Список кто проголосовал и за что!
 * В разработке
 */
$(function(){
    $('div.widget-like2').hover(function(e){
        var _id = $(this).attr('id');
        $.ajax({
            type:'POST',
            dataType:'json',
            url:'/ajax/like.list',
            data:{
            },
            success:function(data){
                $('#'+_id).removeClass('loading'); 
                $('#'+_id).html(data);
            },
            beforeSend:function(){
                $('#'+_id).addClass('loading'); 
            }
        });
    });
});
