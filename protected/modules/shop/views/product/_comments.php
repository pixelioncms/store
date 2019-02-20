<?php 

if (Yii::app()->hasModule('comments')) {
    $this->widget('mod.comments.widgets.comment.CommentWidget', array('model' => $model));
}
