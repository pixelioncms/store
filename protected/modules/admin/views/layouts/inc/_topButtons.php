<?php

if (!isset($this->topButtons)) {
    $moduleID = ucfirst(Yii::app()->controller->module->id);
    $controllerID = ucfirst(str_replace('admin/', '', Yii::app()->controller->id));

    $visible = (Yii::app()->user->openAccess("{$moduleID}.{$controllerID}.*") || Yii::app()->user->openAccess("{$moduleID}.{$controllerID}.Create") || Yii::app()->user->isSuperuser) ? true : false;
    if ($visible) {
        echo Html::link(Yii::t('app', 'CREATE', 0), array('create'), array('title' => Yii::t('app', 'CREATE', 0), 'class' => 'btn btn-success'));
    }
} else {
    if ($this->topButtons == true) {
        if (is_array($this->topButtons)) {
            echo '<div class="btn-group" role="group">';
            foreach ($this->topButtons as $button) {
                $visible = (isset($button['visible'])) ? $button['visible'] : false;
                if (isset($button['visible'])) {
                    $visible = $button['visible'];
                } else {
                    $visible = true;
                }
                if ($visible) {
                    if (isset($button['icon'])) {
                        $icon = '<i class="' . $button['icon'] . '"></i> ';
                        $label = '<span>'.$button['label'].'</span>';
                    } else {
                        $icon = '';
                        $label = $button['label'];
                    }
                    echo Html::link($icon .$label, $button['url'], $button['htmlOptions']);
                }
            }
            echo '</div>';
        }
    }
}
