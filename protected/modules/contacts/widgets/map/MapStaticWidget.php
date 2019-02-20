<?php

Yii::import('mod.contacts.widgets.map.CMapWidget');

class MapStaticWidget extends CMapWidget {

    public $pk;

    public function run() {
        $cs = Yii::app()->clientScript;
        $map = ContactsMaps::model()->findByPk($this->pk);
        if ($map) {

            $mapID = __CLASS__ . $map->id;
            $this->setId(__CLASS__ . $map->id);

            $options = CMap::mergeArray($this->getOptions($map), $this->options);
            $this->renderMap($this->id, $options);

            $cs->registerScript($mapID, "
                var markersList = " . CJSON::encode($this->getMapMarkers($map)) . ";
                var mapID = '" . $mapID . "';
                var mapOptions = " . CJavaScript::encode($options) . ";
                function initMap(){
                      var map = api.addMap(mapID,mapOptions);
                    var bounds = new google.maps.LatLngBounds();
                    $.each(markersList,function(i,marker){
                        api.setMark(marker,mapID);

                        bounds.extend(new google.maps.LatLng(marker.lat, marker.lng));
  
                    });

                    if(markersList.length > 1){
                        api.setBounds(bounds,mapID);
                    }
                }
        ", CClientScript::POS_BEGIN);
        }
    }

}
