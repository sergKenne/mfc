document.addEventListener('DOMContentLoaded', function(){
    if((site.module == 'offices' && site.method == 'lists') || (site.module == 'offices' && site.method == 'view')){
        ymaps.ready(init);

        function init(){
            const dataLocation = JSON.parse(document.getElementById('mapData').textContent);

            var mapSettings;
            if(site.method == 'view'){
                mapSettings = {center: document.getElementById('map-office').getAttribute('data-coords').split(', '), zoom: 10}
            }
            else{
                mapSettings = {center: [58.739874, 61.883069], zoom: 5}
            }

            var myMapOffices = new ymaps.Map("map-offices", mapSettings, {
                searchControlProvider: 'yandex#search'
            });

            myMapOffices.behaviors.disable(["scrollZoom"]);


            dataLocation.forEach(elt => {
                console.log(elt)
            const myPlacemark = new ymaps.Placemark(elt.coordinate, elt.balloon, elt.icon);
            myMapOffices.geoObjects.add(myPlacemark);
            })
        }
    }

    if(site.module == 'offices' && site.method == 'lists'){
        officeSearchInit();
    }


    if(site.module == 'offices' && site.method == 'view'){
        var officeCoords = document.getElementById('map-office').getAttribute('data-coords').split(', ');
        ymaps.ready(function () {
            var myMap = new ymaps.Map('map-office', {
                    center: officeCoords,
                    zoom: 17
                }, {
                    searchControlProvider: 'yandex#search'
                }),

                MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                    '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
                ),

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
});

function officeSearchInit(){
    searchList = document.getElementById('search_list');
    var xhr = new XMLHttpRequest();
    document.getElementById('office_search').onkeyup = function(){
        if(this.value.length > 0){
            setXHR('/offices/officeFilter/?type=search&filter='+this.value, false, 'GET', function(response){
                if(response['data'] && response['data'].length > 0){
                    var html  = '';
                    response['data'].forEach(function(office){
                        html += renderOffice(office);
                    });
                    searchList.innerHTML = html;
                    searchList.style.display = '';
                }
                else{ searchList.style.display = 'none';}
            }, xhr);
        }
        else{ searchList.style.display = 'none';}
    };
}

function renderOffice(office){
    return '<a href="/offices/view/'+office.id+'" class="info__form-list-item">'+
        '<svg class="info__form-svg"><use xlink:href="/sprite.svg#loc"></use></svg>'+
        '<span class="info__form-city">'+office.name+'</span>'+
        '</a>';
}