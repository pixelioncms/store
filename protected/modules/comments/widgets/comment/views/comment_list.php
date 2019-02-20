

<?php

$this->widget('ListView', array(
    'dataProvider' => $dataProvider,
    'id' => 'comment-list',
    'emptyText' => Yii::t('CommentsModule.default', 'NO_COMMENTS'),
    'itemView' => (Yii::app()->controller instanceof AdminController) ? '_view_backend' : '_view',
    'pager'=>array(
        'header'=>''
    )
));
?>  