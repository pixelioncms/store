<?php

echo Html::link('', 'javascript:void(0)', array(
    'class' => 'favorites',
    'title' => 'Добавить в избраное',
    'onClick' => 'favorites("' . $model->id . '","' . get_class($model) . '","add","' . $mod . '")'));
?>
