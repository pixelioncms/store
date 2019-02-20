<?php

class LALogRoute extends CFileLogRoute
{
    /**
     * Formats a log message given different fields.
     * @param string $message message content
     * @param integer $level message level
     * @param string $category message category
     * @param integer $time timestamp
     * @return string formatted message
     */
    protected function formatLogMessage($message, $level, $category, $time)
    {
        $message .= '.-==-.';
        $ip = CMS::getip();
        if ($ip) {
            return date('Y/m/d H:i:s', $time) . " [ip:" . $ip . "] [$level] [$category] $message\n";
        } else {
            parent::formatLogMessage($message, $level, $category, $time);
        }

    }
}
