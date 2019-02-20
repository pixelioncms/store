/*
 * функция создания карты
 * принимаемый параметр - ID блока, в котором будет инициализирована карта, string
 */

function map(containerId, options) {










    var ymap = new ymaps.Map(containerId, {
        // Центр карты, по умолчанию Одесса
        center: [options.center.let, options.center.lng],
        // Коэффициент масштабирования
        zoom: options.zoom,
        type: options.type,
        //type: 'my#type',
        controls: []
    });




    var width = $(window).width();
    //console.log(width);
    // alert(width);
    if (width <= 768) {
        ymap.behaviors.disable('drag');
    } else {
        if (!options.drag) {
            ymap.behaviors.disable('drag');
        }
    }
    if (!options.scrollZoom) {
        ymap.behaviors.disable('scrollZoom');
    }
    console.log(options.zoomControl);
    if (options.zoomControl.enable) {

        var zoomPosition = new Object();
        if (options.zoomControl.top) {
            zoomPosition['top'] = options.zoomControl.top;
        }
        if (options.zoomControl.bottom) {
            zoomPosition['bottom'] = options.zoomControl.bottom;
        }
        if (options.zoomControl.left) {
            zoomPosition['left'] = options.zoomControl.left;
        }
        if (options.zoomControl.right) {
            zoomPosition['right'] = options.zoomControl.right;
        }

        var zoomControl = new ymaps.control.ZoomControl({
            options: {
                size: "large",
                position: zoomPosition
            }
        });
        ymap.controls.add(zoomControl);
    }
    /*ymap.layers.add(new ymaps.Layer(
     'https://tile.openstreetmap.org/%z/%x/%y.png', {
     projection: ymaps.projection.sphericalMercator
     }));*/






    ymap.copyrights.add('CORNER CMS'); //&copy; 
    ymap.placemarkset = new ymaps.GeoObjectCollection();
    ymap.geoObjects.add(ymap.placemarkset);



    ymap.geoObjects.add(new ymaps.GeoObjectCollection());
    ymap.canmark = true;
    ymap.coordInput = options.coordInput;









    return ymap;
}


/**
 * создание метки
 * 
 * @param {type} params
 * @returns {ymaps.Placemark}
 */
function Placemark(params) {
    console.log(params);
    return false;
    var placemark = new ymaps.Placemark(
            params.coords,
            //[params.coordy, params.coordx],
            params.properties,
            // {preset:'islands#circleDotIcon'}
            params.options
            );

    return placemark;
}

function setCoords(coordY, coordX, selector) {

    if (selector === undefined) {
        $('input#ContactsMarkers_coords').val(coordY + ',' + coordX);
    } else {
        $(selector).val(coordY + ',' + coordX);
    }
    $.jGrowl('Кординаты устрановны.');
}

function setContentBalloon(value) {
    $('#ContactsMarkers_balloon_content_body').html(value);

    tinymce.activeEditor.setContent(value);
    $.jGrowl('Содержание балуна устрановно.');
}

// Определяем адрес по координатам (обратное геокодирование)
function getAddress(coords, myPlacemark) {

    myPlacemark.properties.set('iconContent', 'поиск...');
    ymaps.geocode(coords).then(function (res) {
        var firstGeoObject = res.geoObjects.get(0);

        myPlacemark.properties
                .set({
                    iconContent: firstGeoObject.properties.get('name'),
                    balloonContent: firstGeoObject.properties.get('text')
                });
    });
}
/**
 * управление яндекс-картой. 
 **/
function mapsApi() {
    /*************  внутренние переменные *************/
    this.maps = new Object; //тут будет хранится объект миникарты
    this.loaded = false;// тут будет хранится флаг загруженности, чтобы каждый раз не проверять
    this.tempcontainer = new Array;//тут будут храниться функции,  которе был вызваны до загрузки АП



    /*************  конструктор и реализация очереди выполнения *************/

    //собственно, конструктор
    this.__constructor = function () {
        obj = this;
        ymaps.ready(function () { //после загрузки АПИ 
            obj.loaded = true;        //ставим флаг что апи загружено
            obj.__initAfterLoad();   //выполняем ранее запошенные функции
        });


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
        if (this.maps[mapid] !== undefined && this.maps[mapid].canmark) {
            this.maps[mapid].geoObjects.add(new ymaps.Placemark(params.coords, params.properties, params.options));
        } else {
            for (var prop in this.maps) { //перебираем карты
                if (this.maps[prop].canmark) {
                    this.maps[prop].geoObjects.add(new ymaps.Placemark(params.coords, params.properties, params.options));
                }
            }
        }
        return this;
    };

    this.__setRouter = function (params, mapid, index) {
        var route;
        index = typeof index !== 'undefined' ? index : 1111;
        if (this.maps[mapid] !== undefined) {
            var map = this.maps[mapid];

            ymaps.route([params.coords[1], params.coords[0]], {
                mapStateAutoApply: params.mapStateAutoApply
            }).then(function (router) {

                //route && map.geoObjects.remove(98);
                route = router;
                route.getPaths().options.set({
                    // в балуне выводим только информацию о времени движения с учетом пробок
                    balloonContentBodyLayout: ymaps.templateLayoutFactory.createClass('$[properties.humanJamsTime]'),
                    // можно выставить настройки графики маршруту
                    //strokeColor: '0000ffff',
                    strokeColor: params.color,
                    opacity: params.opacity
                });

                //Находим ИНДЕК, если нашели удаляем.
                if (map.geoObjects.get(index)) {
                    map.geoObjects.remove(index);
                    map.geoObjects.set(index, route);
                }

                // добавляем маршрут на карту
                map.geoObjects.add(route, index);



                var points = route.getWayPoints(),
                        lastPoint = points.getLength() - 1;
                // Задаем стиль метки - иконки будут красного цвета, и
                // их изображения будут растягиваться под контент.
                points.options.set('preset', params.preset);
                // Задаем контент меток в начальной и конечной точках.
                points.get(0).properties.set('iconContent', params.start_icon_content);
                points.get(0).properties.set('balloonContent', params.start_balloon_content_body);
                points.get(lastPoint).properties.set('balloonContent', params.end_balloon_content_body);
                points.get(lastPoint).properties.set('iconContent', params.end_icon_content);





            }, function (error) {
                alert("Возникла ошибка: " + error.message);
            });

        } else {
            for (var prop  in this.maps) { //перебираем карты
                console.log(params);
            }
        }
        return this;
    };

    this.__setMarkCoords = function (mapid) {
        var myPlacemark;
        if (this.maps[mapid] !== undefined && this.maps[mapid].canmark) {
            //this.maps[mapid].geoObjects.add(new Placemark(params));
            var that = this;
            var map = this.maps[mapid];

            map.events.add('click', function (e) {
                var coords = e.get('coords');
                var coordy = coords[0].toPrecision(6);
                var coordx = coords[1].toPrecision(6);
                var options = {
                    coordy: coordy,
                    coordx: coordx,
                    coordInput: map.coordInput,
                    properties: {},
                    options: {
                        preset: 'islands#violetStretchyIcon',
                        draggable: false
                    }
                };
                // Если метка уже создана – просто передвигаем ее
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = new Placemark(options);
                    map.geoObjects.add(myPlacemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    //  myPlacemark.events.add('dragend', function () {
                    //      getAddress(myPlacemark.geometry.getCoordinates(),map);
                    //  });
                }

                var install_coord_button = new ymaps.control.Button({
                    data: {
                        content: '<i class="icon-location" style="font-size:20px;"></i> Установить координаты',
                        title: ''
                    },
                    options: {
                        maxWidth: [28, 150, 200],
                        selectOnClick: false
                    }
                });

                var install_balloon_button = new ymaps.control.Button({
                    data: {
                        content: '<i class="icon-map-2" style="font-size:20px;"></i> Установить адрес балуна',
                        title: ''
                    },
                    options: {
                        maxWidth: [28, 150, 230],
                        selectOnClick: false
                    }
                });

                map.controls.add(install_coord_button, {
                    'float': "left",
                    position: {
                        top: 10,
                        left: 10
                    }
                });
                map.controls.add(install_balloon_button, {
                    'float': "left",
                    position: {
                        top: 10,
                        left: 210
                    }
                });
                install_coord_button.events.add('click', function (e) {
                    setCoords(coordx, coordy, options.coordInput);
                });


                myPlacemark.properties.set('iconContent', 'поиск...');
                ymaps.geocode(coords).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);
                    //console.log(firstGeoObject.properties.get('description'));
                    myPlacemark.properties
                            .set({
                                iconContent: firstGeoObject.properties.get('name'),
                                balloonContent: firstGeoObject.properties.get('text')
                            });
                    install_balloon_button.events.add('click', function (e) {
                        setContentBalloon(firstGeoObject.properties.get('text'));
                    });
                });



            });

        } else {
            for (var prop  in this.maps) { //перебираем карты
                if (this.maps[prop].canmark)
                    this.maps[prop].geoObjects.add(new Placemark(params));
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
// this.__setBoundsOffset(this.maps[mapid])
    this.__setBoundsOffset = function (mapid, bond) {
        var size = mapid.container.getSize(),
                bounds = bond,
                offsets = [5, 530, 0, 0], //bottom,left,top,right
                x = (bounds[1][1] - bounds[0][1]) - size[0],
                y = (bounds[1][0] - bounds[0][0]) - size[1];


        return [[bounds[0][0] - offsets[0] * y, bounds[0][1] - offsets[1] * x], [bounds[1][0] + offsets[2] * y, bounds[1][1] + offsets[3] * x]];
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
    this.__setBounds = function (bounds, options, mapid) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined) {

                this.maps[mapid].setBounds(bounds, options);

            }
        } else
            for (var prop in this.maps) { //перебираем карты




                this.maps[prop].setBounds(bounds, options);


                var width = $(window).width();
                if (width >= 768) {
                    // console.log(this.__setBoundsOffset(this.maps[prop]));
                    var position = this.maps[prop].getGlobalPixelCenter();
                    this.maps[prop].setGlobalPixelCenter([position[0] - 310, position[1]]);
                }


            }
        return this;
    };


    /**
     * обновление карты
     * mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     * если отсутствует то функция применяется ко всем имеющимся
     * 
     * @param {string} mapid
     * @returns {mapsApi}
     */
    this.__redraw = function (mapid) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined) {
                this.maps[mapid].container.fitToViewport();
            }
        } else
            for (var prop in this.maps) { //перебираем карты
                this.maps[prop].container.fitToViewport();
            }
        return this;
    };



    this.__setCenterMap = function (coords, mapid, zoom, options) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined) {
                this.maps[mapid].setCenter(coords, zoom, options);
                var width = $(window).width();
                if (width >= 768) {
                    var position = this.maps[mapid].getGlobalPixelCenter();
                    this.maps[mapid].setGlobalPixelCenter([position[0] - 310, position[1]]);
                }


//this.maps[mapid].panTo(coords, {flying: 1,timingFunction:'ease-in'});
            }
        } else {
            for (var prop in this.maps) { //перебираем карты
                this.maps[prop].setCenter(coords, zoom, options);
                var width = $(window).width();
                if (width >= 768) {
                    var position = this.maps[prop].getGlobalPixelCenter();
                    this.maps[prop].setGlobalPixelCenter([position[0] - 310, position[1]]);
                }
            }
        }
        return this;
    };


    this.__setZoomMap = function (zoom, mapid) {
        if (mapid !== undefined) {
            if (this.maps[mapid] !== undefined) {
                this.maps[mapid].setZoom(zoom, {});
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
                    obj.__setRouter(params, containerId, key);

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
