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
interface ConverterInterface
{
    /**
     * Convert file paths.
     *
     * @param string $path The path to be converted
     *
     * @return string The new path
     */
    public function convert($path);
}
