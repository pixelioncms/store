<?php

class ClientScript extends CClientScript
{
    public $title;
    public $coreScriptPosition=self::POS_END;
    public $minify = false;
    //public $defaultScriptFilePosition=self::POS_END;
   // public $defaultScriptPosition=self::POS_END;

    /**
     * Register seo title
     * @param $content
     * @return $this
     */
    public function registerTitleTag($content)
    {
        $params = func_get_args();



        if (Yii::app()->request->getParam('page')) {
            $content .= Yii::t('SeoModule.default', 'SEO_PAGE_NUM', array('{n}' => Yii::app()->request->getParam('page')));
        }
        if (Yii::app()->settings->get('seo', 'enable_title_name') && $content) {
            $content .= ' ' . Yii::app()->settings->get('seo', 'separation') . ' ' . Yii::app()->settings->get('app', 'site_name');
        } else {
            $content .= ' ' . Yii::app()->settings->get('app', 'site_name');
        }



        $this->title = $content;
        Yii::log('registerTitleTag'.$content, 'info', 'application');
        $this->recordCachingAction('clientScript', 'registerTitleTag', $params);
        return $this;
    }

    public function registerCssFile($url, $media = '')
    {
        if ($this->minify)
            $url = $this->minifyCssFile($url);


        $this->hasScripts = false;
        $this->cssFiles[$url] = $media;
        $params = func_get_args();

        $this->recordCachingAction('clientScript', 'registerCssFile', $params);
        return $this;
    }

    public function registerScriptFile($url, $position = null, array $htmlOptions = array())
    {
        if ($this->minify)
            $url = $this->minifyJsFile($url);

        $params = func_get_args();
        if ($position === null)
            $position = $this->defaultScriptFilePosition;
        $this->hasScripts = true;
        if (empty($htmlOptions))
            $value = $url;
        else {
            $value = $htmlOptions;
            $value['src'] = $url;
        }
        $this->scriptFiles[$position][$url] = $value;
        $this->recordCachingAction('clientScript', 'registerScriptFile', $params);
        return $this;
    }

    public function registerCss($id, $css, $media = '')
    {
        $this->hasScripts = true;
        if ($this->minify)
            $css = $this->minifyCss($css);
        $this->css[$id] = array($css, $media);
        $params = func_get_args();
        $this->recordCachingAction('clientScript', 'registerCss', $params);
        return $this;
    }

    public function registerScript($id, $script, $position = null, array $htmlOptions = array())
    {
        $params = func_get_args();

        if ($position === null)
            $position = $this->defaultScriptPosition;
        $this->hasScripts = true;


        if ($this->minify)
            $script = $this->minifyJs($script);

        if (empty($htmlOptions))
            $scriptValue = $script;
        else {
            if ($position == self::POS_LOAD || $position == self::POS_READY)
                throw new CException(Yii::t('yii', 'Script HTML options are not allowed for "CClientScript::POS_LOAD" and "CClientScript::POS_READY".'));
            $scriptValue = $htmlOptions;
            $scriptValue['content'] = $script;
        }


        $this->scripts[$position][$id] = $scriptValue;
        if ($position === self::POS_READY || $position === self::POS_LOAD)
            $this->registerCoreScript('jquery');


        $this->recordCachingAction('clientScript', 'registerScript', $params);
        return $this;
    }

    public function renderCoreScripts()
    {
        if ($this->coreScripts === null)
            return;
        $cssFiles = array();
        $jsFiles = array();
        //$position = $this->coreScriptPosition;
        foreach ($this->coreScripts as $name => $package) {
            $baseUrl = $this->getPackageBaseUrl($name);

            $position = (isset($package['position'])) ? $package['position'] : $this->coreScriptPosition;
            if (!empty($package['js'])) {
                foreach ($package['js'] as $js) {
                    //$jsFiles[$baseUrl . '/' . $js] = $baseUrl . '/' . $js;
                    if (!isset($package['jsOptions'])) {
                        $value = $baseUrl . '/' . $js;
                    } else {
                        $value = $package['jsOptions'];
                        $value['src'] = $baseUrl . '/' . $js;
                    }

                    $jsFiles[$baseUrl . '/' . $js] = $value;

                    $this->scriptFiles[$position][$baseUrl . '/' . $js] = array('src'=>$baseUrl . '/' . $js);
                }
            }
            if (!empty($package['css'])) {
                foreach ($package['css'] as $css)
                    $cssFiles[$baseUrl . '/' . $css] = '';
            }
        }
        // merge in place
        if ($cssFiles !== array()) {
            foreach ($this->cssFiles as $cssFile => $media)
                $cssFiles[$cssFile] = $media;
            $this->cssFiles = $cssFiles;
        }

        if ($jsFiles !== array()) {
            if (isset($this->scriptFiles[$this->coreScriptPosition])) {
                foreach ($this->scriptFiles[$this->coreScriptPosition] as $url => $value) {
                    $jsFiles[$url] = $value;


                }
            }
            //$this->scriptFiles[$this->coreScriptPosition] = $jsFiles;
            $this->scriptFiles[$position] = $jsFiles;
        }
       // CVarDumper::dump($this->scriptFiles,10,true);
    }

    protected function renderScriptBatch(array $scripts)
    {
        $html = '';
        $scriptBatches = array();
        foreach ($scripts as $scriptValue) {
            if (is_array($scriptValue)) {
                $scriptContent = $scriptValue['content'];
                unset($scriptValue['content']);
                $scriptHtmlOptions = $scriptValue;
                ksort($scriptHtmlOptions);
            } else {
                $scriptContent = $scriptValue;
                $scriptHtmlOptions = array();
            }
            $key = serialize($scriptHtmlOptions);
            $scriptBatches[$key]['htmlOptions'] = $scriptHtmlOptions;
            $scriptBatches[$key]['scripts'][] = $scriptContent;
        }
        foreach ($scriptBatches as $scriptBatch)
            if (!empty($scriptBatch['scripts']))
                $html .= Html::script(implode("\n", $scriptBatch['scripts']), $scriptBatch['htmlOptions']) . "\n";
        return $html;
    }

    public function renderBodyBegin(&$output)
    {
        $html = '';

        $config = Yii::app()->settings->get('seo');
        if ($config->googletag_id) {
            $html .= '
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $config->googletag_id . '"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->'.PHP_EOL;
        }

        if (isset($this->scriptFiles[self::POS_BEGIN])) {
            foreach ($this->scriptFiles[self::POS_BEGIN] as $scriptFileUrl => $scriptFileValue) {
                if (is_array($scriptFileValue))
                    $html .= Html::scriptFile($scriptFileUrl, $scriptFileValue) . "\n";
                else
                    $html .= Html::scriptFile($scriptFileUrl) . "\n";
            }
        }
        if (isset($this->scripts[self::POS_BEGIN]))
            $html .= $this->renderScriptBatch($this->scripts[self::POS_BEGIN]);


        if ($html !== '') {
            $count = 0;
            $output = preg_replace('/(<body\b[^>]*>)/is', '$1<###begin###>', $output, 1, $count);
            if ($count)
                $output = str_replace('<###begin###>', $html, $output);
            else
                $output = $html . $output;
        }
    }

    public function renderHead(&$output)
    {
        $html = '';
        if ($this->title) {
            $html .= CHtml::tag('title', array(), $this->title, true);
        }
        foreach ($this->metaTags as $meta)
            $html .= CHtml::metaTag($meta['content'], null, null, $meta) . "\n";
        foreach ($this->linkTags as $link)
            $html .= CHtml::linkTag(null, null, null, null, $link) . "\n";
        foreach ($this->cssFiles as $url => $media)
            $html .= Html::cssFile($url, $media) . "\n";
        foreach ($this->css as $css)
            $html .= Html::css($css[0], $css[1]) . "\n";
        if ($this->enableJavaScript) {
            if (isset($this->scriptFiles[self::POS_HEAD])) {
                foreach ($this->scriptFiles[self::POS_HEAD] as $scriptFileValueUrl => $scriptFileValue) {
                    if (is_array($scriptFileValue))
                        $html .= Html::scriptFile($scriptFileValueUrl, $scriptFileValue) . "\n";
                    else
                        $html .= Html::scriptFile($scriptFileValueUrl) . "\n";
                }
            }

            if (isset($this->scripts[self::POS_HEAD]))
                $html .= $this->renderScriptBatch($this->scripts[self::POS_HEAD]);
        }

        if ($html !== '') {
            $count = 0;
            $output = preg_replace('/(<title\b[^>]*>|<\\/head\s*>)/is', '<###head###>$1', $output, 1, $count);
            if ($count)
                $output = str_replace('<###head###>', $html, $output);
            else
                $output = $html . $output;
        }
    }

    public function renderBodyEnd(&$output)
    {
        if (!isset($this->scriptFiles[self::POS_END]) && !isset($this->scripts[self::POS_END])
            && !isset($this->scripts[self::POS_READY]) && !isset($this->scripts[self::POS_LOAD])
        )
            return;

        $fullPage = 0;
        $output = preg_replace('/(<\\/body\s*>)/is', '<###end###>$1', $output, 1, $fullPage);
        $html = '';
        if (isset($this->scriptFiles[self::POS_END])) {
            foreach ($this->scriptFiles[self::POS_END] as $scriptFileUrl => $scriptFileValue) {
                if (is_array($scriptFileValue))
                    $html .= Html::scriptFile($scriptFileUrl, $scriptFileValue) . "\n";
                else
                    $html .= Html::scriptFile($scriptFileUrl) . "\n";
            }
        }
        $scripts = isset($this->scripts[self::POS_END]) ? $this->scripts[self::POS_END] : array();
        if (isset($this->scripts[self::POS_READY])) {
            if ($fullPage)
                $scripts[] = "jQuery(function($) {\n" . implode("\n", $this->scripts[self::POS_READY]) . "\n});";
            else
                $scripts[] = implode("\n", $this->scripts[self::POS_READY]);
        }
        if (isset($this->scripts[self::POS_LOAD])) {
            if ($fullPage)
                $scripts[] = "jQuery(window).on('load',function() {\n" . implode("\n", $this->scripts[self::POS_LOAD]) . "\n});";
            else
                $scripts[] = implode("\n", $this->scripts[self::POS_LOAD]);
        }
        if (!empty($scripts))
            $html .= $this->renderScriptBatch($scripts);

        if ($fullPage)
            $output = str_replace('<###end###>', $html, $output);
        else
            $output = $output . $html;
    }


    private function minifyCssFile($url)
    {
        $name = pathinfo(basename($url), PATHINFO_FILENAME);
        $extension = pathinfo(basename($url), PATHINFO_EXTENSION);
        $path = Yii::getPathOfAlias('webroot') . $url;
        $minFile = $name . '.min.' . $extension;
        $newPath = dirname($path) . DS . $minFile;

        if (!preg_match('/(http|https):\/\//', $url)) {
            if (preg_match('/(?<!\.min)\.css$/', $url)) {

                if (!file_exists($newPath)) {
                    Yii::import('app.minify.minify.CSS');
                    Yii::log('minifyCssFile create file: ' . $newPath);
                    $minifier = new CSS($path);
                    $minifier->setImportExtensions(array());
                    $minifier->minify($newPath);

                }
                return dirname($url) . '/' . $minFile;

                /*Yii::import('app.minify.minify.CSS');
                $minifier = new CSS($path);
                $minifier->setImportExtensions(array());
                $minifier->minify($path);*/
            }
        }
        return $url;
    }

    private function minifyJsFile($url)
    {
        $name = pathinfo(basename($url), PATHINFO_FILENAME);
        $extension = pathinfo(basename($url), PATHINFO_EXTENSION);
        $path = Yii::getPathOfAlias('webroot') . $url;
        $minFile = $name . '.min.' . $extension;
        $newPath = dirname($path) . DS . $minFile;
        if (!preg_match('/(http|https):\/\//', $url)) {
            if (preg_match('/(?<!\.min)\.js$/', $url)) {
                if (!file_exists($newPath)) {
                    Yii::import('app.minify.minify.JS');
                    Yii::log('minifyJsFile create file: ' . $newPath);
                    $minifier = new JS($path);
                    $minifier->minify($newPath);

                }
                return dirname($url) . '/' . $minFile;
            }
        }
        return $url;
    }


    private function minifyCss($css)
    {

        Yii::import('app.minify.minify.CSS');
        $minifier = new CSS();
        $minifier->setImportExtensions(array());
        $minifier->add($css);
        return $minifier->minify();
    }

    /*todo: PAN no used.*/
    public function compressCSS($buffer)
    {
        // Remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        // Remove space after colons
        $buffer = str_replace(': ', ':', $buffer);
        // Remove whitespace
        $buffer = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

        return $buffer;
    }

    private function minifyJs($script)
    {
        Yii::import('app.minify.minify.JS');
        $minifier = new JS();
        $minifier->add($script);
        return $minifier->minify();


    }
}