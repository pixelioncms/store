<?php

class CoreController extends CController
{


    public function actionAttachment()
    {

        //header("Content-type: image/jpeg");
        // header("Content-type: image/jpeg");
        header('Last-Modified: ' . gmdate('r', time()));
        header('Expires: ' . gmdate('r', time() + 1800));
        header('Pragma: no-cache');
        header('Cache-Control: no-cache, must-revalidate');
        // $start = microtime(true);
        $id = Yii::app()->request->getParam('id');
        $size = Yii::app()->request->getParam('size');

        $model = AttachmentModel::model()->findByPk((int)$id);

        if (!$model)
            die('attachment model error.');

        $modelArray = explode('.', $model->model);
        $mod = $modelArray[1];
        $imgPath = $model->getOriginalUrl($model->dir, true);
        $img = Yii::app()->img;

        if (!file_exists($imgPath)) {
            $this->redirect(CMS::placeholderUrl(array('size' => $size)));
        }

        $img->load($imgPath);
        $configApp = Yii::app()->settings->get('app');
        $sizes = explode('x', $size);
        /*if ($size) {
            $img->resize((!empty($sizes[0])) ? $sizes[0] : false, (!empty($sizes[1])) ? $sizes[1] : true, true);
        }*/
        if ($size) {

            $img->resize((!empty($sizes[0])) ? $sizes[0] : 0, (!empty($sizes[1])) ? $sizes[1] : 0);
        }

        if ($configApp->attachment_wm_active && in_array($mod, explode(',', $configApp->attachment_wm_active))) {

            $offsetX = isset($configApp->attachment_wm_offsetx) ? $configApp->attachment_wm_offsetx : 10;
            $offsetY = isset($configApp->attachment_wm_offsety) ? $configApp->attachment_wm_offsety : 10;
            $corner = isset($configApp->attachment_wm_corner) ? $configApp->attachment_wm_corner : 4;
            $path = !empty($configApp->attachment_wm_path) ? $configApp->attachment_wm_path : Yii::getPathOfAlias('webroot.uploads') . '/watermark.png';
            if ($imageInfo = @getimagesize($path)) {
                $wm_width = (float)$imageInfo[0];
                $wm_height = (float)$imageInfo[1];
            }

            $toWidth = min($img->width, $wm_width);
            $wm_zoom = round($toWidth / $wm_width / 2, 1);
            $img->watermark($path, $offsetX, $offsetY, $corner, $wm_zoom);
        }


        $img->show();
        //Yii::log('Attachment Image Load: ' . (microtime(true) - $start) . ' sec.', 'info');
    }

    public function actionPlaceholder()
    {
        header("Content-type: image/png");

        // Dimensions
        $getsize = isset($_GET['size']) ? $_GET['size'] : '100x100';
        $dimensions = explode('x', $getsize);

        // Create image
        $image = imagecreate($dimensions[0], $dimensions[1]);

        // Colours
        $bg = isset($_GET['bg']) ? $_GET['bg'] : 'ccc';

        $bg = CMS::hex2rgb($bg);
        $opacityBg = (isset($_GET['bg'])) ? 0 : 127;
        //$setbg = imagecolorallocate($image, $bg['r'], $bg['g'], $bg['b']);
        $setbg = imagecolorallocatealpha($image, $bg['r'], $bg['g'], $bg['b'], $opacityBg);

        $fg = isset($_GET['fg']) ? $_GET['fg'] : '999';
        $fg = CMS::hex2rgb($fg);
        $setfg = imagecolorallocate($image, $fg['r'], $fg['g'], $fg['b']);

        $text = isset($_GET['text']) ? strip_tags($_GET['text']) : $getsize;
        $text = str_replace('+', ' ', $text);
        $padding = 10;

        $fontsize = $dimensions[0] / 2;


        if (strlen($text) == 4 && preg_match("/([A-Za-z]{1}[0-9]{3})$/i", $text)) {
            $text = '&#x' . $text . ';';
            $padding = 5;
            $fontsize = $dimensions[0];
            $font = Yii::getPathOfAlias('app.assets.fonts') . DS . 'Pixelion.ttf';
        } elseif ($text == 'PIXELION' || $text == 'pixelion') {
            $font = Yii::getPathOfAlias('app.assets.fonts') . DS . 'Pixelion.ttf';
        } else {
            $font = Yii::getPathOfAlias('app.assets.fonts') . DS . 'Exo2-Light.ttf';
        }

        $textBoundingBox = imagettfbbox($fontsize - $padding, 0, $font, $text);
        // decrease the default font size until it fits nicely within the image
        while (((($dimensions[0] - ($textBoundingBox[2] - $textBoundingBox[0])) < $padding) || (($dimensions[1] - ($textBoundingBox[1] - $textBoundingBox[7])) < $padding)) && ($fontsize - $padding > 1)) {
            $fontsize--;
            $textBoundingBox = imagettfbbox($fontsize - $padding, 0, $font, $text);
        }

        imagettftext($image, $fontsize - $padding, 0, ($dimensions[0] / 2) - (($textBoundingBox[2] + $textBoundingBox[0]) / 2), ($dimensions[1] / 2) - (($textBoundingBox[1] + $textBoundingBox[7]) / 2), $setfg, $font, $text);


        imagepng($image);
        imagedestroy($image);
        Yii::app()->end();
    }
}
