<?php

if (!$this->isAdded) {
echo Html::link(Yii::t('CompareModule.default', 'BTN_COMPARE',1), 'javascript:compare.add(' . $this->pk . ');', $this->linkOptions);
     }else{
         echo 'already compare';
     }