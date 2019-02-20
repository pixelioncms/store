<?php

trait TraitUpload
{


    public function getImageUrl($dir, $size = '100x100', $options = array())
    {
        $owner = $this->getOwner();
        $redir = (strpos($dir, '.')) ? str_replace('.', '/', $dir) : $dir;
        $attrname = (isset($this->name)) ? $this->name : $owner->{$this->attribute};
        if (!empty($attrname)) {
            if ($size !== false) {
                //print_r($this->behaviors());die;
                return $this->assetImage($size, $attrname, 'attachments.' . $dir, 'attachments/' . $redir, $options);
            }
        } else {
            return false;
        }
    }


    public function getOriginalUrl($dir, $absolute = false)
    {
        $owner = $this->getOwner();
        $attrname = (isset($this->name)) ? $this->name : $owner->{$this->attribute};
        if (!$absolute) {
            $redir = (strpos($dir, '.')) ? str_replace('.', '/', $dir) : $dir;
            return "/uploads/attachments/{$redir}/" . $attrname;
        } else {
            return Yii::getPathOfAlias("webroot.uploads.attachments.{$dir}") . DS . $attrname;
        }
    }



    public function assetImage($size, $filename, $uploadAlias, $assetsAlias, $options = array())
    {



        $offsetX = isset($this->attachmentAttributes['watermark_offsetX']) ? $this->attachmentAttributes['watermark_offsetX'] : 10;
        $offsetY = isset($this->attachmentAttributes['watermark_offsetY']) ? $this->attachmentAttributes['watermark_offsetY'] : 10;
        $corner = isset($this->attachmentAttributes['watermark_corner']) ? $this->attachmentAttributes['watermark_corner'] : 4;
        $watermark_path = isset($this->attachmentAttributes['watermark_path']) ? $this->attachmentAttributes['watermark_path'] : Yii::getPathOfAlias('webroot.uploads') . '/watermark.png';



        $thumbPath = Yii::getPathOfAlias("webroot.assets.{$assetsAlias}.{$size}");
        if (!file_exists($thumbPath)) {
            mkdir($thumbPath, 0777, true);
        }
        // Path to source image
        $fullPath = Yii::getPathOfAlias("webroot.uploads.{$uploadAlias}") . DS . $filename;
        if (!file_exists($fullPath)) {
            // return CMS::placeholderUrl(array('size' => $size));
        }
        // Path to thumb
        $thumbPath = $thumbPath . DS . $filename;

        if (!file_exists($thumbPath)) {
            $sizes = explode('x', $size);



            $optionResizeProportional = (isset($options['resize'])) ? false : true;

            $img = Yii::app()->img;
            $img->load($fullPath);
            if (isset($this->attachmentAttributes['watermark']) ? $this->attachmentAttributes['watermark'] : false) {

                $img->watermark($watermark_path, $offsetX, $offsetY, $corner, false);
            }

            $img->resize((!empty($sizes[0]))?$sizes[0]:false, (!empty($sizes[1]))?$sizes[1]:false, $optionResizeProportional);
            $img->save($thumbPath);

        } else {
            //
        }
        $replace = str_replace('.', '/', $assetsAlias);
        return "/assets/{$replace}/{$size}/" . $filename;
    }
}
