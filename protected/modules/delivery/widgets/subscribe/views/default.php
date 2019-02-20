<script>
    function send(formid, reload){
        var str = $(formid).serialize();
        $.ajax({
            url: $(formid).attr('action'),
            type: 'POST',
            data: str,
            success: function(data){
                $(reload).html(data);
            },
            complete: function(){

            } 
        });
    }
</script>
<div class="newsletter">
<h3 class="section-title"><?= Yii::t('SubscribeWidget.default','WGT_NAME')?></h3>
<div class="sidebar-widget-body outer-top-xs" id="side-subscribe">
    <?php echo $this->render('_subscribe', array('model' => $model)); ?>

</div>
</div>





