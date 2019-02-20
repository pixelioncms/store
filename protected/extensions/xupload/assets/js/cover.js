
function setCover(that,filename,pid){
        $.ajax({
            url:'/admin/projects/default/setCover',
            type:'POST',
            data:{
                file:filename,
                pid:pid
                },
            success:function(){
                common.removeLoader();
                $('.files tr').removeClass('active');
                $(that).parent().parent('tr').addClass('active');
            },
            beforeSend:function(){
                common.addLoader();
            }
        });
}
