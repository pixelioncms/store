<?php

/**
 * Cart Widget
 * Display is module shop installed
 * @uses Widget 
 */
class YourinfoWidget extends BlockWidget {
    /* public function getTitle() {
      return Yii::t('YourinfoWidget.default', 'TITLE');
      } */

    public function run() {

        Yii::import('app.addons.Browser');
        $browserClass = new Browser();
        $browser = $browserClass->getBrowser();
        $platform = $browserClass->getPlatform();

        if ($browser == Browser::BROWSER_FIREFOX) {
            $browserIcon = 'firefox';
        } elseif ($browser == Browser::BROWSER_SAFARI) {
            $browserIcon = 'safari';
        } elseif ($browser == Browser::BROWSER_OPERA) {
            $browserIcon = 'opera';
        } elseif ($browser == Browser::BROWSER_CHROME) {
            $browserIcon = 'chrome';
        } elseif ($browser == Browser::BROWSER_IE) {
            $browserIcon = 'ie';
        }else{
			$browserIcon = '';
		}

        if ($platform == Browser::PLATFORM_WINDOWS) {
            $platformIcon = 'windows';
        } elseif ($platform == Browser::PLATFORM_WINDOWS_7) { //no tested
            $platformIcon = 'windows';
        } elseif ($platform == Browser::PLATFORM_WINDOWS_8) { //no tested
            $platformIcon = 'windows-7';
        } elseif ($platform == Browser::PLATFORM_WINDOWS_8_1) { //no tested
            $platformIcon = 'windows-7';
        } elseif ($platform == Browser::PLATFORM_WINDOWS_10) { //no tested
            $platformIcon = 'windows-7';
        } elseif ($platform == Browser::PLATFORM_ANDROID) {
            $platformIcon = 'android';
        } elseif ($platform == Browser::PLATFORM_LINUX) {
            $platformIcon = 'linux';
        } elseif ($platform == Browser::PLATFORM_APPLE) {
            $platformIcon = 'apple';
        }else{
			$platformIcon = '';
		}


        $this->render($this->skin, array(
            'platformIcon' => $platformIcon,
            'browserIcon' => $browserIcon,
            'browser' => $browserClass,
        ));
    }

}
