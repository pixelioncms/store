
var control = window.fc || {};
control = {
    update:function(that, grid){
        console.log('update');
    },
    remove:function(that, grid){
        var obj = $(that);
        common.ajax(obj.attr('href'), {}, function(){
            $.fn.yiiListView.update(grid);
        });
    },
    switchChange:function(that, grid){
        var obj = $(that);
        common.ajax(obj.attr('href'), {}, function(){
            $.fn.yiiListView.update(grid);
        });
       
    }
}