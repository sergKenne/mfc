document.addEventListener('DOMContentLoaded', function(){
    if(site.method == 'main_page'){
        var officeList = document.getElementById('office_list') ,myMap;
            document.querySelectorAll('.info__map path').forEach(function(district){
            district.onclick = function(e){
                e.preventDefault();
                setXHR('/offices/byDistrict/'+district.getAttribute('data-id'), false, 'GET', function(response){
                    if(response['data']){
                        ymaps.ready(init);

                        function init () {
                            document.getElementById('map').style.display = 'block';
                            document.querySelector('.info__map').style.display = 'none';

                            myMap = new ymaps.Map('map', {
                                center: response['data'][0]['coords'].split(', '),
                                zoom: 10
                            }, {
                                searchControlProvider: 'yandex#search'
                            });

                            var html = '';

                            response['data'].forEach(function(office) {
                                var balloon = {
                                    'balloonContentHeader': office.name,
                                    'balloonContentBody': '<a target="_blank" style="color: #00f" href="/offices/view/'+office.id+'">Перейти к офису</a>',
                                    'hintContent': 'неизвестная загруженность'
                                };

                                const myPlacemark = new ymaps.Placemark(office.coords.split(', '), balloon,
                                    {
                                        iconLayout: 'default#image',
                                        iconImageHref: '/img/geo_grey.png',
                                        iconImageSize: [38, 48]
                                    }
                                );
                                myMap.geoObjects.add(myPlacemark);

                                html += renderOffice(office);
                            });
                            officeList.innerHTML = html;
                        }
                    }
                });
            }
        });

        document.getElementById('onmap').onclick = function(e){
            e.preventDefault();
            document.querySelector('.info__yandex-map').style.display = 'none';
            document.querySelector('.info__map').style.display = '';
            setXHR('/offices/officeFilter/12', false, 'GET', function(response){
                if(response['data']){
                    var html = '';
                    response['data'].forEach(function(item){
                        html += renderOffice(item);
                    });
                    officeList.innerHTML = html;
                }
            });

            myMap.destroy();
        };

        document.getElementById('onlist').onclick = function(e){
            e.preventDefault();
            document.querySelector('.info__yandex-map').style.display = 'none';
            document.querySelector('.info__map').style.display = '';

            setXHR('/offices/officeFilter/10', false, 'GET', function(response){
                if(response['data']){
                    var html  = '';
                    response['data'].forEach(function(office){
                        html += renderOffice(office);
                    });
                    officeList.innerHTML = html;
                }
            });

            myMap.destroy();
        };

        //инит полной карты офисов на главной
        ymaps.ready(function () {
            var fullMap = new ymaps.Map('fullmap', {
                    center: [58.739874, 61.883069],
                    zoom: 6
                }, {
                    searchControlProvider: 'yandex#search'
                });
                var datalocation = JSON.parse(document.getElementById('allOfficeMapData').textContent);
                datalocation.forEach(function(elt) {
                    fullMap.geoObjects.add(new ymaps.Placemark(elt.coordinate, elt.balloon, elt.icon));
                });
        });


        var officeScript = document.createElement('script');
        officeScript.src = '/js/modules/offices.js';
        document.querySelector('head').appendChild(officeScript);
        officeScript.onload = function(){ officeSearchInit();}
    }

    if(site.method == 'contacts'){
        var officeCoords = document.getElementById('map-office').getAttribute('data-coords').split(', ');
        ymaps.ready(function () {
            var myMap = new ymaps.Map('map-office', {
                    center: officeCoords,
                    zoom: 17
                }, {
                    searchControlProvider: 'yandex#search'
                }),

                myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                    hintContent: ''
                }, {
                    iconLayout: 'default#image',
                    iconImageHref: '/img/geo.png',

                    iconImageSize: [40, 48],
                    iconImageOffset:[-25, -37]
                });

            myMap.geoObjects
                .add(myPlacemark)
                .add(myPlacemarkWithContent);
        });
    }

    if(site.module == 'pages' && site.method == 'offices'){
        var officeScript = document.createElement('script');
        officeScript.src = '/js/modules/offices.js';
        document.querySelector('head').appendChild(officeScript);
        officeScript.onload = function(){ officeSearchInit();}
    }
});