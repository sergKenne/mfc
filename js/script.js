var cities = [
  {
      'cityName': 'Екатеринбург',
      'offices': [
          {
            'coordinates': [56.829374, 60.672699],
            'city': 'Екатеринбург, Свердловская область, Россия', 'name': 'Сибирский тракт (дублёр), 2',
            "phone": '8 (343) 273-00-08'
          },
          //{'coordinates': [55.701677873469, 37.57358050756649], 'name': 'Ленинский проспект, 47с2'}
      ]
  },
];


$(document).ready(function(){
    $('.banner-slider').bxSlider();

    $('.slider').bxSlider();

    $('.partener-slider').bxSlider({
      slideWidth: 170,
      minSlides: 8,
      maxSlides: 8,
      slideMargin: 10,
      stopAutoOnClick: true,
    });

    $("#search-js, #modal-btn-js, #modal-js").click(function(){
      $("#modal-js").slideToggle(); 
    });

    $("#map-svg path").each(function(){
      let color = $(this).attr("fill");
        $(this).hover(
            function(){
              $(this).css({fill: "#F49678"})
            }, function(){
              $(this).css({fill: color})
            }
        );

        $(this).click(function(){
          if($(this).data("city")) {
            console.log(cities);
            //alert($(this).data("city"));
            let city = $(this).data("city");
                city = cities.find(c => c.cityName === city )
                const {coordinates} = city.offices[0]
                const current_city = city.offices[0].city;
                const current_name = city.offices[0].name;
                console.log("coordinates:", current_city);
                ymaps.ready(init);
                

                

                function init () {
                  $(".info__yandex-map").css({display: "block" });
                  $(".info__map").css({display: "none" });
                    var myMap = new ymaps.Map('map', {
                        //center: [56.829374, 60.672699], 
                        center: coordinates,
                        zoom: 10
                    }, {
                        searchControlProvider: 'yandex#search'
                    });


                    // Создаём макет содержимого.
                    MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
                        '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
                    ),

                    myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                        hintContent: current_name,
                        balloonContent: current_city
                    }, {
                        // Опции.
                        // Необходимо указать данный тип макета.
                        iconLayout: 'default#image',
                        // Своё изображение иконки метки.
                        iconImageHref: '../img/geo.png',
                        // Размеры метки.
                        iconImageSize: [40, 48],
                        // Смещение левого верхнего угла иконки относительно
                        // её "ножки" (точки привязки).
                        iconImageOffset: [-5, -38]
                    }),

                    // myPlacemarkWithContent = new ymaps.Placemark(coordinates, {
                    //     hintContent: 'Собственный значок метки с контентом',
                    //     balloonContent: 'А эта — новогодняя',
                    //     iconContent: '12'
                    // }, {
                    //     // Опции.
                    //     // Необходимо указать данный тип макета.
                    //     iconLayout: 'default#imageWithContent',
                    //     // Своё изображение иконки метки.
                    //     iconImageHref: '../img/geo.png',
                    //     // Размеры метки.
                    //     iconImageSize: [48, 48],
                    //     // Смещение левого верхнего угла иконки относительно
                    //     // её "ножки" (точки привязки).
                    //     iconImageOffset: [-24, -24],
                    //     // Смещение слоя с содержимым относительно слоя с картинкой.
                    //     iconContentOffset: [15, 15],
                    //     // Макет содержимого.
                    //     iconContentLayout: MyIconContentLayout
                    // });

                myMap.geoObjects
                    .add(myPlacemark)
                    //.add(myPlacemarkWithContent);




                    // document.getElementById('destroyButton').onclick = function () {
                    //     // Для уничтожения используется метод destroy.
                    //     myMap.destroy();
                    // };

                }


          } else {
            alert("no Data")
          }
        })
    })
});


// Дождёмся загрузки API и готовности DOM.
// ymaps.ready(init);

// function init () {
  
//     var myMap = new ymaps.Map('map', {
//         center: [56.829374, 60.672699], 
//         zoom: 10
//     }, {
//         searchControlProvider: 'yandex#search'
//     });

//     document.getElementById('destroyButton').onclick = function () {
//         // Для уничтожения используется метод destroy.
//         myMap.destroy();
//     };

// }






