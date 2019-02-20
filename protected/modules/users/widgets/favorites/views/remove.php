<?php

echo CHtml::link('','javascript:void(0)',array(
    'class'=>'favorites isset',
    'title'=>'Уже находится в фаворитах',
    'onClick'=>'fav_remove("'.$object_id.'","'.$favorite_id.'","'.get_class($model).'","'.$mod.'")'));

?>
