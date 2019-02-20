<?php



        
        ?>
<script>  
            
            
    var tasks = <?php echo json_encode($response) ?>;

    function doTask(taskNum, next, i,num){
        var time = Math.floor(Math.random()); //*3000 default
        // $("#sended-result").prepend("<div class='senden-row"+i+"'>Ожидание...</div>");
                      
        setTimeout(function(){
            $.ajax({
                type:"POST",
                url:"/admin/shop/currency/updateProductPrice",
                data: {
                    "data":taskNum,
                    key:i
                },
                success:function(data){
                    //var response = $.parseJSON(data);
                    next();
                    $("#sended").text(i+1);


                    // $(".senden-row"+i).html("<div class='senden-row'><span style='color:#97AF32'></span> "+taskNum.name+" установленая новая цена: "+taskNum.newprice+"</div>");

                       
                },
                beforeSend:function(){
                    //  $(".senden-row"+i).text("Идет отправка...");
                    //$(".senden-row"+i).html("Идет отправка...");
                },
                complate:function(){
                    //$(".senden-row"+i).html("Готово..");

                }
            });
              
        },time)
    }

    function createTask(taskNum,i,num){
        return function(next){
            doTask(taskNum, next,i,num);
                         
        }
    }

    $(function(){
        $("#progress-send").html("Изменино <span id='sended'>0</span> из <span id='total'>"+tasks.length+"</span>");
        console.log(tasks.length);
    });
    for (var i = 0; i < tasks.length; i++){
        var num = i+1
        $(document).queue('tasks', createTask(tasks[i],i,num));
    }

    $(document).queue('tasks', function(){
        console.log("all done");
        $("#sended-result").prepend("<div><b>Готово!</b></div>");
    });

    $(document).dequeue('tasks');

                
                                    
</script>


<div class="widget grid4">
    <div class="whead">
        <h6><?= Yii::t('deliveryModule.site', 'DELIVERY_RESULT'); ?></h6>

        <div class="progress contentProgress hidden">
            <div class="bar barG" style="width:0;"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <div id="progress-send"></div>
        <div id="sended-result"></div>
        <div id="sended-row"></div>
    </div>
</div>





