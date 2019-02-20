<?php

Yii::import('app.addons.email_template.CETemplateClasses');

class CETemplate extends CApplicationComponent {

    protected $_defaultData = array();

    public function init() {

        $request = Yii::app()->request;
        $this->_defaultData['year'] = date('Y', CMS::time());
        $this->_defaultData['time'] = date('H:i', CMS::time());
        $this->_defaultData['now_date'] = CMS::date(date('Y-m-d H:i:s'), true, true);
        $this->_defaultData['host'] = $request->serverName;
        $this->_defaultData['ip'] = $request->userHostAddress;
        //$this->_defaultData['ip_country'] = CMS::getCountryNameByIp($request->userHostAddress);
        $this->_defaultData['ip_country'] = Yii::app()->geoip->get($request->userHostAddress)->country;
        $this->_defaultData['useragent_browser'] = Yii::app()->browser->getName();
        $this->_defaultData['useragent_browser_v'] = Yii::app()->browser->getVersion();
        $this->_defaultData['browser_string'] = Yii::app()->browser->getString();
        $this->_defaultData['useragent_platform'] = Yii::app()->browser->getPlatform();
        $this->_defaultData['useragent'] = $request->userAgent;
        $this->_defaultData['site_name'] = Yii::app()->settings->get('app', 'site_name');
        parent::init();
    }

    public function template_path($data, $template_path, $templates_root_dir = false, $no_global_vars = flase
// $profiling = FALSE - пока убрали
    ) {
        // функция-обёртка для быстрого вызова класса
        // принимает шаблон в виде пути к нему

        $W = new CETemplateClasses(array(
            'data' => CMap::mergeArray($this->_defaultData, $data),
            'templates_root' => $templates_root_dir,
            'no_global_vars' => $no_global_vars
        ));
        $tpl = $W->get_template($template_path);
        $W->templates_current_dir = pathinfo($W->template_real_path($template_path), PATHINFO_DIRNAME) . '/';
        $string = $W->parse_template($tpl);
        return $string;
    }

    public function template($data, $template_code, $templates_root_dir = false, $no_global_vars = false
// profiling пока убрали
    ) {
        // функция-обёртка для быстрого вызова класса
        // принимает шаблон непосредственно в виде кода
        $W = new CETemplateClasses(array(
            'data' => CMap::mergeArray($this->_defaultData, $data),
            'templates_root' => $templates_root_dir,
            'no_global_vars' => $no_global_vars,
            'profiling' => false
        ));
        $string = $W->parse_template($template_code);
        return $string;
    }

}
