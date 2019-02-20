<div class="terms-conditions-page">
    <div class="row">
        <div class="col-md-12 terms-conditions">
            <h2 class="heading-title"><?= $model->title; ?></h2>
            <div class="">
                <?= Html::text($model->full_text); ?>
            </div>
        </div>
    </div>
</div>

<?php
if($model->attachments){
    foreach($model->attachments as $attachment){
        echo Html::image($attachment->getImageUrl('name','attachments.pages','100x100'),'',array('class'=>'img-thumbnail'));
    }
}
?>




