<?php

class Upgrade {

    const PATH = 'webroot.uploads.upgrade';
    const PATH_TEMP = 'webroot.uploads.upgrade.temp';

    public $filename = 'upgrade-test.zip';

    /**
     * Загрузка обновление на сервер сайта.
     */
    public function download() {
        // Адрес файла, который необходимо скачать
        $url = 'https://pixelion.com.ua/upgrade/' . $this->filename;
        $pi = pathinfo($url);
        $ext = $pi['extension'];
        $name = $pi['filename'];

        if (Yii::app()->hasComponent('curl')) {
            $saveFile = $name . '.' . $ext;
            $fp = fopen(Yii::getPathOfAlias(self::PATH) . DS . $saveFile, 'w+');
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
                // $opt = $curl->getData();
                //print_r($opt);
                //Yii::app()->controller->setFlashMessage('Обновление успешно скачено');
                fclose($fp);
                return true;
            } else {
                throw new Exception('error');
            }

            //    if (preg_match("/[^0-9a-z\.\_\-]/i", $saveFile))
            //  $saveFile = md5(microtime(true)) . '.' . $ext;
            // $path = Yii::getPathOfAlias(self::PATH);
            // $handle = fopen($path . DS . $saveFile, 'w+');
            // fwrite($fp, $opt);
        }
    }

    /**
     * Установка обновление.
     */
    public function setup() {
        $zip = new ZipArchive();

        $filepath = Yii::getPathOfAlias(self::PATH) . DS . $this->filename;
        if (file_exists($filepath)) {
            $extractTo = Yii::getPathOfAlias(self::PATH_TEMP) . DS;
            if ($zip->open($filepath) === true) {
                $zip->extractTo($extractTo); //Извлекаем файлы в указанную директорию
                $zip->close(); //Завершаем работу с архивом
                $this->removeUpdateZip($filepath);
                Yii::app()->controller->setNotify('Обновление успешно установлено');
                
            } else {
                //  echo "Архива не существует!"; //Выводим уведомление об ошибке
            }
        } else {
            die('Файл обновние не найден.');
        }
    }

    private function removeUpdateZip($file_path) {
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

}

?>
