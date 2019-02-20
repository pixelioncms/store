
<?php

$this->widget('ListView', array(
    //'ajaxUpdate' => true,
    'summaryText' => false,
    'id' => 'timeline-items',
    'dataProvider' => $data,
    //'enableHistory' => true,
    'itemView' => 'ext.blocks.timeline.views._view',
    'pagerCssClass' => 'text-center',
    'htmlOptions' => array('class' => 'list-group'),
    'afterAjaxUpdate' => "js:function(){
        $('[data-toggle=\"popover\"]').popover({trigger:'hover'});
        }",
));
?>