<?php

if (!$this->isAdded) {
    if (Yii::app()->user->isGuest) {
        echo Html::link('', array('/users/register'), $this->linkOptions);
    } else {
        echo Html::link('', 'javascript:wishlist.add(' . $this->pk . ');', $this->linkOptions);
    }
}
