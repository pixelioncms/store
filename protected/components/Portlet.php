<?php

/**
 * Portlet is the base class for portlet widgets.
 *
 * A portlet displays a fragment of content, usually in terms of a block
 * on the side bars of a Web page.
 *
 * To specify the content of the portlet, override the {@link renderContent}
 * method, or insert the content code between the {@link CController::beginWidget}
 * and {@link Controller::endWidget} calls. For example,
 *
 * <code>
 * $this->beginWidget('Portlet');
 *     ...insert content here...
 * $this->endWidget();
 * </code>
 *
 * A portlet also has an optional {@link title}. One may also override {@link renderDecoration}
 * to further customize the decorative display of a portlet (e.g. adding min/max buttons).
 * 
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @package components
 * @since 1.1
 * @uses CWidget
 */
class Portlet extends CWidget {

    /**
     * @var string the tag name for the portlet container tag. Defaults to 'div'.
     */
    public $tagName = 'div';

    /**
     * @var array the HTML attributes for the portlet container tag.
     */
    public $htmlOptions = array('class' => 'panel panel-default');

    /**
     * @var string the title of the portlet. Defaults to null.
     * When this is not set, Decoration will not be displayed.
     * Note that the title will not be HTML-encoded when rendering.
     */
    public $title;

    /**
     * @var string the CSS class for the decoration container tag. Defaults to 'portlet-decoration'.
     */
    public $decorationCssClass = 'panel-heading';

    /**
     * @var string the CSS class for the portlet title tag. Defaults to 'portlet-title'.
     */
    public $titleCssClass = '';

    /**
     * @var string the CSS class for the content container tag. Defaults to 'portlet-content'.
     */
    public $contentCssClass = 'panel-body';

    /**
     * @var boolean whether to hide the portlet when the body content is empty. Defaults to true.
     * @since 1.1.4
     */
    public $hideOnEmpty = true;
    private $_openTag;

    /**
     * Initializes the widget.
     * This renders the open tags needed by the portlet.
     * It also renders the decoration, if any.
     */
    public function init() {
        $this->title = $this->getTitle();
        ob_start();
        ob_implicit_flush(false);
        if (isset($this->htmlOptions['id']))
            $this->id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $this->id;
        echo CHtml::openTag($this->tagName, $this->htmlOptions) . "\n";
        $this->renderDecoration();
        echo "<div class=\"{$this->contentCssClass}\">\n";

        $this->_openTag = ob_get_contents();
        ob_clean();
    }

    public function getTitle(){
        return 'Unknown widget name';
    }

    /**
     * Renders the content of the portlet.
     */
    public function run() {
        $this->renderContent();
        $content = ob_get_clean();
        if ($this->hideOnEmpty && trim($content) === '')
            return;
        echo $this->_openTag;
        echo $content;
        echo "</div>\n";
        echo Html::closeTag($this->tagName);
    }

    /**
     * Renders the decoration for the portlet.
     * The default implementation will render the title if it is set.
     */
    protected function renderDecoration() {
        if ($this->title !== null) {
            echo "<div class=\"{$this->decorationCssClass}\">\n";
            echo "<span class=\"{$this->titleCssClass}\">{$this->title}</span>\n";
            echo "</div>\n";
        }
    }

    /**
     * Renders the content of the portlet.
     * Child classes should override this method to render the actual content.
     */
    protected function renderContent() {
        
    }

}