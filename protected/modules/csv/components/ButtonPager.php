<?php

/**
 * ButtonPager class file.
 * Данный класс для ListView
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CBasePager
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class ButtonPager extends CBasePager {

    const CSS_FIRST_PAGE = 'page-item first';
    const CSS_LAST_PAGE = 'page-item last';
    const CSS_PREVIOUS_PAGE = 'page-item previous';
    const CSS_NEXT_PAGE = 'page-item next';
    const CSS_INTERNAL_PAGE = 'page-item';
    const CSS_HIDDEN_PAGE = 'page-item hidden';


    /**
     * @var string the CSS class for the first page button. Defaults to 'first'.
     * @since 1.1.11
     */
    public $firstPageCssClass = self::CSS_FIRST_PAGE;

    /**
     * @var string the CSS class for the last page button. Defaults to 'last'.
     * @since 1.1.11
     */
    public $lastPageCssClass = self::CSS_LAST_PAGE;

    /**
     * @var string the CSS class for the previous page button. Defaults to 'previous'.
     * @since 1.1.11
     */
    public $previousPageCssClass = self::CSS_PREVIOUS_PAGE;

    /**
     * @var string the CSS class for the next page button. Defaults to 'next'.
     * @since 1.1.11
     */
    public $nextPageCssClass = self::CSS_NEXT_PAGE;

    /**
     * @var string the CSS class for the internal page buttons. Defaults to 'page'.
     * @since 1.1.11
     */
    public $internalPageCssClass = self::CSS_INTERNAL_PAGE;

    /**
     * @var string the CSS class for the hidden page buttons. Defaults to 'hidden'.
     * @since 1.1.11
     */
    public $hiddenPageCssClass = self::CSS_HIDDEN_PAGE;


    /**
     * @var string the text shown before page buttons. Defaults to 'Go to page: '.
     */
    public $header;


    /**
     * @var string the text shown after page buttons.
     */
    public $footer = '';

    /**
     * @var mixed the CSS file used for the widget. Defaults to null, meaning
     * using the default CSS file included together with the widget.
     * If false, no CSS file will be used. Otherwise, the specified CSS file
     * will be included when using this widget.
     */
    public $cssFile;

    /**
     * @var array HTML attributes for the pager container tag.
     */
    public $htmlOptions = array();

    /**
     * Initializes the pager by setting some default property values.
     */
    public function init() {
        if ($this->header === null)
            $this->header = Yii::t('yii', 'Go to page: ');

        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'yiiPager';
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run() {
        $this->registerClientScript();
        $buttons = $this->createPageButtons();
        if (empty($buttons))
            return;
        echo $this->header;
        echo Html::tag('ul', $this->htmlOptions, implode("\n", $buttons));
        echo $this->footer;
    }

    /**
     * Creates the page buttons.
     * @return array a list of page buttons (in HTML code).
     */
    protected function createPageButtons() {
        $pageCount = $this->getPageCount();
        for ($i = 0; $i <= $pageCount-1; ++$i)
            $buttons[] = $this->createPageButton($i + 1, $i, $this->internalPageCssClass, false, $i, Yii::t('app', 'TITLE_PAGER_NUM', array('{num}' => $i + 1)));

        return $buttons;
    }


    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button.
     * @param boolean $hidden whether this page button is visible
     * @param boolean $selected whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label, $page, $class, $hidden, $selected, $title) {
        if (!$hidden) {
            return '<li class="' . $class . '">' . Html::submitButton($label, array('name'=>'page','value'=>$label,'title' => $title,'class'=>'page-link')) . '</li>';
        }
    }
    /**
     * Registers the needed client scripts (mainly CSS file).
     */
    public function registerClientScript() {
        if ($this->cssFile !== false)
            self::registerCssFile($this->cssFile);
    }

    /**
     * Registers the needed CSS file.
     * @param string $url the CSS URL. If null, a default CSS URL will be used.
     */
    public static function registerCssFile($url = null) {
        if ($url === null)
            $url = CHtml::asset(Yii::getPathOfAlias('system.web.widgets.pagers.pager') . '.css');
        Yii::app()->getClientScript()->registerCssFile($url);
    }
}
