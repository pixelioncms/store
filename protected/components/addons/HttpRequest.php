<?php


class HttpRequest extends CHttpRequest
{

    private $_pathInfo;

    /**
     * @var array of rules where to disable csrf validation
     */
    public $noCsrfValidationRoutes;

    /**
     * @return string
     * @throws CHttpException
     * @return string Parsed path info without lang prefix.
     */
    public function getPathInfo()
    {
        $langCode = null;
        $pathInfo = parent::getPathInfo();

        if (null === $this->_pathInfo) {

            $pathInfo = parent::getPathInfo();
            $parts = explode('/', $pathInfo);

            if (in_array($parts[0], Yii::app()->languageManager->getCodes())) {
                // Valid language code detected.
                // Remove it from url path to make route work and activate lang
                $langCode = $parts[0];

                // If language code is equal default - show 404 page
                if ($langCode === Yii::app()->languageManager->default->code)
                    throw new CHttpException(404, Yii::t('error', '404'));

                unset($parts[0]);
                $pathInfo = implode($parts, '/');
            }

            $this->_pathInfo = $pathInfo;


            // Activate language by code
            Yii::app()->languageManager->setActive($langCode);
        }

        return $pathInfo;
    }

    /**
     * Add param to current url. Url is based on $data and $_GET arrays
     *
     * @param $route
     * @param $data array of the data to add to the url.
     * @param $selectMany
     * @return string
     */
    public function addUrlParam($route, $data, $selectMany = false)
    {
        foreach ($data as $key => $val) {
            if (isset($_GET[$key]) && $key !== 'url' && $selectMany === true) {
                $tempData = explode(',', $_GET[$key]);
                $data[$key] = implode(',', array_unique(array_merge((array)$data[$key], $tempData)));
            }
        }

        return Yii::app()->createUrl($route, CMap::mergeArray($_GET, $data));
    }

    /**
     * Delete param/value from current
     *
     * @param string $route
     * @param string $key to remove from query
     * @param null $value If not value - delete whole key
     * @return string new url
     */
    public function removeUrlParam($route, $key, $value = null)
    {
        $get = $_GET;
        if (isset($get[$key])) {
            if ($value === null)
                unset($get[$key]);
            else {

                //if(!$this->isAjaxRequest){
                    $get[$key] = explode(',', $get[$key]);
                //}

                $pos = array_search($value, $get[$key]);
                // Delete value
                if (isset($get[$key][$pos]))
                    unset($get[$key][$pos]);
                // Save changes
                if (!empty($get[$key]))
                    $get[$key] = implode(',', $get[$key]);
                // Delete key if empty
                else
                    unset($get[$key]);
            }
        }
        return Yii::app()->createUrl($route, $get);
    }
    public function removePostUrlParam($route, $key, $value = null)
    {
        $get = $_POST;
        if (isset($get[$key])) {
            if ($value === null)
                unset($get[$key]);
            else {
                $pos = array_search($value, $get[$key]);
                // Delete value
                if (isset($get[$key][$pos]))
                    unset($get[$key][$pos]);
                // Save changes
                if (!empty($get[$key]))
                    $get[$key] = implode(',', $get[$key]);
                // Delete key if empty
                else
                    unset($get[$key]);
            }
        }
        return Yii::app()->createUrl($route, $get);
    }
    public function removeUrlGetParam($route, $key)
    {
        $get = $_GET;
        foreach ($key as $p) {
            if (isset($get[$p])) {
                // Delete value
                if (isset($get[$p]))
                    unset($get[$p]);
                // Delete key if empty
                else
                    unset($get[$p]);
            }
        }
        return Yii::app()->createUrl($route, $get);
    }

    /**
     * Normalize request.
     * Disable CSRF for payment controller
     */
    protected function normalizeRequest()
    {
        parent::normalizeRequest();

        if ($this->enableCsrfValidation && $this->isCLI() === false) {
            $url = $this->getRequestUri();
            foreach ($this->noCsrfValidationRoutes as $route) {
                //  Yii::log(substr($url, 0, strlen($route)),'info','routes');
                //  if (substr($url, 0, strlen($route)) === $route){
                //  Yii::log($route.' - '.$url,'info','routes');
                Yii::app()->detachEventHandler('onBeginRequest', array($this, 'validateCsrfToken'));
                // }
            }
        }
    }

    /**
     * Check if script launched from command line
     * @return bool
     */
    protected function isCLI()
    {
        if (substr(php_sapi_name(), 0, 3) === 'cli')
            return true;
        else
            return false;
    }

}
