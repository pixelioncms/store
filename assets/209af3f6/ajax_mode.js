/**
 * AJAX MODE
 */
var gridID = 'shop-products';
    
function updateGrid(uri){
    if(window.History.enabled) {
        var url = uri.split('?'),
        params = $.deparam.querystring('?'+ (url[1] || ''));

        delete params['ajax'];
        window.History.pushState(null, document.title, decodeURIComponent($.param.querystring(url[0], params)));
    } else {
        $.fn.yiiListView.update(gridID, {
            url: uri
        });
    }
}
function applyCategorySorter(obj){
    console.log(obj)
    
}

function updatefilter(){
    $.ajax({
       url:'/shop/updateFilters',
       type:'GET',
       success:function(data){
           $('.block-filters').html(data);
       }
    });
}
    
$(function(){

    $('.block-filters input').change(function(){
        //checkUrl();
        updatefilter();
        updateGrid($(this).attr('data-url'));
    });
    
    $('#shop-sort select').change(function(){
       // checkUrl();
        updateGrid($(this).attr('data-url'));
    });
    
    $('#shop-sort-view a').on('click',function(){
        updateGrid($(this).attr('href'));
        return false;
    });
    
    function checkUrl(){
        var dataArr = $('.block-filters form').serialize();
        //dataArr+='&baseURL='+categoryFullUrl;
        //dataArr+='&ajax=true';
        //dataArr+='&'+$('#shop-sort form').serialize();
        //var formData = JSON.stringify(jQuery('.block-filters form').serializeArray());
        var formData = JSON.parse(JSON.stringify($('.block-filters form').serializeArray()))
$.each(formData, function( index, value ) {
console.log(value['name']);
});
        console.log(formData);

    }
    
    
    function checkUrlOLD(){
        var dataArr = $('.block-filters form').serialize();
        dataArr+='&baseURL='+categoryFullUrl;
        dataArr+='&ajax=true';
        dataArr+='&'+$('#shop-sort form').serialize();
        $.ajax({
            type:'POST',
            url:'/shop/test',
            data:dataArr,
            dataType:'json',
            success:function(response){
                console.log(response);
                updateGrid(response.url);
            }
        });
    }

});