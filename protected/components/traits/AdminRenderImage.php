<?php

trait AdminRenderImage {

    /**
     * 
     * @param string $uploads_dir_alias Example "news"
     * @param string $attr
     * @return boolean
     */
    public function renderImage($uploads_dir_alias, $attr) {
        if (!$this->isNewRecord) {
            if (file_exists(Yii::getPathOfAlias("webroot.uploads.{$uploads_dir_alias}") . '/' . $this->$attr) && !empty($this->$attr)) {
                Yii::app()->controller->widget('ext.fancybox.Fancybox', array('target' => '.overview-image'));
                $dir = str_replace('.', '/', $uploads_dir_alias);
                return '<a href="/uploads/' . $dir . '/' . $this->$attr . '" class="overview-image" title="' . $this->$attr . '"><i class="icon-images"></i> '.Yii::t('app','VIEW_IMG').'</a>';
            } else {
                return false;
            }
        }
    }

}