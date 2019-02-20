<?php


class SwfUploadAction extends CAction {

    public function run() {
        $basePath = Yii::getPathOfAlias('webroot.uploads.attachment');
        $basePathThumb = Yii::getPathOfAlias('webroot.uploads.attachment.thumbs');
        if (isset($_FILES['uploadifyFile'])) {
            $file = $_FILES['uploadifyFile'];
            $ext = CFileHelper::getExtension($file['name']);
            $rename = CMS::gen(10) . '.' . $ext;
            $result = Yii::app()->img->load($file['tmp_name'])
                    ->save($basePath . DS . $rename, false, 100)
                    ->thumb(200, 200)
                    ->save($basePathThumb . DS . $rename, false, 100);
            if (!$result) {
                echo 'Upload error.';
            } else {
                 die("Upload success");//$this->saveAttachment($rename);
            }
        } else {
            throw new CException(Yii::t(__CLASS__, "File not sent.", array()));
        }
        throw new CException(Yii::t(__CLASS__, 'Unknown error.', array()));
    }

    public function saveAttachment($filename) {
        $model = new AttachmentModel;
        $model->filename = $filename;
        if ($model->validate()) {
            $model->save(false, false);
            die("Upload success");
        }
    }

}