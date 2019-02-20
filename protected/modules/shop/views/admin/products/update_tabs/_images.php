<div class="clearfix"></div>


<?= Html::hiddenField('redirect_hash', 0); ?>


<?php $this->widget('ext.attachment.AttachmentWidget',array(
    'model'=>$model,
    'skin'=>'default_fullwidth',
    'relationName'=>'images'
)); ?>

<div class="clearfix"></div>

