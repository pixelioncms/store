<?php

if ($model->productLabel) {
    if ($model->productLabel['class'] == 'new') {
        $color = 'green';
    } elseif ($model->productLabel['class'] == 'hit') {
        $color = 'purple';
    } else {
        $color = 'blue';
    }
    echo Html::tag('div', array('class' => "corner-{$this->position}-{$color} " . $model->productLabel['class']), '', true);
}


