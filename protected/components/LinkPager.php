<?php

/**
 * LinkPager class file.
 * Данный класс для ListView
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CBasePager
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class LinkPager extends CBasePager {

    const CSS_FIRST_PAGE = 'page-item first';
    const CSS_LAST_PAGE = 'page-item last';
    const CSS_PREVIOUS_PAGE = 'page-item previous';
    const CSS_NEXT_PAGE = 'page-item next';
    const CSS_INTERNAL_PAGE = 'page-item';
    const CSS_HIDDEN_PAGE = 'page-item hidden';
    const CSS_SELECTED_PAGE = 'active';

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
     * @var string the CSS class for the selected page buttons. Defaults to 'selected'.
     * @since 1.1.11
     */
    public $selectedPageCssClass = self::CSS_SELECTED_PAGE;

    /**
     * @var integer maximum number of page buttons that can be displayed. Defaults to 10.
     */
    public $maxButtonCount = 10;

    /**
     * @var string the text label for the next page button. Defaults to 'Next &gt;'.
     */
    public $nextPageLabel;

    /**
     * @var string the text label for the previous page button. Defaults to '&lt; Previous'.
     */
    public $prevPageLabel;

    /**
     * @var string the text label for the first page button. Defaults to '&lt;&lt; First'.
     */
    public $firstPageLabel;

    /**
     * @var string the text label for the last page button. Defaults to 'Last &gt;&gt;'.
     */
    public $lastPageLabel;

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
        if ($this->nextPageLabel === null)
            $this->nextPageLabel = Html::icon('icon-arrow-right',array('title'=>Yii::t('app', 'PAGER_NEXT')));
        if ($this->prevPageLabel === null)
            $this->prevPageLabel = Html::icon('icon-arrow-left',array('title'=>Yii::t('app', 'PAGER_PREV')));
        if ($this->firstPageLabel === null)
            $this->firstPageLabel = Html::icon('icon-double-arrow-left',array('title'=>Yii::t('app', 'PAGER_FIRST')));
        if ($this->lastPageLabel === null)
            $this->lastPageLabel = Html::icon('icon-double-arrow-right',array('title'=>Yii::t('app', 'PAGER_LAST')));
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


    protected function createPageButtons() {
        if (($pageCount = $this->getPageCount()) <= 1)
            return array();

        list($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons = array();


        // first page

        if ($this->firstPageLabel) {
            $buttons[] = $this->createPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false, Yii::t('app', 'TITLE_PAGER_FIRST'));
        }
        // prev page
        if ($this->prevPageLabel) {
            if (($page = $currentPage - 1) < 0)
                $page = 0;
            $buttons[] = $this->createPageButton($this->prevPageLabel, $page, $this->previousPageCssClass, $currentPage <= 0, false, Yii::t('app', 'TITLE_PAGER_PREV'));
        }
        // internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i)
            $buttons[] = $this->createPageButton($i + 1, $i, $this->internalPageCssClass, false, $i == $currentPage, Yii::t('app', 'TITLE_PAGER_NUM', array('{num}' => $i + 1)));

        // next page
        if ($this->nextPageLabel) {
            if (($page = $currentPage + 1) >= $pageCount - 1)
                $page = $pageCount - 1;
            $buttons[] = $this->createPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false, Yii::t('app', 'TITLE_PAGER_NEXT'));
        }
        // last page
        if ($this->lastPageLabel) {
            $buttons[] = $this->createPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false, Yii::t('app', 'TITLE_PAGER_LAST'));
        }
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
        if ($hidden || $selected)
            $class.=' ' . ($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
        unset($_GET['ajax']); //Append by PANIX
        if (!$hidden) {
            return '<li class="' . $class . '">' . Html::link($label, $this->createPageUrl($page), array('title' => $title,'class'=>'page-link  ')) . '</li>';
        }
    }

    /**
     * @return array the begin and end pages that need to be displayed.
     */
    protected function getPageRange() {
        $currentPage = $this->getCurrentPage();
        $pageCount = $this->getPageCount();

        $beginPage = max(0, $currentPage - (int) ($this->maxButtonCount / 2));
        if (($endPage = $beginPage + $this->maxButtonCount - 1) >= $pageCount) {
            $endPage = $pageCount - 1;
            $beginPage = max(0, $endPage - $this->maxButtonCount + 1);
        }
        return array($beginPage, $endPage);
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

    public function num_pages($url, $numpages) {
        $pag = isset($_GET['pb']) ? intval($_GET['pb']) : 1;
        if ($numpages > 1) {
            $this->registerClientScript();
            $html = '';
            $html .= Html::openTag('div', array('class' => 'text-center'));
            $html .= Html::openTag('ul', array('class' => 'pagination'));
            if ($pag > 1) {
                $prevpage = $pag - 1;
                $html .= Html::openTag('li', array('class' => $this->firstPageCssClass));
                $html .= Html::link('&lt;&lt;', $url . '?pb=' . $prevpage);
                $html .= CHtml::closeTag('li');
            }
            for ($i = 1; $i < $numpages + 1; $i++) {
                $htmlClass = ($i == $pag) ? $this->internalPageCssClass . ' ' . $this->selectedPageCssClass : $this->internalPageCssClass;
                $html .= Html::openTag('li', array('class' => $htmlClass));
                if ($i == $pag) {
                    $html .= "<span title=\"$i\">$i</span>";
                } else {
                    if ((($i > ($pag - 4)) && ($i < ($pag + 4))) OR ( $i == $numpages) || ($i == 1))
                        $html .= Html::link($i, $url . '?pb=' . $i);
                }
                if ($i < $numpages) {
                    if (($i > ($pag - 5)) && ($i < ($pag + 4)))
                        $html .= " ";
                    if (($pag > 5) && ($i == 1))
                        $html .= " <span>...</span>";
                    if (($pag < ($numpages - 4)) && ($i == ($numpages - 1)))
                        $html .= "<span>...</span> ";
                }
                $html .= CHtml::closeTag('li');
            }
            //$this->maxButtonCount
            if ($pag < $numpages) {
                $nextpage = $pag + 1;
                $html .= Html::openTag('li', array('class' => $this->lastPageCssClass));
                $html .= Html::link('&gt;&gt;', $url . '?pb=' . $nextpage);
                $html .= CHtml::closeTag('li');
            }

            $html .= CHtml::closeTag('ul');
            $html .= CHtml::closeTag('div');
            return $html;
        }
    }

}
