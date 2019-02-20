<?php


class PhpMessageSource extends CPhpMessageSource
{

    public $_files = array();

    protected function getMessageFile($category, $language)
    {
        if (!isset($this->_files[$category][$language])) {
            if (($pos = strpos($category, '.')) !== false) {
                $extensionClass = substr($category, 0, $pos);
                $extensionCategory = substr($category, $pos + 1);
                // First check if there's an extension registered for this class.
                if (isset($this->extensionPaths[$extensionClass]))
                    $this->_files[$category][$language] = Yii::getPathOfAlias($this->extensionPaths[$extensionClass]) . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $extensionCategory . '.php';
                else {
                    // No extension registered, need to find it.
                    $class = new ReflectionClass($extensionClass);
                    $this->_files[$category][$language] = dirname($class->getFileName()) . DS . 'messages' . DS . $language . DIRECTORY_SEPARATOR . $extensionCategory . '.php';
                }
            } else
                $this->_files[$category][$language] = $this->basePath . DS . $language . DS . $category . '.php';
        }

        return $this->_files[$category][$language];
    }

}