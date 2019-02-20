<?php

/**
 * Upgrade class
 *
 * @author PIXELION CMS development team <info@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 * @version 1.0
 */
class Upgrade2 {

    /**
     * Путь загругки
     */
    public $path_temp = 'webroot.protected.runtime.downloadManager';

    /**
     * Путь распаковки модулей
     */
    const PATH_EXTRACT = 'webroot.protected.modules';

    public $filename = 'upgrade-test.zip';

    public function __construct() {
        if (!file_exists(Yii::getPathOfAlias($this->path_temp))) {
            CFileHelper::createDirectory(Yii::getPathOfAlias($this->path_temp), 0777);
        }
        if (!file_exists(Yii::getPathOfAlias("{$this->path_temp}.modules"))) {
            CFileHelper::createDirectory(Yii::getPathOfAlias("{$this->path_temp}.modules"), 0777);
        }
    }

    /**
     * Загрузка обновление на сервер сайта.
     * 
     * @param string $modname Название папки модуля.
     * @param string $ver Версия модуля
     */
    public function downloadMod($modname, $ver) {
        // Адрес файла, который необходимо скачать
        $url = "https://corner-cms.com/upgrade/modules/{$modname}/{$ver}.zip";
        $pi = pathinfo($url);
        $ext = $pi['extension'];
        $name = $pi['filename'];

        if (Yii::app()->hasComponent('curl')) {
            $saveFile = $name . '.' . $ext;
            $newSaveFile = $modname . '_' . $ver . '.' . $ext;
            $pathTempModules = Yii::getPathOfAlias("{$this->path_temp}.modules");

            if (file_exists($pathTempModules . DS . $newSaveFile)) {

                $fp = fopen($pathTempModules . DS . $saveFile, 'wb'); //w+
                $curl = Yii::app()->curl;
                $curl->options = array(
                    'timeout' => 50,
                    'setOptions' => array(
                        CURLOPT_URL => str_replace(" ", "%20", $url),
                        CURLOPT_HEADER => false,
                        CURLOPT_FILE => $fp,
                        //   CURLOPT_BINARYTRANSFER => true,
                        //   CURLOPT_AUTOREFERER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                    //    CURLOPT_RETURNTRANSFER => true,
                    )
                );
                $connect = $curl->run($url);
                if (!$connect->hasErrors()) {
                    Yii::app()->controller->setNotify(self::t('SUCCESS_DOWNLOAD_MODULE', array(
                                '{mod}' => ucfirst($modname),
                                '{v}' => $ver,
                            )), 'success');
                    fclose($fp);

                    //Rename download temp file
                    if (file_exists($pathTempModules . DS . $saveFile)) {

                        rename($pathTempModules . DS . $saveFile, $pathTempModules . DS . $newSaveFile);
                    }
                    //$this->extract($ver . '.zip');
                    return true;
                } else {
                    $error = $connect->getErrors();
                    Yii::app()->controller->setNotify(self::t('ERROR_DOWNLOAD_MODULE', array(
                                '{mod}' => ucfirst($modname),
                                '{v}' => $ver,
                                '{message}' => $error->message
                            )), 'error');
                    //throw new Exception($opt->message);
                }
            } else
                Yii::app()->controller->setNotify('Уже присуствует', 'info');
        } else {
            throw new Exception('No find curl component.');
        }
    }

    /**
     * Установка обновление.
     */
    public function extract($name, $filename) {
        $zip = new ZipArchive();
        $filepath = Yii::getPathOfAlias("{$this->path_temp}.modules") . DS . $name . '_' . $filename;
        if (file_exists($filepath)) {
            $extractTo = Yii::getPathOfAlias(self::PATH_EXTRACT) . DS;
            if ($zip->open($filepath) === true) {
                $zip->extractTo($extractTo); //Извлекаем файлы в указанную директорию
                $zip->close(); //Завершаем работу с архивом
                $this->removeUpdateZip($filepath);
                Yii::app()->controller->setNotify(self::t('SUCCESS_EXTRACT_MODULE'), 'success');
            } else {
                Yii::app()->controller->setNotify(self::t('ERROR_ARCHIVE_FILE'), 'error');
            }
        } else {
            Yii::app()->controller->setNotify(self::t('ERROR_EXTRACT_FILE'), 'error');
        }
    }

    private function removeUpdateZip($file_path) {
        if (file_exists($file_path)) {
            chmod($file_path, 0755);
            //$permtest = CMS::chmod($file_path, 777);
            //if ($permtest)
            //    die($permtest);
            unlink($file_path);
        }
    }


    public static function t($message, $params) {
        return Yii::t('DownloadManagerModule.default', $message, $params);
    }

}
