<?php

class UploadifyFile extends CFormModel {

    public $uploadifyFile;

    public function rules() {
        return array(
            array(
                'uploadifyFile',
                'file',
                'maxSize' => 1024 * 1024 * 1024,
                'types' => 'jpg, png, gif, txt',
            ),
        );
    }

}