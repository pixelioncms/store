<?php

/**
 * Html class
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @package app
 * @uses CHtml
 * @copyright (c) 2016, Andrew Semenov
 * @link http://pixelion.com.ua PIXELION CMS
 */
class Html extends CHtml
{


    /**
     * Add xhr.abort()
     *
     * @param string $event
     * @param array $htmlOptions
     */
    protected static function clientChange($event, &$htmlOptions)
    {
        if (!isset($htmlOptions['submit']) && !isset($htmlOptions['confirm']) && !isset($htmlOptions['ajax']))
            return;

        if (isset($htmlOptions['live'])) {
            $live = $htmlOptions['live'];
            unset($htmlOptions['live']);
        } else
            $live = self::$liveEvents;

        if (isset($htmlOptions['return']) && $htmlOptions['return'])
            $return = 'return true';
        else
            $return = 'return false';

        if (isset($htmlOptions['on' . $event])) {
            $handler = trim($htmlOptions['on' . $event], ';') . ';';
            unset($htmlOptions['on' . $event]);
        } else
            $handler = '';

        if (isset($htmlOptions['id']))
            $id = $htmlOptions['id'];
        else
            $id = $htmlOptions['id'] = isset($htmlOptions['name']) ? $htmlOptions['name'] : self::ID_PREFIX . self::$count++;

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        if (isset($htmlOptions['submit'])) {
            $cs->registerCoreScript('yii');
            $request = Yii::app()->getRequest();
            if ($request->enableCsrfValidation && isset($htmlOptions['csrf']) && $htmlOptions['csrf'])
                $htmlOptions['params'][$request->csrfTokenName] = $request->getCsrfToken();
            if (isset($htmlOptions['params']))
                $params = CJavaScript::encode($htmlOptions['params']);
            else
                $params = '{}';
            if ($htmlOptions['submit'] !== '')
                $url = CJavaScript::quote(self::normalizeUrl($htmlOptions['submit']));
            else
                $url = '';
            $handler .= "jQuery.yii.submitForm(this,'$url',$params);{$return};";
        }

        if (isset($htmlOptions['ajax']))
            $handler .= self::ajax($htmlOptions['ajax'], $id) . "{$return};";

        if (isset($htmlOptions['confirm'])) {
            $confirm = 'confirm(\'' . CJavaScript::quote($htmlOptions['confirm']) . '\')';
            if ($handler !== '')
                $handler = "if($confirm) {" . $handler . "} else return false;";
            else
                $handler = "return $confirm;";
        }

        $xhr = ($id) ? "var xhr{$id};" : '';
        if ($live) {
            $cs->registerScript('Yii.CHtml.#' . $id, "{$xhr} jQuery('body').on('$event','#$id',function(){{$handler}});");
        } else {
            $cs->registerScript('Yii.CHtml.#' . $id, "{$xhr} jQuery('#$id').on('$event', function(){{$handler}});");
        }
        unset($htmlOptions['params'], $htmlOptions['submit'], $htmlOptions['ajax'], $htmlOptions['confirm'], $htmlOptions['return'], $htmlOptions['csrf']);
    }

    /**
     * Add for xhr.abort()
     *
     * @param string $text
     * @param mixed $url
     * @param array $ajaxOptions
     * @param array $htmlOptions
     * @return string
     */
    public static function ajaxLink($text, $url, $ajaxOptions = array(), $htmlOptions = array())
    {
        if (!isset($htmlOptions['href']))
            $htmlOptions['href'] = '#';
        $ajaxOptions['url'] = $url;
        $htmlOptions['ajax'] = $ajaxOptions;
        self::clientChange('click', $htmlOptions);
        return self::tag('a', $htmlOptions, $text);
    }

    /**
     * Add xhr.abort()
     *
     * @param array $options
     * @param bool $id
     * @return string
     */
    public static function ajax($options, $id = false)
    {

        Yii::app()->getClientScript()->registerCoreScript('jquery');
        if (!isset($options['url']))
            $options['url'] = new CJavaScriptExpression('location.href');
        else
            $options['url'] = self::normalizeUrl($options['url']);
        if (!isset($options['cache']))
            $options['cache'] = false;
        if (!isset($options['data']) && isset($options['type']))
            $options['data'] = new CJavaScriptExpression('jQuery(this).parents("form").serialize()');
        foreach (array('beforeSend', 'complete', 'error', 'success') as $name) {
            if (isset($options[$name]) && !($options[$name] instanceof CJavaScriptExpression))
                $options[$name] = new CJavaScriptExpression($options[$name]);
        }
        if (isset($options['update'])) {
            if (!isset($options['success']))
                $options['success'] = new CJavaScriptExpression('function(html){jQuery("' . $options['update'] . '").html(html)}');
            unset($options['update']);
        }
        if (isset($options['replace'])) {
            if (!isset($options['success']))
                $options['success'] = new CJavaScriptExpression('function(html){jQuery("' . $options['replace'] . '").replaceWith(html)}');
            unset($options['replace']);
        }

        if ($id) {
            $xhr = "
            if(xhr{$id} && xhr{$id}.readyState != 4){
                xhr{$id}.onreadystatechange = null;
                xhr{$id}.abort();
            }
            xhr{$id} = jQuery.ajax(" . CJavaScript::encode($options) . ");";
        } else {
            $xhr = "jQuery.ajax(" . CJavaScript::encode($options) . ");";
        }
        return $xhr;
    }

    public static function link($text, $url = '#', $htmlOptions = array())
    {
        if (!is_array($url)) {
            if (!isset($htmlOptions['rel'])) {
                if (preg_match('%^((https?://)|(www\.)|(//))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i', $url)) {
                    $htmlOptions['rel'] = 'nofollow';
                }
            }
        }
        //Todo: пересмотреть Html::link

        if (!Yii::app()->controller->isAdminController) {
            if (Yii::app()->user->isEditMode) {
                $options = array();
                if (isset($htmlOptions['class'])) {
                    $options['class'] = $htmlOptions['class'];
                } else {

                }
                return parent::tag('span', $options, $text, true);
            }
        }

        return parent::link($text, $url, $htmlOptions);
    }

    public static function tel($phone, $htmlOptions = array())
    {
        return self::link($phone, 'tel:' . preg_replace('/[^0-9+]/', '', $phone), $htmlOptions);
    }

    public static function viber($number, $htmlOptions = array())
    {
        if(!isset($htmlOptions['target']))
            $htmlOptions['target']='_blank';

        if(!isset($htmlOptions['rel']))
            $htmlOptions['rel']='nofollow';

        return self::link($number, 'viber://add?number=' . preg_replace('/[^0-9]/', '', $number), $htmlOptions);
    }

    public static function icon($name, $htmlOptions = array())
    {
        if ($name) {
            if (isset($htmlOptions['class'])) {
                $htmlOptions['class'] = $name . ' ' . $htmlOptions['class'];
            } else {
                $htmlOptions['class'] = $name;
            }
        }
        return CHtml::tag('i', $htmlOptions, '', true);
    }

    public static function activeLabelEx($model, $attribute, $htmlOptions = array())
    {
        $realAttribute = $attribute;
        self::resolveName($model, $attribute); // strip off square brackets if any
        $htmlOptions['required'] = $model->isAttributeRequired($attribute);
        return self::activeLabel($model, $realAttribute, $htmlOptions);
    }

    public static function activeLabel($model, $attribute, $htmlOptions = array())
    {
        $inputName = self::resolveName($model, $attribute);
        if (isset($htmlOptions['for'])) {
            $for = $htmlOptions['for'];
            unset($htmlOptions['for']);
        } else
            $for = self::getIdByName($inputName);
        if (isset($htmlOptions['label'])) {
            if (($label = $htmlOptions['label']) === false)
                return '';
            unset($htmlOptions['label']);
        } else
            $label = $model->getAttributeLabel($attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($htmlOptions);
        return self::label($label, $for, $htmlOptions);
    }

    /**
     * HTML and word filter
     *
     * @param string $message
     * @param boolean $cut Обрезать текст. true|false
     * @return string
     */
    public static function text($message, $cut = false)
    {
        $config = Yii::app()->settings->get('app');
        //if (!$mode)
        //  $message = strip_tags(urldecode($message));
        //$message = htmlspecialchars(trim($message), ENT_QUOTES);
        // $message=html_entity_decode(htmlentities($message));
        if ($config->censor) {
            $censor_l = explode(",", $config->censor_array);
            foreach ($censor_l as $val)
                $message = preg_replace("#" . $val . "#iu", $config->censor_replace, $message);
        }

        return self::highlight($message, $cut);
    }

    /**
     *
     * @param string $text
     * @param boolean $cut
     * @return string
     */
    public static function highlight($text, $cut = false)
    {
        $params = (Yii::app()->request->getParam('word')) ? Yii::app()->request->getParam('word') : Yii::app()->request->getParam('tag');
        if ($params) {
            if ($cut) {
                $pos = max(mb_stripos($text, $params, null, Yii::app()->charset) - 100, 0);
                $fragment = mb_substr($text, $pos, 200, Yii::app()->charset);
            } else {
                $fragment = html_entity_decode(htmlentities($text));
            }
            if (is_array($params)) {
                foreach ($params as $k => $w) {
                    $fragment = str_replace($w, '<span class="highlight-word">' . $w . '</span>', $fragment);
                }
                $highlighted = $fragment;
            } else {
                $highlighted = str_replace($params, '<span class="highlight-word">' . $params . '</span>', $fragment);
            }
        } else {
            $highlighted = $text;
        }
        return $highlighted;
    }

    public static function bootstrap_badge($value, $options = array())
    {
        return CHtml::tag('span', $options, $value, true);
    }


    public static function css($text, $media = '')
    {
        if ($media !== '')
            $media = ' media="' . $media . '"';
        return "<style {$media}>\n/*<![CDATA[*/\n{$text}\n/*]]>*/\n</style>";
    }

    public static function cssFile($url, $media = '')
    {
        return CHtml::linkTag('stylesheet', null, $url, $media !== '' ? $media : null);
    }

    public static function script($text, array $htmlOptions = array())
    {
        $defaultHtmlOptions = array(// 'type'=>'text/javascript',
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        return self::tag('script', $htmlOptions, "\n/*<![CDATA[*/\n{$text}\n/*]]>*/\n");
    }

    public static function scriptFile($url, array $htmlOptions = array())
    {
        $defaultHtmlOptions = array(
            //'type'=>'text/javascript',
            'src' => $url
        );
        $htmlOptions = array_merge($defaultHtmlOptions, $htmlOptions);
        return self::tag('script', $htmlOptions, '');
    }

    public static function clipboard($text)
    {
        //Yii::app()->clientScript->registerFile();
        $id = 'clipboard-' . md5($text);
        return Html::script("common.clipboard('#{$id}');") . Html::tag('span', array('id' => $id, 'data-clipboard-text' => $text), $text);
    }
}
