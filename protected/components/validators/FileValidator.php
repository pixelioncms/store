<?php

/**
 * Валидатор специально для изображений если сохранять пустое поле.
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package app
 * @subpackage validators
 * @uses CFileValidator
 */
class FileValidator extends CFileValidator {

    protected function emptyAttribute($object, $attribute) {

    }

}