function rating(id,mod,rate)
{
    var xhr;
   // var url = '/store/ajax/rateProduct/'+id;
   // var rating = $('input[name=rating_'+id+']:checked').val();
   // $('input[name=rating_'+id+']').rating('disable');
   if(xhr){
       xhr.abort();
   }
    xhr = $.ajax({
        url: '/store/ajax/rateProduct/'+id,
        data:{rating:rate},
        success:function(data){
            $('.rate-'+id).html(data)
        }
    });

}