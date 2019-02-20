<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $model->getForm();
Yii::app()->tpl->closeWidget();
?>


<script>
$(function(){
    
   $('#LanguageModel_flag_name').change(function(){
      console.log($(this).val()); 
        $('#flag_render').html('<img class="img-thumbnail" src="/uploads/language/'+$(this).val()+'" alt="" />');
      //flag_render
   });
   $('#LanguageModel_flag_name').change();
});
</script>