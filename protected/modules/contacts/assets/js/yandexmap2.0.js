/*
 * функция создания карты
 * принимаемый параметр - ID блока, в котором будет инициализирована карта, string
 */
function map(containerId,options){
 //  var opt = $.parseJSON( '{ "name": "John" }' );

    var ymap = new ymaps.Map(containerId, {
        center: [options.center.let,options.center.lng],
        zoom: options.zoom,
        // Тип карты
        type: options.type,

        behaviors: ["default", "scrollZoom"]
    });
    if(!options.drag){
        ymap.behaviors.disable('drag');
    }
    //элементы управления
    if(options.mapTools){
        ymap.controls.add('mapTools',{
            top: 5, 
            left: 35
        });
    }
    if(options.zoomControl){
        ymap.controls.add('zoomControl',{
            top: 7, 
            left: 5
        });
    }
    ymap.placemarkset = new ymaps.GeoObjectCollection();
    ymap.geoObjects.add(ymap.placemarkset);
    ymap.canmark = true;
    return ymap;
}

function __getBaloonContent(placemarkParams){
    var bContent='';

    bContent += "<div>"+placemarkParams.title+"</div>";  


    return bContent;
}
/*
 * создание метки
    *принимаемый параметр: JSON-объект
    *{geoX, geoY, title, link, avatar, price, shortname, cur_row}
    *где 
    *geoX - геокоордината по оси X (долгота), float
    *geoY - геокоордината по оси Y (широта), float
    *title   -  название объявления, string 
    *link   -  ссылка ведущая на страницу объявления, string
    *avatar - ссылка, ведущая к изображению аватарки, string
    *price   - цена, float
    *shortname - сокращенное название валюты, string
    *cur_row - название текущго периода оплаты, string  
 */
function Placemark(placemarkParams){
    //создание метки с указаным балуном
    var placemark = new ymaps.Placemark(
        // Координаты метки
        [placemarkParams.geoY, placemarkParams.geoX], {
            /* Свойства метки:*/
            // - контент балуна метки
            balloonContent: __getBaloonContent(placemarkParams),
            clusterCaption: placemarkParams.title
        }
        );
    return placemark;
}


/*
 * создание метки
     *марка не содержит полноценного балуна, как Placemark(),
     * принимаемый параметр: JSON объект
     * {geoY, geoX, title}
     * где
     *geoX - геокоордината по оси X (долгота), float
     *geoY - геокоордината по оси Y (широта), float
     *title   -  название объявления, string 
 */
function PlacemarkLow(mark){
    //создание метки с указаным балуном
    var placemark = new ymaps.Placemark(
        // Координаты метки
        [mark.geoY, mark.geoX], {
            /* Свойства метки:*/
            // - контент балуна метки
            balloonContent: mark.title
        }, {
            /* Опции метки:*/
            /* изображение метки */
            iconImageHref:document.getElementById('flat_indicator').src,
            /* размер метки */
            iconImageSize:[15,22],
            /* смещение метки */
            iconImageOffset:[-7, -22],
            /* не показывать значок метки при открытии балуна */
            hideIconOnBalloonOpen: false
        }
        );
    return placemark;
}


/* маркер - перетаскивалка круга */
function dragmarker(params,parent){
    var coords = params.coords;
    var image = false;
    var ImageUrl = false;
    var position = params.position;
    var placemark = new ymaps.Placemark(
        // Координаты метки
        [coords[0],coords[1]], {
        // - контент балуна метки
        //balloonContent: 'ololo'
        }, {
            iconImageHref: '/uploads/radiusmap/pimpa.png',
            iconImageSize:[34,34],
            iconImageOffset:[-17,-17],
            // hideIconOnBalloonOpen: false
            draggable : true
        }
        );

    //начало перетаскивания круга, возможно скрытие маркеров или что-то в этом духе
    placemark.events.add('dragstart',function(e){
        parent.hideMarkers();
	
    });    
    
    placemark.events.add('drag',function(e){
        parent.hideMarkers();
        var obj = e.get('target');
        var coords = obj.geometry.getCoordinates();
        parent.replace(coords);	
    });    
    
    //конец перетаскивания маркеров - изменение координаткруга
    placemark.events.add('dragend',function(e){
        //var obj = e.get('target');
        /*parent.geoInput.change();*/
        var obj = e.get('target');
        var coords = obj.geometry.getCoordinates();
        parent.replace(coords,true);
        parent.showMarkers();
    //alert (obj.startcoords);
    //alert (endcoords);
    });
  
    return placemark;
}


/* cоздание маркера-тянучки */
function marker(params,parent){
    var coords = params.coords;
    var image = false;
    var ImageUrl = false;
    var position = params.position;
    var offset =[0,0];
    switch(position){
        case 'left':{
	    
            coords=ymaps.coordSystem.geo.solveDirectProblem(coords, [0,1], params.radius).endPoint;
        };
        ImageUrl = '/uploads/radiusmap/right.png';
        offset = [0,-7];
        break;
        case 'right':{
	    
            coords=ymaps.coordSystem.geo.solveDirectProblem(coords, [0,-1], params.radius).endPoint;
        };
        ImageUrl = '/uploads/radiusmap/left.png';
        offset = [-14,-7];
        break;
        case 'top':{
	    
            coords=ymaps.coordSystem.geo.solveDirectProblem(coords, [1,0], params.radius).endPoint;
        };
        ImageUrl = '/uploads/radiusmap/top.png';
        offset = [-7,-14];
        break;
        case 'bottom':{
	    
            coords=ymaps.coordSystem.geo.solveDirectProblem(coords, [-1,0], params.radius).endPoint;
        };
        ImageUrl = '/uploads/radiusmap/bottom.png';
        offset = [-7,0];
        break;

    }
      
    var placemark = new ymaps.Placemark(
        // Координаты метки
        [coords[0],coords[1]], {
	    
        // - контент балуна метки
        //balloonContent: 'ololo'
        }, {
	    
            iconImageHref:ImageUrl,
	    
            iconImageSize:[14,14],
	    
            iconImageOffset:offset,
	    
            // hideIconOnBalloonOpen: false
            draggable : true
        }
        );
	    
    //реализация драг-н-дроп
    //сохраняем стартовую позицию - нужна для последущего выравнивания по горизонтали\вертикали
    placemark.events.add('dragstart',function(e){
        var obj = e.get('target');
        obj.startcoords = obj.geometry.getCoordinates();
    });
    
    //проверка изменений координат во время движения, чтобы не сбивалась горизонталь\вертикаль
    placemark.events.add('drag',function(e){
        var center = parent.circle.geometry.getCoordinates();
        var obj = e.get('target');
        var dist = 0;  //здесь будет сохранена дистанция от центра,
        //к сожалению картографов, земля у нас не плоская,
        //придётся вводить процентные отношения для изменяющейся дистанции
        var newcoords = obj.geometry.getCoordinates();
        switch(position){
            case 'left':
                newcoords[0]=obj.startcoords[0];
                if(newcoords[1]<center[1]) newcoords[1] = center[1];
		
                break;
            case 'right':
                newcoords[0]=obj.startcoords[0];
                if(newcoords[1]>center[1]) newcoords[1] = center[1];
		
                break;
            case 'top':
                newcoords[1]=obj.startcoords[1];
                if(newcoords[0]<center[0]) newcoords[0] = center[0];
		
                break;
            case 'bottom':
                newcoords[1]=obj.startcoords[1];
                if(newcoords[0]>center[0])newcoords[0] = center[0];
                break;
        }
	 
        dist = ymaps.coordSystem.geo.getDistance(center, newcoords)
        parent.resize(dist);
    });    
    
    placemark.events.add('dragend',function(e){
        //var obj = e.get('target');
        /*parent.geoInput.change();*/
        parent.radInput.change();
    //alert (obj.startcoords);
    //alert (endcoords);
    });
        
    return placemark;

}


/*
 * точки-переключалки городов
 */
function cPoint(point,circle){
    var placemark = new ymaps.Placemark(
        // Координаты метки
        [point.geoy*1,point.geox*1], {
            hintContent:point.name
	
        // - контент балуна метки
        //balloonContent: 'name'
        }, {
            iconImageHref: '/uploads/radiusmap/pimpochka.png',
            iconImageSize:[30,30],
            iconImageOffset:[-15,-15],
            // hideIconOnBalloonOpen: false
            draggable : false
        }
        );
    
    placemark.events.add('click',function(e){
        var obj = e.get('target');
        var coords = obj.geometry.getCoordinates();
        circle.geoInput.attr('value',coords[1]+', '+coords[0]) ;
        circle.moveTo();
    });
    return placemark;
}
/*
 * создание круга с биндом на инпуты и курсором управления.
 * входящие параметры - объект JSON {coordInput, radiusInput}
 * coordInput - ID инпута, в котором будут храниться геокоординаты (и оттуда браться) String
 * radiusInput - ID инпута, в котором будет храниться радиус, String
 */
function SearchCircle (params,map,points){
    this.collection = new ymaps.GeoObjectCollection();
    var mainobj = this;
    this.geoInput =$('#'+params.coordInput);// document.getElementById(params.coordInput);
    this.radInput = $('#'+params.radiusInput);// document.getElementById(params.radiusInput);
    var geoCoords = this.geoInput.attr('value').split(',');
    //приводим к числовому типу и переставляем местами (не перевибать-же всю базу из-за изменения формата)
    var buf =geoCoords[1]*1;
    geoCoords[1]=geoCoords[0]*1;
    geoCoords[0]=buf;
    var radius = this.radInput.attr('value')*1;	

    this.circle = new ymaps.Circle([geoCoords, radius], {}, { 
        //draggable : true,
        draggable : false,
        fillOpacity:0.3,
        fillColor: '#fbc848',
        strokeColor:'#287cb6',
        strokeOpacity:1
	
    });
    
    //создаём маркеры-тянучки, на самом деле метки с заданой картинкой

    this.leftmarker = new marker({
        position:'left', 
        coords:[geoCoords[0],geoCoords[1]], 
        radius:radius
    },this);
    this.rightmarker = new marker({
        position:'right', 
        coords:[geoCoords[0],geoCoords[1]], 
        radius:radius
    },this);
    this.topmarker = new marker({
        position:'top', 
        coords:[geoCoords[0],geoCoords[1]], 
        radius:radius
    },this);
    this.bottommarker = new marker({
        position:'bottom', 
        coords:[geoCoords[0],geoCoords[1]], 
        radius:radius
    },this);
   
    this.dragmarker = new dragmarker({
        coords:[geoCoords[0],geoCoords[1]]
    },this);
  
    /*
   //начало перетаскивания - прячем стрелочки
    this.circle.events.add('dragstart',function(e){
	mainobj.collection.remove(mainobj.leftmarker);
	mainobj.collection.remove(mainobj.rightmarker);
	mainobj.collection.remove(mainobj.topmarker);
	mainobj.collection.remove(mainobj.bottommarker);
    });


   
    //конец перетаскивания - меняем оординаты стрелочек, отображаем их и вызываем событие изменения 
координат
    this.circle.events.add('dragend',function(e){
	var obj = e.get('target');
	var coords = obj.geometry.getCoordinates();
	var radius  = obj.geometry.getRadius();
	mainobj.leftmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(coords, [0,1], radius
).endPoint);
	mainobj.rightmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(coords, [0,-1]
, radius).endPoint);
	mainobj.topmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(coords, [1,0], radius
).endPoint);
	mainobj.bottommarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(coords, [-1,0
], radius).endPoint);
	mainobj.collection.add(mainobj.leftmarker);
	mainobj.collection.add(mainobj.rightmarker);
	mainobj.collection.add(mainobj.topmarker);
	mainobj.collection.add(mainobj.bottommarker);
	mainobj.geoInput.attr('value',coords[1]+', '+coords[0]) ;
	mainobj.geoInput.change();
    });
  */
    /*изменение позицциив заданные координаты*/
    this.replace = function(coords,applyCoords){
        var radius  = mainobj.circle.geometry.getRadius();
        mainobj.dragmarker.geometry.setCoordinates(coords);
        mainobj.leftmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(coords, [0,1], radius
            ).endPoint);
        mainobj.rightmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(coords, [0,-1]
            , radius).endPoint);
        mainobj.topmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(coords, [1,0], radius
            ).endPoint);
        mainobj.bottommarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(coords, [-1,0
            ], radius).endPoint);
        mainobj.circle.geometry.setCoordinates([coords[0],coords[1]]);
	
        if(applyCoords == true){
            mainobj.geoInput.attr('value',coords[1]+', '+coords[0]) ;
            mainobj.geoInput.change();
        }
    }
    //перетаскивание круга - надо заодно тащить и маркеры
    /* this.circle.events.add('drag',function(e){
	var obj = e.get('target');

	
    //mainobj.geoInput.value = tGeoCoords[1]+','+tGeoCoords[0];
    });   
    */
    
    //distance - дистанция в метрах
    this.resize = function(distance){
        var center = this.circle.geometry.getCoordinates();
        //var radiusInMeters = distance*69*1000;
        this.leftmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(center, [0,1], distance
            ).endPoint);
        this.rightmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(center, [0,-1], distance
            ).endPoint);
        this.topmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(center, [1,0], distance
            ).endPoint);
        this.bottommarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(center, [-1,0], distance
            ).endPoint);
        this.circle.geometry.setRadius(distance);
        this.radInput.attr('value', distance);
    }

    //скрыть маркеры-тянучки
    this.hideMarkers = function(){
        mainobj.collection.remove(mainobj.leftmarker);
        mainobj.collection.remove(mainobj.rightmarker);
        mainobj.collection.remove(mainobj.topmarker);
        mainobj.collection.remove(mainobj.bottommarker);
    }
    //отобразить маркеры-тянучки
    this.showMarkers = function(){
        mainobj.collection.add(mainobj.leftmarker);
        mainobj.collection.add(mainobj.rightmarker);
        mainobj.collection.add(mainobj.topmarker);
        mainobj.collection.add(mainobj.bottommarker);
    }


    if(typeof(points)!=='undefined'){
        this.points = new  ymaps.GeoObjectCollection();
        for(var i=0;i< points.length;i++){
            this.points.add(new cPoint(points[i],this));
        }
        this.points.MRbounds = this.points.getBounds()
    }else this.points = false;



    this.moveTo = function(params){
        if(this.points!=false&&(this.geoInput.attr('value')=='0, 0'||this.geoInput.attr('value')=='')){
            api.clearAllMarks();
            map.geoObjects.remove(this.collection);
            map.geoObjects.add(this.points);
            //map.setBounds(this.points.getBounds(),{checkZoomRange:true});
            map.setBounds(this.points.MRbounds,{
                checkZoomRange:true
            });
            map.canmark = false;
        }else{
            map.geoObjects.remove(this.points);
            map.geoObjects.add(map.placemarkset);
            map.geoObjects.add(this.collection);
            var geoCoords = this.geoInput.attr('value').split(',');
            //приводим к числовому типу и переставляем местами (не перевибать-же всю базу из-за изменения формата)
            var buf =geoCoords[1]*1;
            geoCoords[1]=geoCoords[0]*1;
            geoCoords[0]=buf;
            var curRadius = this.radInput.attr('value')*1;
            this.circle.geometry.setCoordinates([geoCoords[0],geoCoords[1]]);
            this.circle.geometry.setRadius(curRadius);
            this.dragmarker.geometry.setCoordinates([geoCoords[0],geoCoords[1]]);
            this.leftmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(geoCoords, [0,1
                ], curRadius).endPoint);
            this.rightmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(geoCoords, [0
                ,-1], curRadius).endPoint);
            this.topmarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(geoCoords, [1,0
                ], curRadius).endPoint);
            this.bottommarker.geometry.setCoordinates(ymaps.coordSystem.geo.solveDirectProblem(geoCoords, [-1
                ,0], curRadius).endPoint);
            map.setBounds(this.circle.geometry.getBounds(),{
                checkZoomRange:true
            });
            map.canmark = true;
            this.radInput.change();
        }
	
    };

    this.destroy = function(){
        map.geoObjects.remove(this.collection);
        map.circle = undefined;
    };

    this.collection.add(this.circle);
    this.collection.add(this.leftmarker);
    this.collection.add(this.rightmarker);
    this.collection.add(this.topmarker);
    this.collection.add(this.bottommarker);
    this.collection.add(this.dragmarker);
    
    if(this.points!=false&&(this.geoInput.attr('value')=='0, 0'||this.geoInput.attr('value')=='')){
        api.clearAllMarks();
        map.geoObjects.add(this.points);
        this.points.MRbounds = this.points.getBounds()
        map.setBounds(this.points.MRbounds,{
            checkZoomRange:true
        });
        map.canmark = false;
    }else{
        map.geoObjects.add(this.collection);
        map.setBounds(this.circle.geometry.getBounds(),{
            checkZoomRange:true
        });
        map.canmark = true;
    }
    map.circle = this;
    return this;
}

/**
 * управление яндекс-картой. 
 **/
function mapsApi(){
    /*************  внутренние переменные *************/
    this.maps = new Object; //тут будет хранится объект миникарты
    this.loaded = false;// тут будет хранится флаг загруженности, чтобы каждый раз не проверять
    this.tempcontainer = new Array;//тут будут храниться функции,  которе был вызваны до загрузки АП



    /*************  конструктор и реализация очереди выполнения *************/
    
    //собственно, конструктор
    this.__constructor =  function() {  
        obj = this;
        ymaps.ready(function () { //после загрузки АПИ 
            obj.loaded = true;        //ставим флаг что апи загружено
            obj.__initAfterLoad();   //выполняем ранее запошенные функции
        });
    }; 
 
    //выполнение функций, запрошенных до загрузки яндекс-апи
    this.__initAfterLoad = function(){
        while(tempEl = this.tempcontainer.shift()){//извлекаем функции из временного массива
            if(typeof(tempEl)=='function') tempEl();//если там оказалась действиельно функция - выполняем
	    
        }
    }
 
    /*постановщик очереди
    * принимаемый параметр - функция, которая будет выполнена либо поставлена в очередь
    */
    this.__exec=function(func){ 
        if(this.loaded)		  // проверяем загружена ли АПИ
            func();			  //если загружена, выполняем требуемую функцию
        else this.tempcontainer.push(func);// если нет - ставим в очередь
    }




    /*************  тела функций *************/
    
    /*добавление карты
    *принимаемый параметр: ID блока в котором будет прорисована карта. string 
    */
    this.__addMap = function(containerId,options){
        if(this.maps[containerId] == undefined)
            this.maps[containerId] = new map(containerId,options);

    }
    
    /*очищение элементов карт
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
    */
    this.__clearAllMarks = function(mapid){
        if(mapid!=undefined){
            if(this.maps[mapid]!=undefined){
                this.maps[mapid].placemarkset.removeAll();
            //this.maps[mapid].geoObjects.remove(this.maps[mapid].placemarkset);
            //this.maps[mapid].geoObjects.add(this.maps[mapid].placemarkset);
            //	this.maps[mapid].geoObjects.each(function(geoObject){
            //	    this.maps[mapid].geoObjects.remove(geoObject);
            //	});		
            }
        }else
            for (var prop  in this.maps) { //перебираем карты
                //tmap = this.maps[prop];
                this.maps[prop].placemarkset.removeAll();
            }
    }

    /*установка объявления на карты
    *принимаемый параметр: JSON-объект
    *{geoX, geoY, title, link, avatar, price, shortname, cur_row}
    *где 
    *geoX - геокоордината по оси X (долгота), float
    *geoY - геокоордината по оси Y (широта), float
    *title   -  название объявления, string 
    *link   -  ссылка ведущая на страницу объявления, string
    *avatar - ссылка, ведущая к изображению аватарки, string
    *price   - цена, float
    *shortname - сокращенное название валюты, string
    *cur_row - название текущго периода оплаты, string  
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
    */
    this.__setMark=function (placemarkParams,mapid){
        console.log('__setMark');
        if(this.maps[mapid]!=undefined&&this.maps[mapid].canmark){
            this.maps[mapid].placemarkset.add(new Placemark(placemarkParams));
        }
        else{
            for (var prop  in this.maps) { //перебираем карты
                if(this.maps[prop].canmark) this.maps[prop].placemarkset.add(new Placemark(placemarkParams));
            }
        }
        return this;
    }


    /*
     * установка массива меток на карту: полуаем JSON  массив объекта.
     */
    this.__setMarks=function (placemarksParams,mapid){
        console.log(placemarksParams+ '__setMarks');
        if(mapid!=undefined){
            if(this.maps[mapid]!=undefined&&this.maps[mapid].canmark){
                for(var i=0; i< placemarksParams.length;i++){
                    var geoObject = new Placemark(placemarksParams[i]);
                    this.maps[mapid].placemarkset.add(geoObject);
                }
            }
        }else
            for (var prop  in this.maps) { //перебираем карты
                for(var i=0; i< placemarksParams.length;i++){
                    if(this.maps[prop].canmark)
                        this.maps[prop].placemarkset.add(new Placemark(placemarksParams[i]));
                }
            }
        return this;
    }


    /* Границы области показа
     * принимаемый параметр: двумерный массив
     * [[min_y,min_x],[max_y,max_x]]
     * где 
     * min_y, min_x широта и долгота левого нижнего угла области показа, float
     * max_y, max_x широта и долгота правого верхнего угла области показа, float
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
     */
    this.__setBounds=function (bounds,mapid){
        if(mapid!=undefined){
            if(this.maps[mapid]!=undefined){
                this.maps[mapid].setBounds(bounds,{
                    checkZoomRange:true
                });
            }
        }else
            for (var prop  in this.maps) { //перебираем карты
                this.maps[prop].setBounds(bounds,{
                    checkZoomRange:true
                });
            }
        return this;
    }

    /*обновление карты
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
     */
    this.__redraw=function (mapid){
        if(mapid!=undefined){
            if(this.maps[mapid]!=undefined){
                this.maps[mapid].container.fitToViewport();
                if(mapid=='full_map') {
                    if (this.maps[mapid].circle!=undefined) this.maps[mapid].circle.moveTo();
                }

            }
        }else
            for (var prop  in this.maps) { //перебираем карты
                this.maps[prop].container.fitToViewport();
            }
        return this;
    }
    
    
    
    this.__setCenterMap=function(coords,mapid){
        if(mapid!=undefined){
            if(this.maps[mapid]!=undefined){
                this.maps[mapid].setCenter(coords);

            }
        }else
            for (var prop  in this.maps) { //перебираем карты
                this.maps[prop].setCenter(coords);
            }
        return this;
    }
    
    
    this.__setZoomMap=function(zoom,mapid){
        if(mapid!=undefined){
            if(this.maps[mapid]!=undefined){
                this.maps[mapid].setZoom(zoom);

            }
        }else
            for (var prop  in this.maps) { //перебираем карты
                this.maps[prop].setZoom(zoom);
            }
        return this;
    }
    
    /*установка марки и центрирование карты по марке
     *марка не содержит полноценного балуна, как при setMark(),
     * принимаемый параметр: JSON объект
     * {geoY, geoX, title}
     * где
     *geoX - геокоордината по оси X (долгота), float
     *geoY - геокоордината по оси Y (широта), float
     *title   -  название объявления, string 
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
     */
    this.__setCenteredMark=function(mark,mapid){
        if(mapid!=undefined){
            if(this.maps[mapid]!=undefined&&this.maps[mapid].canmark){
                //для этой марки вызываем другую функуию создания метки
                this.maps[mapid].placemarkset.add(new PlacemarkLow(mark));
                this.maps[mapid].setCenter([mark.geoY, mark.geoX],10,{
                    checkZoomRange: true
                });
		
            }
        }else
            for (var prop  in this.maps) { //перебираем карты
                if(this.maps[prop].canmark){
                    //для этой марки вызываем другую функуию создания метки
                    this.maps[prop].placemarkset.add(new PlacemarkLow(mark));
                    this.maps[prop].setCenter([mark.geoY, mark.geoX],10,{
                        checkZoomRange: true
                    });
                }
            }
        return this;
    }

    //создание круга для поиска на карте
    this.__setSearchRadius = function(params,mapid,points){
        if(mapid!=undefined){
            if(this.maps[mapid]!=undefined){
                if(typeof(this.maps[mapid].circle)!=='undefined'){ //если круг уже существует, перемещаем его
                    this.maps[mapid].circle.moveTo(params);
                }else{				    //если круга ещё нет - создаём
                    var searchcircle = new SearchCircle(params,this.maps[mapid],points);
                    this.maps[mapid].circle = searchcircle;
                }
            }
        }
        return this;
    }


    /*************  интерфейсы функций *************/
    
    //добавление карты
    this.addMap=function(containerId, options){
        obj = this;
        this.__exec(function(){
            obj.__addMap(containerId,options)
        });
        return this;
    };

    //очищение элементов карт
    this.clearAllMarks = function(mapid){
        obj = this;
        this.__exec(function(){
            obj.__clearAllMarks(mapid)
        });
        return this;
    };

    //установка объявления на карты
    this.setMark=function (placemarkParams,mapid){
        obj = this;
        this.__exec(function(){
            obj.__setMark(placemarkParams,mapid)
        });
        return this;
    }

    //установка объявлений на карты (получаемый массив JSON  объектов)
    this.setMarks=function (placemarksParams,mapid){
        obj = this;
        this.__exec(function(){
            obj.__setMarks(placemarksParams,mapid)
        });
        return this;
    }
    /* уставливаем центр карты по кординатом*/
    this.setZoomMap=function (zoom,mapid){
        obj = this;
        this.__exec(function(){
            obj.__setZoomMap(zoom,mapid)
        });
        return this;
    }
    
    this.setCenterMap=function (coords,mapid){
        obj = this;
        this.__exec(function(){
            obj.__setCenterMap(coords,mapid)
        });
        return this;
    }

    //Границы области показа
    this.setBounds=function (bounds,mapid){
        obj = this;
        this.__exec(function(){
            obj.__setBounds(bounds,mapid)
        });
        return this;
    }

    //обновление карты
    this.redraw=function (mapid){
        obj = this;
        this.__exec(function(){
            obj.__redraw(mapid)
        });
        return this;
    }

    //установка марки и центрирование карты по марке
    this.setCenteredMark = function(mark,mapid){
        obj = this;
        this.__exec(function(){
            obj.__setCenteredMark(mark,mapid)
        });
        return this;
    }

    //создание круга для поиска на карте
    this.setSearchRadius = function(params,mapid,points){
        obj = this;
        this.__exec(function(){
            obj.__setSearchRadius(params,mapid,points)
        });
        return this;
    }
    
    //проверка существования карты
    this.hasMap=function(mapid){
        result = false;
        if(this.maps[mapid]!=undefined)result = true;
        return result;
    }
    
    /*************  вызов конструктора и возврат объекта *************/
    this.__constructor();//вызов конструктора
    return this;
}



api = new mapsApi();