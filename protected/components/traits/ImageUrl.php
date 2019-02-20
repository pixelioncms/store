<?php

trait ImageUrl {

    /**
     * Get url to product image. Enter $size to resize image.
     * 
     * @param string $attr Model attribute
     * @param string $dir Folder name in uploads
     * @param string|false $size New size of the image. e.g. '150x150'
     * @param string $resize resize or adaptiveResize
     * @return string
     */
    public function getImageUrl($attr, $dir, $size = false, $resize = 'resize') {
        $redir = (strpos($dir, '.')) ? str_replace('.', '/', $dir) : $dir;
        Yii::import('ext.phpthumb.PhpThumbFactory');
        $attrname = $this->$attr;
        if (!empty($attrname)) {
            if ($size !== false) {
                return CMS::resizeImage($size, $attrname, $redir, $redir, $resize);
            }
        } else {
            return false;
        }
    }

    public function getOriginalImageUrl($attr, $dir) {
        $redir = (strpos($dir, '.')) ? str_replace('.', '/', $dir) : $dir;
        return "/uploads/{$redir}/" . $this->$attr;
    }

    /**
     * 
     * @param string $attr
     * @param string $dir Example "news",
     * @param string|false $size New size of the image. e.g. '50x50'
     * @param string|false $sizeLarge New size of the image. e.g. '500x500'
     * @return boolean
     */
    public function renderImage($attr, $dir, $size = '50x50', $sizeLarge = '800x600') {

        if (!$this->isNewRecord) {
            if (file_exists(Yii::getPathOfAlias("webroot.uploads.{$dir}") . DS . $this->$attr) && !empty($this->$attr)) {
                Yii::app()->controller->widget('ext.fancybox.Fancybox', array('target' => '.fancybox-img'));
                return Html::link(Html::image($this->getImageUrl($attr, $dir, $size), '', array('class' => 'img-thumbnail')), $this->getImageUrl($attr, $dir, $sizeLarge), array('class' => 'fancybox-img'));
            } else {
                return false;
            }
        }
    }

    public function renderImageText($attr, $dir, $size = '800x600') {
        if (!$this->isNewRecord) {
            if (file_exists(Yii::getPathOfAlias("webroot.uploads.{$dir}") . DS . $this->$attr) && !empty($this->$attr)) {
                Yii::app()->controller->widget('ext.fancybox.Fancybox', array('target' => '.fancybox-img'));
                return Html::link('<i class="icon-images"></i> ' . Yii::t('app', 'VIEW_IMG'), $this->getImageUrl($attr, $dir, $size), array('class' => 'fancybox-img'));
                // return '<a href="/uploads/' . $dir . '/' . $this->$attr . '" class="fancybox-img" title="' . $this->$attr . '"><i class="icon-images"></i> '.Yii::t('app','VIEW_IMG').'</a>';
            } else {
                return false;
            }
        }
    }

}
