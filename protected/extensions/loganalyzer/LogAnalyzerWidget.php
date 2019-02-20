<?php

/**
 * LogAnalyzerWidget class file.
 *
 * @author Stanislav Sysoev <d4rkr00t@gmial.com>
 * @see https://github.com/d4rkr00t/yii-loganalyzer
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @version 0.2
 */
class LogAnalyzerWidget extends CWidget
{

    public $filters = array();
    public $log_file_path;
    public $title;
    private $last_status;
    protected $_path = 'ext.loganalyzer.';

    public function init()
    {
        parent::init();

        //Yii::import($this->_path . 'LogAnalyzer');

        if (!$this->log_file_path) {
            $this->log_file_path = Yii::app()->getRuntimePath() . DS . 'application.log';
        }

        /**
         * Set widget title
         */
        if (!$this->title) {
            $this->title = Yii::t('LogAnalyzerWidget.main', 'Log Analyzer');
        }
    }

    public function run()
    {

        if (isset($_GET['log'])) {
            if (file_exists($this->log_file_path)) {
                file_put_contents($this->log_file_path, '');
            }
            //   Yii::app()->controller->redirect($this->getUrl());
        }

        /**
         * Load log file
         */
        if (file_exists($this->log_file_path)) {
            $log = file_get_contents($this->log_file_path);
        }
        /**
         * Explode log on messages
         */
        if (isset($log)) {
           // $log = explode('.-==-.', $log);
            $log = explode('.-==-.', $log);

            $pop = array_pop($log);

            $log = array_reverse($log);
        } else {
            $log = array();
        }
        $this->registerAssets();

        $this->render('index', array(
            'log' => $log
        ));
    }

    /**
     * Register CSS and JS files.
     */
    protected function registerAssets()
    {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');

        $assets_path = dirname(__FILE__) . DS . 'assets';
        $url = Yii::app()->assetManager->publish($assets_path, false, -1, YII_DEBUG);
        $cs->registerCssFile($url . '/log.css');
    }

    public function filterLog($text)
    {
        foreach ($this->filters as $f) {
            if (preg_match('/' . $f . '/', $text)) {
                return false;
            }
        }

        return true;
    }

    public function showDate($text)
    {
        return date('H:i d.m.Y', strtotime(mb_substr($text, 0, 20, 'utf8')));
    }

    public function showError($text)
    {
        $text = mb_substr($text, 20, mb_strlen($text, 'utf8'), 'utf8');

        $text = explode('Stack trace:', $text);
        $text = $text[0];

        if ($this->last_status != "") {
            $text = str_replace($this->last_status . " ", "", $text);
        }

        return $text;
    }

    public function showStack($text)
    {
        $text = explode('Stack trace:', $text);
        return @$text[1];
    }

    public function showStatus($text)
    {
        if (preg_match('%\[error\]%', $text)) {
            $this->last_status = '[error]';
            return array('status' => 'error', 'class' => 'badge-danger');
        } elseif (preg_match('%\[warning\]%', $text)) {
            $this->last_status = '[warning]';
            return array('status' => 'warning', 'class' => 'badge-warning');
        } elseif (preg_match('%\[info\]%', $text)) {
            $this->last_status = '[info]';
            return array('status' => 'info', 'class' => 'badge-info');
        } elseif (preg_match('%\[sql\]%', $text)) {
            $this->last_status = '[sql]';
            return array('status' => 'sql', 'class' => 'badge-primary');
        } else {
            return array('status' => 'undefined', 'class' => '');
        }
    }

    public function getUrl()
    {
        return Yii::app()->controller->createUrl('/admin/app/security/clear');
    }

}
