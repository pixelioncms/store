<?php

class MainModule extends WebModule {

    public function init() {
        $this->setIcon('icon-home');
    }

    public function getRules() {
        return array(
            'layout/<layout:(demo-blocks-layout|ui)>' => 'main/index/test',
        );
    }


}
