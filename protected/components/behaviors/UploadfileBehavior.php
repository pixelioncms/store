<?php

/**
 * @version 1.0
 * @author Andrew S. <andrew.panix@gmail.com>
 * @name $attributes Array attributes model
 */
class UploadfileBehavior extends CActiveRecordBehavior
{

    public $dir;
    //public $attribute = 'filename';
    public $attributes = array();
    public $extensions = array('jpg', 'jpeg', 'png', 'gif');
    private $oldUploadFiles = array();

    public function attach($owner)
    {
        return parent::attach($owner);
    }

    public function beforeSave($event)
    {
        $owner = $this->getOwner();
        foreach ($this->attributes as $attribute) {
            if (isset($owner->{$attribute})) {
                $owner->{$attribute} = $this->uploadFile($attribute, (isset($this->oldUploadFiles[$attribute])) ? $this->oldUploadFiles[$attribute] : null);
            }
        }
        return parent::afterSave($event);
    }

    public function afterFind($event)
    {
        $owner = $this->getOwner();
        if ($owner->scenario == 'update') {
            foreach ($this->attributes as $attribute) {
                if (isset($owner->{$attribute})) {
                    $this->oldUploadFiles[$attribute] = $owner->{$attribute};
                }
            }
        }
    }

    public function getImageUrl($attribute, $size = '100x100', $options = array())
    {
        $owner = $this->getOwner();
        $attrname = $owner->{$attribute};

        if (!empty($attrname)) {
            if($size){
                return CMS::processImage($size, $attrname, $this->dir, $options);
            }else{
                return $this->getFilePath($attribute);
            }

        } else {
            return $imgSource = CMS::placeholderUrl(array('size' => $size));
        }
    }

    public function getFileUrl($attr, $absolute = false)
    {
        $owner = $this->getOwner();
        if (isset($owner->{$attr})) {
            if ($this->checkExistFile($attr)) {
                return ($absolute) ? $this->getFileAbsolutePath($attr) : $this->getFilePath($attr);
            }
        }
        return false;
    }

    private function checkExistFile($attr)
    {
        if (file_exists($this->getFileAbsolutePath($attr))) {
            return true;
        }
        return false;
    }

    public function getRemoveUrl($attribute)
    {
        if ($this->checkExistFile($attribute)) {

            $owner = $this->getOwner();
            $params = array();
            $params[] = 'removeFile';

            if ($owner->getUpdateUrl())
                $params['redirect'] = Yii::app()->createAbsoluteUrl($owner->getUpdateUrl());

            $params['attribute'] = $attribute;
            $params['key'] = $owner->getPrimaryKey();

            return Html::link(Html::icon('icon-delete') . ' ' . Yii::t('app', 'DELETE'), $params, array('class' => 'btn btn-sm btn-danger'));
        }
    }
    public function getImageBase64($attr)
    {
        $owner = $this->getOwner();
        if (isset($owner->{$attr})) {
            if ($this->checkExistFile($attr)) {
                $path = $this->getFileAbsolutePath($attr);
                $fileInfo = $this->getFileInfo($attr);
                if (in_array($fileInfo['extension'], array('jpg', 'jpeg', 'gif', 'png'))) {
                    $data = file_get_contents($path);
                    return 'data:image/' . $fileInfo['extension'] . ';base64,' . base64_encode($data);
                }
            }
        }
        return false;
    }
    public function getFileHtmlButton($attribute)
    {
        if ($this->checkExistFile($attribute)) {
            $fileInfo = $this->getFileInfo($attribute);
            $fancybox = false;
            $linkValue = Html::icon('icon-search') . ' Открыть файл';
            if (in_array($fileInfo['extension'], array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'svg'))) {
                $fancybox = true;
            }
            if ($fancybox){
                $targetClass = 'fancybox-popup-'  . $attribute;
                Yii::app()->controller->widget('ext.fancybox.Fancybox', array('target' => '.'.$targetClass));
            }

            return Html::link($linkValue, $this->getFileUrl($attribute), array('class' => 'btn btn-sm btn-primary ' . $targetClass)) . $this->getRemoveUrl($attribute);
        }
    }


    protected function getFileInfo($attribute)
    {
        if ($this->checkExistFile($attribute)) {
            return pathinfo($this->getFileAbsolutePath($attribute));
        }
        return false;
    }

    private function getFilePath($attr)
    {
        $replace = str_replace('.', '/', $this->dir);
        return "/uploads/{$replace}/" . $this->getOwner()->{$attr};
    }

    private function getFileAbsolutePath($attribute)
    {
        $owner = $this->getOwner();
        //$replace = str_replace('.', '/', $this->dir);
        if ($owner->{$attribute}) {
            return Yii::getPathOfAlias("webroot.uploads.{$this->dir}") . DS . $owner->{$attribute};
        } else {
            return false;
        }
    }

    private function uploadFile($attribute, $old_image = null)
    {
        $owner = $this->getOwner();
        $file = CUploadedFile::getInstance($owner, $attribute);
        $path = Yii::getPathOfAlias("webroot.uploads.{$this->dir}") . DS;


        if (isset($file)) {
            if ($old_image && file_exists($path . $old_image))
                unlink($path . $old_image);

            $newname = CMS::gen(10) . "." . $file->extensionName;
            if (in_array($file->extensionName, $this->extensions)) { //Загрузка для изображений
                $img = Yii::app()->img;
                $img->load($file->tempName);
                $img->save($path . $newname);
            } else {

                $file->saveAs($path . $newname);
            }
            $owner->{$attribute} = (string)$newname;
        } else {
            $owner->{$attribute} = (string)$old_image;
        }
        return $owner->{$attribute};
    }

}
