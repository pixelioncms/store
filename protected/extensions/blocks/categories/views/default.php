<?php

$this->widget('zii.widgets.CMenu', array(
    'items' => $this->getMenuTree(),

    'htmlOptions' => array('class' => 'list-unstyled'),
    'activeCssClass'=>'active',
    'itemCssClass'=>'123',
));
?>

