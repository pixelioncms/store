<?php

Yii::import('zii.widgets.CMenu');

class GridCheckboxDropDown extends CMenu {
    public $itemCssClass = 'nav-item';

    protected function renderMenuItem($item) {
        $icon = (isset($item['icon'])) ? CHtml::tag('i', array('class' => $item['icon']), '', true) . ' ' : '';
        if (isset($item['url'])) {
            $label = $this->linkLabelWrapper === null ? $item['label'] : CHtml::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);

           if(isset($item['linkOptions'])){
               //$linkOptions['class'] = 'nav-link';
               //array_push($item['linkOptions'],);
               if(!isset($item['linkOptions']['class'])){
                   $item['linkOptions'] = CMap::mergeArray($item['linkOptions'],array('class'=>'nav-link'));
               }

           }else{
               $item['linkOptions']['class'] = 'nav-link';
           }


            return CHtml::link($icon . $label, $item['url'], $item['linkOptions']);
        } else
            return CHtml::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : array(), $icon . $item['label']);
    }

}
