<?php

if (!$this->isAdded) {
    //if (!Yii::app()->getModule('wishlist')->enable_guest) {
   //     echo Html::link('<span>' . Yii::t('WishlistModule.default', 'BTN_WISHLIST', 0) . '</span>', array('/users/register'), $this->linkOptions);
    //} else {
        echo Html::link('<span>' . Yii::t('WishlistModule.default', 'BTN_WISHLIST', 0) . '</span>', 'javascript:wishlist.add(' . $this->pk . ');', $this->linkOptions);
   // }
} else {
  echo Html::link('<span>Уже добавлено</span>', 'javascript:void(0)', $this->linkOptions);
}