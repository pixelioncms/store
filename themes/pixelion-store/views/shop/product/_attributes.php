<?php

$this->widget('mod.shop.components.AttributesRender', array(
    'model' => $model,
    'list' => '_attributes_list',
    'htmlOptions' => array(
        'class' => 'attributes'
    ),
));

