/*
 * функция создания карты
 * принимаемый параметр - ID блока, в котором будет инициализирована карта, string
 */



function setCoordsToMarkerInput(coords, selector) {


        $(selector).val(coords[0] + ',' + coords[1]);

    common.notify('Кординаты устрановны.','success');
}
/**
 * управление яндекс-картой. 
 **/
function mapsApi() {
    /*************  внутренние переменные *************/
    this.maps = new Object; //тут будет хранится объект карты
    this.loaded = false;// тут будет хранится флаг загруженности, чтобы каждый раз не проверять
    this.tempcontainer = new Array;//тут будут храниться функции,  которе был вызваны до загрузки АП

    this.markers = new Array; //Список маркеров

    /*************  конструктор и реализация очереди выполнения *************/

    //собственно, конструктор
    this.__constructor = function () {
        obj = this;
        //ymaps.ready(function () { //после загрузки АПИ 
        obj.loaded = true;        //ставим флаг что апи загружено
        obj.__initAfterLoad();   //выполняем ранее запошенные функции
        //});


    };

    //выполнение функций, запрошенных до загрузки яндекс-апи
    this.__initAfterLoad = function () {
        while (tempEl = this.tempcontainer.shift()) {//извлекаем функции из временного массива
            if (typeof (tempEl) == 'function')
                tempEl();//если там оказалась действиельно функция - выполняем

        }
    };

    /*постановщик очереди
     * принимаемый параметр - функция, которая будет выполнена либо поставлена в очередь
     */
    this.__exec = function (func) {
        if (this.loaded)		  // проверяем загружена ли АПИ
            func();			  //если загружена, выполняем требуемую функцию
        else
            this.tempcontainer.push(func);// если нет - ставим в очередь
    };




    /*************  тела функций *************/

    /**
     * добавление карты
     * принимаемый параметр: ID блока в котором будет прорисована карта. string
     * 
     * @param {string} containerId
     * @param {array} options
     * @returns {object}
     */
    this.__addMap = function (containerId, options) {
        if (this.maps[containerId] === undefined) {
            this.maps[containerId] = new map(containerId, options);

        }

    };

    /**
     * добавление к маркеру балуна
     * 
     * @param {type} map Карта
     * @param {type} marker Маркер
     * @param {array} params Параметры маркера
     */
    this.setMarkerWindow = function (map, marker, params) {
        if (params.balloonContentBody && params.balloonContentBody !== '') {
            var contentString = '<div id="content">' +
                    '<h1 id="firstHeading" class="firstHeading">' + params.balloonContentHeader + '</h1>' +
                    '<div id="bodyContent">' + (params.balloonContentBody)?params.balloonContentBody:'' + '</div>' +
                    '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 200

            });
            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });

        }
    };

    this.getPositions = function (pos) {
        switch (pos) {
            case 1:
                return google.maps.ControlPosition.TOP_CENTER;
                break;
            case 2:
                return google.maps.ControlPosition.TOP_LEFT;
                break;
            case 3:
                return google.maps.ControlPosition.TOP_RIGHT;
                break;
            case 4:
                return google.maps.ControlPosition.LEFT_TOP;
                break;
            case 5:
                return google.maps.ControlPosition.LEFT_CENTER;
                break;
            case 6:
                return google.maps.ControlPosition.LEFT_BOTTOM;
                break;
            case 7:
                return google.maps.ControlPosition.RIGHT_TOP;
                break;
            case 8:
                return google.maps.ControlPosition.RIGHT_CENTER;
                break;
            case 9:
                return google.maps.ControlPosition.RIGHT_BOTTOM;
                break;
            case 10:
                return google.maps.ControlPosition.BOTTOM_CENTER;
                break;
            case 11:
                return google.maps.ControlPosition.BOTTOM_LEFT;
                break;
            case 12:
                return google.maps.ControlPosition.BOTTOM_RIGHT;
                break;
        }
    };

    /**
     * установка объявления на карты
     * принимаемый параметр: JSON-объект
     * mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     * если отсутствует то функция применяется ко всем имеющимся
     * 
     * 
     * @param {type} params
     * @param {type} mapid
     * @returns {mapsApi}
     */
    this.__setMark = function (params, mapid) {
        var icon = null;
       // this.markers[mapid] = [];
        if (this.maps[mapid] !== undefined && this.maps[mapid].canmark) {
            var map = this.maps[mapid];

            if (params.options.iconHref) {
                icon = {
                    url: params.options.iconHref,
                    size: new google.maps.Size(params.options.iconSize[0], params.options.iconSize[1]),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(params.options.iconOffset[0], params.options.iconOffset[1]),
                    scaledSize: new google.maps.Size(params.options.iconSize[0], params.options.iconSize[1])//new google.maps.Size(params.options.iconImageSize.width, params.options.iconImageSize.height)
                };
            }

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(params.lat, params.lng),
                title: params.hint_content,
                label: params.icon_content,
                icon: icon,
                map: map,
                draggable:(params.draggable)?params.draggable:false
            });

      
            this.setMarkerWindow(map, marker, params);
            this.markers.push(marker);
        } else {
            for (var prop in this.maps) { //перебираем карты
                if (this.maps[prop].canmark) {
                    var map = this.maps[prop];

                    if (params.options.iconHref) {
                        icon = {
                            url: params.options.iconHref,
                            size: new google.maps.Size(params.options.iconSize.width, params.options.iconSize.height),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(params.options.iconOffset[0], params.options.iconOffset[1]),
                            scaledSize: new google.maps.Size(params.options.iconSize.width, params.options.iconSize.height)//new google.maps.Size(params.options.iconImageSize.width, params.options.iconImageSize.height)

                        };
                    }
                    var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(params.lat, params.lng),
                        title: params.hint_content,
                        label: params.icon_content,
                        icon: icon,
                        map: this.maps[prop],
                         draggable:(params.draggable)?params.draggable:false
                    });

                    this.setMarkerWindow(map, marker, params);

                }
            }
        }
        return this;
    };

    this.__setRouter = function (params, mapid) {
        //  var route;
        // index = typeof index !== 'undefined' ? index : 1111;

        if (this.maps[mapid] !== undefined) {
            var map = this.maps[mapid];
            var directionsDisplay = new google.maps.DirectionsRenderer;
            var directionsService = new google.maps.DirectionsService;
            directionsDisplay.setMap(map);
            directionsService.route({
                // origin: 'kiev',
                origin: new google.maps.LatLng(params.start[0], params.start[1]),
                destination: new google.maps.LatLng(params.end[0], params.end[1]),
                travelMode: (params.travelMode) ? params.travelMode : 'DRIVING', // DRIVING BICYCLING TRANSIT WALKING 
                unitSystem: google.maps.UnitSystem.METRIC, //google.maps.UnitSystem.METRIC, google.maps.UnitSystem.IMPERIAL


            }, function (response, status) {
                if (status === 'OK') {
                    directionsDisplay.setDirections(response);
                } else if (status = 'NOT_FOUND') {
                    common.notify('Не удалось найти геокод хотя бы одного места, указанного в качестве исходной точки, пункта назначения или промежуточной точки маршрута.', 'error');
                } else if (status = 'ZERO_RESULTS') {
                    common.notify('Не удалось проложить маршрут между исходной точкой и точкой назначения.', 'error');
                } else {
                    common.notify('Directions request failed due to ' + status, 'error');
                }
            });

        } else {
            for (var prop  in this.maps) { //перебираем карты
                console.log(params);
            }
        }
        return this;
    };



    /**
     * установка массива меток на карту: полуаем JSON  массив объекта.
     * 
     * @param {array} params
     * @param {type} mapid
     * @returns {mapsApi}
     */
    this.__setMarks = function (params, mapid) {
        if (mapid !== undefined) {
            if (this.maps[mapid] != undefined && this.maps[mapid].canmark) {
                for (var i = 0; i < params.length; i++) {
                    var geoObject = new Placemark(params[i]);
                    this.maps[mapid].geoObjects.add(geoObject);
                }
            }
        } else
            for (var prop  in this.maps) { //перебираем карты
                for (var i = 0; i < params.length; i++) {
                    if (this.maps[prop].canmark)
                        this.maps[prop].geoObjects.add(new Placemark(params[i]));
                }
            }
        return this;
    };


    /* Границы области показа
     * принимаемый параметр: двумерный массив
     * [[min_y,min_x],[max_y,max_x]]
     * где 
     * min_y, min_x широта и долгота левого нижнего угла области показа, float
     * max_y, max_x широта и долгота правого верхнего угла области показа, float
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
     */
    this.__setBounds = function (bounds, mapid) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined) {

                this.maps[mapid].fitBounds(bounds);

            }
        } else
            for (var prop in this.maps) { //перебираем карты


                this.maps[prop].fitBounds(bounds);
                var width = $(window).width();
                if (width >= 768) {
                    console.log('__setBounds');
                    // console.log(this.__setBoundsOffset(this.maps[prop]));
                    // var position = this.maps[prop].getGlobalPixelCenter();
                    // this.maps[prop].setGlobalPixelCenter([position[0] - 310, position[1]]);
                }


            }
        return this;
    };


    /**
     * обновление карты NO TEST
     * mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     * если отсутствует то функция применяется ко всем имеющимся
     * 
     * @param {string} mapid
     * @returns {mapsApi}
     */
    this.__redraw = function (mapid) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined) {
                google.maps.event.trigger(this.maps[mapid], 'resize');
            }
        } else
            for (var prop in this.maps) { //перебираем карты
                //this.maps[prop].container.fitToViewport();
                google.maps.event.trigger(this.maps[prop], 'resize');
            }
        return this;
    };



    this.__setCenterMap = function (coords, mapid, zoom) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined) {
                //this.maps[mapid].setCenter(new google.maps.LatLng(coords[0],coords[1]));
                this.maps[mapid].panTo(new google.maps.LatLng(coords[0], coords[1]));
                if (zoom) {
                    this.maps[mapid].setZoom(zoom);
                }
            }
        } else {
            for (var prop in this.maps) { //перебираем карты
                console.log('__setCenterMap each');
                this.maps[prop].setCenter(new google.maps.LatLng(coords[0], coords[1]));
                if (zoom) {
                    this.maps[prop].setZoom(zoom);
                }
            }
        }
        return this;
    };


    this.__setZoomMap = function (zoom, mapid) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined) {
                this.maps[mapid].setZoom(zoom);
            }
        } else
            for (var prop in this.maps) { //перебираем карты
                this.maps[prop].setZoom(zoom);
            }
        return this;
    };


    /**
     * 
     * установка марки и центрирование карты по марке
     * марка не содержит полноценного балуна, как при setMark(),
     * принимаемый параметр: JSON объект
     * mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     * если отсутствует то функция применяется ко всем имеющимся
     * 
     * @param {type} mark
     * @param {type} mapid
     * @returns {mapsApi}
     */
    this.__setCenteredMark = function (mark, mapid) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined && this.maps[mapid].canmark) {
                //для этой марки вызываем другую функуию создания метки
                this.maps[mapid].geoObjects.add(new Placemark(mark));
                this.maps[mapid].setCenter([mark.geoY, mark.geoX], 10, {
                    checkZoomRange: true
                });

            }
        } else
            for (var prop  in this.maps) { //перебираем карты
                if (this.maps[prop].canmark) {
                    //для этой марки вызываем другую функуию создания метки
                    this.maps[prop].geoObjects.add(new Placemark(mark));
                    this.maps[prop].setCenter([mark.geoY, mark.geoX], 10, {
                        checkZoomRange: true
                    });
                }
            }
        return this;
    };



    /*************  интерфейсы функций *************/


    /**
     * добавление карты
     * 
     * @param {string} containerId
     * @param {array} options
     * @returns {mapsApi}
     */
    this.addMap = function (containerId, options) {
        obj = this;
        this.__exec(function () {
            obj.__addMap(containerId, options);
            if (options.auto_show_routers) {
                $.each(options.routes, function (key, params) {
                    obj.__setRouter(params, containerId);
                });
            }
        });
        return this;
    };

    /**
     * установка объявления на карты
     * 
     * @param {type} params
     * @param {string} mapid ID карты.
     * @returns {mapsApi}
     */
    this.setMark = function (params, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setMark(params, mapid);
        });
        return this;
    };

    /**
     * 
     * @param {type} params
     * @param {string} mapid ID карты.
     * @param {type} index
     * @returns {mapsApi}
     */
    this.setRouter = function (params, mapid, index) {
        obj = this;
        this.__exec(function () {
            obj.__setRouter(params, mapid, index);
        });
        return this;
    };

    /**
     * установка объявления на карты
     * 
     * @param {string} mapid ID карты.
     * @param {type} params
     * @returns {mapsApi}
     */
    this.setMarkCoords = function (mapid, params) {
        obj = this;
        this.__exec(function () {
            obj.__setMarkCoords(mapid, params);
        });
        return this;
    };

    /**
     * установка объявлений на карты (получаемый массив JSON  объектов)
     * 
     * @param {type} params
     * @param {string} mapid ID карты.
     * @returns {mapsApi}
     */
    this.setMarks = function (params, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setMarks(params, mapid);
        });
        return this;
    };

    /**
     * уставливаем центр карты по кординатом
     * 
     * @param {int} zoom
     * @param {string} mapid ID карты.
     * @returns {mapsApi}
     */
    this.setZoomMap = function (zoom, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setZoomMap(zoom, mapid);
        });
        return this;
    };

    /**
     * Устанавка центра карты по координатам.
     * 
     * @param {object} coords https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Map-docpage/#setCenter-param-center
     * @param {string} mapid ID карты.
     * @param {int} zoom Если не чего не задано то берем размер зума с карты по уморчанию.
     * @param {object} options https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Map-docpage/#setCenter-param-options
     * @returns {mapsApi}
     */
    this.setCenterMap = function (coords, mapid, zoom, options) {
        obj = this;
        if (options === undefined) {
            options = {};
        }
        if (zoom === undefined) {
            zoom = this.maps[mapid].getZoom();
        }
        this.__exec(function () {
            obj.__setCenterMap(coords, mapid, zoom, options);
        });
        return this;
    };

    /**
     * Границы области показа
     * 
     * @param {array} bounds https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Map-docpage/#setBounds-param-bounds
     * @param {string} mapid ID карты.
     * @param {array} options https://tech.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Map-docpage/#setBounds-param-options
     * @returns {mapsApi}
     */
    this.setBounds = function (bounds, options, mapid) {
        obj = this;
        if (options === undefined) {
            options = {};
        }
        this.__exec(function () {
            obj.__setBounds(bounds, options, mapid);
        });
        return this;
    };


    /**
     * обновление карты
     * 
     * @param {string} mapid ID карты.
     * @returns {mapsApi}
     */
    this.redraw = function (mapid) {
        obj = this;
        this.__exec(function () {
            obj.__redraw(mapid);
        });
        return this;
    };


    /**
     * установка марки и центрирование карты по марке
     * 
     * @param {type} mark
     * @param {string} mapid ID карты.
     * @returns {mapsApi}
     */
    this.setCenteredMark = function (mark, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setCenteredMark(mark, mapid);
        });
        return this;
    };


    /**
     * проверка существования карты
     * 
     * @param {string} mapid ID карты.
     * @returns {result|Boolean}
     */
    this.hasMap = function (mapid) {
        result = false;
        if (this.maps[mapid] !== undefined)
            result = true;
        return result;
    };

    /*************  вызов конструктора и возврат объекта *************/
    this.__constructor();//вызов конструктора
    return this;
}

var min_x = 999;
var max_x = 0;
var min_y = 999;
var max_y = 0;

api = new mapsApi();



function map(containerId, options) {
    var width = $(window).width();

    var gmap = new google.maps.Map(document.getElementById(containerId), {
        zoom: options.zoom,
        center: new google.maps.LatLng(options.center.lat, options.center.lng),
        mapTypeId: options.type,
        disableDefaultUI: false,
        draggable: options.drag,
      //  panControl: true,
      //  signInControl: true
                //disableDoubleClickZoom: true

    });

    /*var citymap = {
     chicago: {
     center: {lat: 41.878, lng: -87.629},
     population: 2714856
     },
     ukraine: {
     center: {lat: 40.714, lng: -74.005},
     population: 18405837
     }
     };
     
     
     for (var city in citymap) {
     // Add the circle for this city to the map.
     var cityCircle = new google.maps.Circle({
     strokeColor: '#FF0000',
     strokeOpacity: 0.8,
     strokeWeight: 2,
     fillColor: '#FF0000',
     fillOpacity: 0.35,
     map: gmap,
     center: citymap[city].center,
     radius: Math.sqrt(citymap[city].population) * 100
     });
     }*/
    if (options.grayscale) {
gmap.setOptions({
      styles: [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":20},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":25},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":20},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]
});
    }

    //night mode
    if (options.night_mode) {
    var now = new Date();
    var hour = now.getHours();
    //var hour = 9;
    if (hour > 21 || hour < 5) {
        gmap.setMapTypeId('terrain');
        //gmap.setMapTypeId('hybrid');
        // gmap.setMapTypeId('roadmap');

       /* gmap.setOptions({
            styles: [
                {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                {
                    featureType: 'administrative.locality',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{color: '#263c3f'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#6b9a76'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry',
                    stylers: [{color: '#38414e'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#212a37'}]
                },
                {
                    featureType: 'road',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#9ca5b3'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry',
                    stylers: [{color: '#746855'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#1f2835'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#f3d19c'}]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{color: '#2f3948'}]
                },
                {
                    featureType: 'transit.station',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{color: '#17263c'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#515c6d'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.stroke',
                    stylers: [{color: '#17263c'}]
                }
            ]

        });*/
    }
    }

    if (options.mapTypeControl) {
        gmap.setOptions({
            mapTypeControl: true,
            mapTypeControlOptions: {
                // style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                position: api.getPositions(options.mapTypeControl)
            }
        });
    }
    if (options.scrollwheel) {
        gmap.setOptions({scrollwheel: true});
    }
    if (options.scaleControl) {
        gmap.setOptions({scaleControl: true});
    }
    if (options.rotateControl) {
        gmap.setOptions({rotateControl: true});
    }
    if (options.zoomControl) {
        gmap.setOptions({
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                position: api.getPositions(options.zoomControl)
            }
        });
    }
    if (options.fullscreenControl) {
        gmap.setOptions({
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: api.getPositions(options.fullscreenControl)
            }
        });
    }

    if (options.streetViewControl) {
        gmap.setOptions({
            streetViewControl: true,
            streetViewControlOptions: {
                position: api.getPositions(options.streetViewControl)
            }
        });
    }

    if (width <= 768) {
        gmap.setOptions({draggable: false});
    } else {
        if (!options.drag) {
            gmap.setOptions({draggable: false});
        }
    }

    /*   var directionsDisplay = new google.maps.DirectionsRenderer;
     var directionsService = new google.maps.DirectionsService;
     
     directionsDisplay.setMap(gmap);
     
     
     directionsService.route({
     // origin: 'kiev',
     origin:new google.maps.LatLng(46.469517, 30.739846),
     destination: 'kiev',
     travelMode: 'DRIVING'
     }, function (response, status) {
     if (status === 'OK') {
     directionsDisplay.setDirections(response);
     } else {
     window.alert('Directions request failed due to ' + status);
     }
     });*/


    var bikeLayer = new google.maps.BicyclingLayer();
    bikeLayer.setMap(gmap);





    //Опображение трафига дорог.
    if (options.trafficLayer) {
        var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(gmap);
    }

    //Опображение общественного транспорта.
    if (options.transitLayer) {
        if (gmap.getMapTypeId() !== 'satellite') {
            var transitLayer = new google.maps.TransitLayer();
            transitLayer.setMap(gmap);
        }
    }
    



    
    gmap.canmark = true;

    return gmap;
}
