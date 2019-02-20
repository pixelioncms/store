<?php

class CMapWidget extends CWidget {

    public $options = array();

    /**
     * Язык отображение карты
     * @var string 
     */
    protected $map_language = 'ru';

    public function init() {
        if (Yii::app()->languageManager->active->locale)
            $this->map_language = Yii::app()->languageManager->active->code;
        $this->registerScript();
    }

    /**
     * Отображаем елемент карты.
     * @param string $mapid
     * @param array $option
     */
    protected function renderMap($mapid, array $option) {
        echo Html::tag('div', array(
            'id' => $mapid,
            'class' => 'gmap',
            'style' => 'width:' . $option['width'] . ';height:' . $option['height'] . ''), '', true);
    }

    /**
     * Регистрируем js карт.
     */
    protected function registerScript() {
        $cs = Yii::app()->clientScript;

        $cs->registerScriptFile(Yii::app()->getModule('contacts')->assetsUrl . '/js/gmap.js', CClientScript::POS_BEGIN, array('async' => 'async'));
        $cs->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyDyLBWKHAoSUP4lo2Dzh8TEcEdpEXcIB-s&callback=initMap&language=' . $this->map_language, CClientScript::POS_END, array('async' => 'async'));
    }

    /**
     * Получяем список маркеров карты.
     * @param ContactsMaps $model
     * @return array
     */
    protected function getMapMarkers(ContactsMaps $model) {
        $result = array();
        foreach ($model->markers as $marker) {
            $coords = explode(',', $marker->coords);

            if ($marker->hasIcon()) {
                $iconArray = array(
                    'iconHref' => (string) Yii::app()->createAbsoluteUrl($marker->getImageUrl()),
                    'iconSize' => array((int) $marker->imageSize[0], (int) $marker->imageSize[1]), //array($marker->imageSize['width'], $marker->imageSize['height'])
                    //'iconOffset' => array((int) $marker->icon_file_offset_x, (int) $marker->icon_file_offset_y)
                    'iconOffset' => array((int) $marker->imageSize[0] - $marker->imageSize[0] / 2, (int) $marker->imageSize[1] - $marker->imageSize[1] / 2)
                );
            } else {
                $iconArray = array();
            }

            $result[] = array(
                'lat' => (float) $coords[0],
                'lng' => (float) $coords[1],
                'name' => $marker->name,
                'balloonContentBody' => $marker->balloon_content_body,
                'hint_content' => $marker->hint_content,
                'icon_content' => $marker->icon_content,
                'options' => CMap::mergeArray(array(), $iconArray),
            );
        }
        return $result;
    }

    /**
     * Получаем опции карты
     * @param ContactsMaps $model
     * @return array
     */
    protected function getOptions(ContactsMaps $model) {
        $coords_center = explode(',', $model->center);
        $routers = array();

        foreach ($model->routers as $route) {
            $routers[] = CJSON::decode($route->getJsonRoute());
        }
        return array(
            'width' => $model->width,
            'height' => $model->height,
            'mapTypeControl' => (int) $model->mapTypeControl,
            'zoomControl' => (int) $model->zoomControl,
            'fullscreenControl' => (int) $model->fullscreenControl,
            'streetViewControl' => (int) $model->streetViewControl,
            'scrollwheel' => (boolean) $model->scrollwheel,
            'scaleControl' => (boolean) $model->scaleControl,
            'rotateControl' => (boolean) $model->rotateControl,
            'trafficLayer' => (boolean) $model->trafficLayer,
            'transitLayer' => (boolean) $model->transitLayer,
            'night_mode' => (boolean) $model->night_mode,
            'zoom' => (int) $model->zoom,
            'auto_show_routers' => (int) $model->auto_show_routers,
            'routes' => $routers,
            'grayscale' => (boolean) $model->grayscale,
            'center' => array(
                'lat' => (float) $coords_center[0],
                'lng' => (float) $coords_center[1]
            ),
            'type' => $model->type,
            'drag' => (int) $model->drag,
        );
    }

    /**
     * Получаем опции карты
     * @param ContactsMaps $model
     * @return array
     */
    protected function getRouters(ContactsMaps $model) {
        $coords_center = explode(',', $model->center);

        return array(
            'width' => $model->width,
            'height' => $model->height,
            'zoomControl' => (int) $model->zoomControl,
            'zoom' => (int) $model->zoom,
            'auto_show_routers' => (int) $model->auto_show_routers,
            'mapStateAutoApply' => (int) $model->mapStateAutoApply,
            'center' => array(
                'lat' => (float) $coords_center[0],
                'lng' => (float) $coords_center[1]
            ),
            'start' => array(
                $model->start_coords,
            ),
            'end' => array(
                $model->end_coords,
            ),
            'type' => $model->type,
            'drag' => (int) $model->drag,
        );
    }

}
