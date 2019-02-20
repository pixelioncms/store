<?php

/**
 * Cart Widget
 * Display is module shop installed
 * @uses Widget 
 */
class ProcessinfoWidget extends BlockWidget {

    public function getTitle() {
        return 'Системная о процессах';
    }

    public function run() {
        $this->render($this->skin, array());
    }

}
