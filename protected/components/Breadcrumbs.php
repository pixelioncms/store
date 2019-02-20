<?php

/**
 * Хлебные крошки отображается список ссылок, указывающих на положение текущей страницы в весь сайт.
 *
 *
 *
 * For example, breadcrumbs like "Home > Sample Post > Edit" means the user is viewing an edit page
 * for the "Sample Post". He can click on "Sample Post" to view that page, or he can click on "Home"
 * to return to the homepage.
 *
 * To use CBreadcrumbs, one usually needs to configure its {@link links} property, which specifies
 * the links to be displayed. For example,
 *
 * <code>
 * $this->widget('Breadcrumbs', array(
 *     'links'=>array(
 *         'Sample post'=>array('post/view', 'id'=>12),
 *         'Edit',
 *     ),
 * ));
 * </code>
 *
 * Because breadcrumbs usually appears in nearly every page of a website, the widget is better to be placed
 * in a layout view. One can define a property "breadcrumbs" in the base controller class and assign it to the widget
 * in the layout, like the following:
 *
 * <code>
 * $this->widget('Breadcrumbs', array('links'=>$this->breadcrumbs));
 * </code>
 *
 * Тогда, в каждом скрипте вида необходимо лишь присвоить «breadcrumbs» свойство по мере необходимости.
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CWidget
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class Breadcrumbs extends CWidget
{


    public $hideCount = 2;
    /**
     * @var string Имя тега для крошки контейнера тега. По умолчанию 'div'.
     */
    public $tagName = 'ul';

    /**
     * @var array HTML атрибуты для крошки тега контейнера.
     */
    public $htmlOptions = array('class' => 'breadcrumbs');

    /**
     * @var boolean HTML кодировать ссылку labels. По умолчанию true.
     */
    public $encodeLabel = true;

    /**
     * @var string the first hyperlink in the breadcrumbs (called home link).
     * If this property is not set, it defaults to a link pointing to {@link CWebApplication::homeUrl} with label 'Home'.
     * If this property is false, the home link will not be rendered.
     */
    public $homeLink;

    /**
     * @var array list of hyperlinks to appear in the breadcrumbs. If this property is empty,
     * the widget will not render anything. Each key-value pair in the array
     * will be used to generate a hyperlink by calling CHtml::link(key, value). For this reason, the key
     * refers to the label of the link while the value can be a string or an array (used to
     * create a URL). For more details, please refer to {@link CHtml::link}.
     * If an element's key is an integer, it means the element will be rendered as a label only (meaning the current page).
     *
     * The following example will generate breadcrumbs as "Home > Sample post > Edit", where "Home" points to the homepage,
     * "Sample post" points to the "index.php?r=post/view&id=12" page, and "Edit" is a label. Note that the "Home" link
     * is specified via {@link homeLink} separately.
     *
     * <pre>
     * array(
     *     'Sample post'=>array('post/view', 'id'=>12),
     *     'Edit',
     * )
     * </pre>
     */
    public $links = array();

    /**
     * @var string String, specifies how each active item is rendered. Defaults to
     * "<a href="{url}">{label}</a>", where "{label}" will be replaced by the corresponding item
     * label while "{url}" will be replaced by the URL of the item.
     * @since 1.1.11
     */
    public $activeLinkTemplate = '<li><a href="{url}">{label}</a></li>';

    /**
     * @var string Строка, определяет, как каждый неактивным визуализируется элемент. По умолчанию
     * "<span>{label}</span>", where "{label}" will be replaced by the corresponding item label.
     * Note that inactive template does not have "{url}" parameter.
     * @since 1.1.11 Framework
     */
    public $inactiveLinkTemplate = '<span>{label}</span>';
    public $scheme = true;
    public $lastscheme = true;

    /**
     * @var string Разделитель между ссылками в хлебных крошках. По умолчанию ' &raquo; '.
     */
    public $separator = ' &raquo; ';

    /**
     * Renders the content of the portlet.
     */
    public function run()
    {

        if ($this->scheme) {
            $this->homeLink = '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">' . Html::link(Yii::t('zii', 'Home'), array('/main/default/index'), array('itemprop' => 'item')) . '<meta itemprop="position" content="1"></li>';
            $this->activeLinkTemplate = '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a href="{url}" itemprop="item"><span itemprop="name">{label}</span></a><meta itemprop="position" content="{count}" /></li>';
            $this->inactiveLinkTemplate = '<li class="breadcrumb-item active" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="item">{label}<meta itemprop="position" content="{count}" /></span></li>';
        } else {
            if (!$this->homeLink)
                $this->homeLink = '<li class="breadcrumb-item">' . Html::link(Yii::t('zii', 'Home'), array('/main/default/index'), array('class' => '')) . '</li>';
        }


        if (empty($this->links))
            return;


        if ($this->scheme) {
            $this->htmlOptions['itemtype'] = 'http://schema.org/BreadcrumbList';
            $this->htmlOptions['itemscope'] = 'itemscope';
        }

        echo Html::openTag($this->tagName, $this->htmlOptions) . "\n";
        $links = array();
        if ($this->homeLink === null) {
            $options = array();
            if ($this->scheme) {
                //  $options['itemprop'] = 'item';
            }
            $links[] = Html::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl, $options);
        } elseif ($this->homeLink !== false)
            $links[] = $this->homeLink;
        $count = 2;

        $r = array();
        $dlinks=array_slice($this->links,0,count($this->links));
        foreach ($this->links as $label => $url) {
            $r[]=$count;
            if (is_string($label) || is_array($url)) {
                $links[] = strtr($this->activeLinkTemplate, array(
                    '{url}' => Html::normalizeUrl($url),
                    '{label}' => $this->encodeLabel ? Html::encode($label) : $label,
                    '{count}' => $count,
                ));
            } else {
                $links[] = str_replace('{label}', $this->encodeLabel ? Html::encode($url) : $url, strtr($this->inactiveLinkTemplate, array(
                    '{count}' => $count
                )));
            }
            $count++;
        }


       // print_r($dlinks);
        foreach ($dlinks as $k=>$s){
           // unset($links[$k]);
          // echo $k;
        }
        if ($this->hideCount <= count($r)) {
           // echo ' z ' . $label;

        } else {
          //  echo ' b ';
        }



        echo implode($this->separator, $links);
        echo Html::closeTag($this->tagName);
    }

}
