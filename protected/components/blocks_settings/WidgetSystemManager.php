<?php

class WidgetSystemManager extends CComponent {

    public function getSystemClass($alias) {
        $path = dirname(Yii::getPathOfAlias($alias));
        $fpath = ($path . DS . 'form');
        if (file_exists($fpath)) {
            $fileForm = self::fileHelper($fpath, 1);
          //  Yii::import($alias); //import block class
          //  $fileBlock = self::fileHelper($path);


       

        $arr = explode('.', $alias);
        $numItems = count($arr);
        $i = 0;
        foreach ($arr as $key => $value) {
            if (++$i === $numItems) {
                $alias = str_replace('.'.$value, '', $alias);
                Yii::import("{$alias}.form.$fileForm"); //import block class
               
                return new $fileForm;
                break;
            }
        }
            
            
            
        /*    if (isset($block->alias)) {
                Yii::import($block->alias . '.form.' . $fileForm); // import form class
            } else {
                die('Ошибка, в блоке не определен $alias');
            }*/

            return new $fileForm;
        } else {

            if (Yii::app()->request->isAjaxRequest)
                die('система не обнаружела настройки виджета');
            return false;
        }
    }

    public function getWidgetTitle($alias) {
        $arr = explode('.', $alias);
        $numItems = count($arr);
        $i = 0;
        foreach ($arr as $key => $value) {
            if (++$i === $numItems) {
                Yii::import("{$alias}"); //import block class
                $class = new $value;
              
                return (isset($class->title)) ?$class->title :'Unknown widget title' ;
                break;
            }
        }
    }

    public function getClass($alias, $className) {
        $path = dirname(Yii::getPathOfAlias($alias) . DS . $className);
        $fpath = ($path . DS . 'form');
        if (file_exists($fpath)) {
            $fileForm = self::fileHelper($fpath, 1);
            Yii::import("{$alias}.{$className}"); //import block class
            //$fileBlock = self::fileHelper($path);
            //   $block = new $fileBlock;
            //  if (isset($block->alias)) {
            Yii::import("{$alias}.form.{$fileForm}"); // import form class
            // } else {
            //     die('Ошибка, в блоке не определен $alias');
            //}

            return new $fileForm;
        } else {

            return false;
        }
    }

    /**
     * Находим файл по $path & $level
     * 
     * @param string $path
     * @param int $level Default value "0"
     * @return string Название класса
     */
    private static function fileHelper($path, $level = 0) {
        $file = CFileHelper::findFiles($path, array(
                    'level' => $level,
                    'fileTypes' => array('php'),
                    'absolutePaths' => false
                        )
        );
        return self::replcePHP($file[0]);
    }

    /**
     * Убираем со строки ".php"
     * 
     * @param string $name
     * @return string
     */
    private static function replcePHP($name) {
        return str_replace('.php', '', $name);
    }

}
