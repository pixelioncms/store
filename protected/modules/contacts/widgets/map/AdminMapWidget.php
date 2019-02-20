<?php

Yii::import('mod.contacts.widgets.map.CMapWidget');

class AdminMapWidget extends CMapWidget {

    const MAP_VERION = '2.1';

    public function run() {
        $cs = Yii::app()->clientScript;
        $maps = ContactsMaps::model()->findAll();
        foreach ($maps as $map) {

            $mapID = __CLASS__ . $map->id;
            //$this->option[$mapID] = $this->getOptions($map);


            $options = CMap::mergeArray($this->getOptions($map), $this->options);

            $this->renderMap($mapID, $options);

            $cs->registerScript($mapID, "
                var markers = " . CJSON::encode($this->getMapMarkers($map)) . ";
                var mapID = '" . $mapID . "';
                var mapOptions = " . CJavaScript::encode($options) . ";
                              
                function initMap(){
                    var map = api.addMap(mapID,mapOptions);
                    var mapid = map.maps[mapID];
                    var bounds = new google.maps.LatLngBounds();

                    $.each(markers,function(i,marker){
                        api.setMark(marker,mapID);
                        bounds.extend(new google.maps.LatLng(marker.lat, marker.lng));
                    });

                    if(markers.length > 1){
                        api.setBounds(bounds,mapID);
                    }

                    mapid.addListener('click', function(event) {
                                var balloonContentBody = '<div id=\"content\">' +
                    '<div id=\"bodyContent\"><a href=\"javascript:void(0)\" onClick=\"setCoordsToMarkerInput(['+event.latLng.lat()+','+event.latLng.lng()+'],\'#ContactsMarkers_coords\')\">".Yii::t('admin','INSTALLED')."</a></div>' +
                    '</div>';
                        api.setMark({balloonContentBody:balloonContentBody,draggable:true,lng:event.latLng.lng(),lat:event.latLng.lat(),options:{}},mapID)
                        if(map.markers.length > 1){
                            for (var i = 0; i < map.markers.length; i++) {
                               map.markers[i].setMap(null);
                            }
                            map.markers = new Array;
                        }

                    });
                }
        ", CClientScript::POS_BEGIN);
            break;
        }
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


            'zoomControl' =>(int) $model->zoomControl,
            'zoom' => (int) $model->zoom,

            'auto_show_routers' => (int) $model->auto_show_routers,
            'routes' => $routers,
            'center' => array(
                'lat' => (float) $coords_center[0],
                'lng' => $coords_center[1]
            ),
            'type' => $model->type,
            'drag' => (int) $model->drag,
        );
    }
}
