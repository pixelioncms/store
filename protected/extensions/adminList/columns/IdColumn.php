<?php

/**
 * Column class to render ID column
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @package ext
 * @subpackage adminList.columns
 * @uses DataColumn
 */
class IdColumn extends DataColumn {

    public function getHeaderCellContent() {
        $this->headerHtmlOptions['width'] = '20px';
        parent::getHeaderCellContent();
    }

}