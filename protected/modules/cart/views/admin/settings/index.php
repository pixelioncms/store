
<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>

<script>
function open_manual(){
    common.ajax('/admin/cart/settings/manual', {}, function(data){
        $('#content_manual_block').toggleClass('hidden');
    
    $('#content_manual').html(data)
    });
}
</script>

<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Документация',
    'htmlOptions' => array('class' => 'fluid hidden','id'=>'content_manual_block')
));
?>
<div id="content_manual"></div>
<?php
Yii::app()->tpl->closeWidget();

?>