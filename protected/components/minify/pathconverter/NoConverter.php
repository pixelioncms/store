<?php


/**
 *
 * @copyright (c) 2018, Semenov Andrew
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @author Semenov Andrew <info@andrix.com.ua>
 *
 * @link http://pixelion.com.ua PIXELION CMS
 * @link http://andrix.com.ua Developer
 *
 */
Yii::import('app.minify.pathconverter.ConverterInterface');
class NoConverter implements ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($path)
    {
        return $path;
    }
}
