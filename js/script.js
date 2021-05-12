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

var minSlides;
var maxSlides;
function windowWidth() {
    if ($(window).width() < 420) {
        minSlides = 3;
        maxSlides = 3;
    }
    else if ($(window).width() < 768) {
        minSlides = 4;
        maxSlides = 4;
    }
    else if ($(window).width() < 1200) {
        minSlides = 5;
        maxSlides = 5;
    }
    else {
        minSlides = 8;
        maxSlides = 8;
    }
}

windowWidth();

$(document).ready(function(){
    $('.banner-slider').bxSlider({
      minSlides: 1,
      maxSlides: 1,
      //slideWidth: ($(window).width() < 426)? 400 : $(window).width() - 20,
    });

    $('.slider').bxSlider();

    $('.partener-slider').bxSlider({
      slideWidth: 170,
      // minSlides: 8,
      // maxSlides: 8,
      minSlides: minSlides,
      maxSlides: maxSlides,
      slideMargin: 10,
      stopAutoOnClick: true,
    });

    $("#search-js, #modal-btn-js, #modal-js").click(function(){
      $("#modal-js").slideToggle(); 
    });

    //OFFERS JS
    $(".offers-title-js").bind({
      click: function() {
        const $this = $(this);
        const $list = $(this).closest(".offers__list-item").find(".offers__sublist");
        const $plus_icon =  $(this).find(".offers__plus-icon");
        const $minus_icon =  $(this).find(".offers__minus-icon");

        $list.slideToggle("fast", function(){
          if($(this).is(":visible")) {
            $this.addClass("offers__list-title--active");
            $plus_icon.css({opacity: 0});
            $minus_icon.css({opacity: 1});
            
          } else {
            $this.removeClass("offers__list-title--active");
            $plus_icon.css({opacity: 1});
            $minus_icon.css({opacity: 0});
          }
        });
      }
    });

    //SIDEBAR JS
    $(".sidebar__sub-title").click(function(){
      const $this = $(this);
      const $subList = $(this).closest(".sidebar__item").find(".sidebar__sub-list");
      $subList.slideToggle("fast", function() {
        if($subList.is(":visible")) {
          $this.find(".sidebar__fas-icon").css({
            transform: "translate(20px, 5px) rotate(-180deg)",
            "transform-origin": "center",
          })
        } else {
          $this.find(".sidebar__fas-icon").css({
            transform: "translate(0px, 5px) rotate(0deg)",
            "transform-origin": "center",
          })
        } 
      }); 
    })



    $(".sidebar__label-service").click(function(e){
      e.preventDefault();
      const $nextNode = $(this).next();
      const $this = $(this);
      $nextNode.slideToggle("fast", function(){
        if($nextNode.is(":visible")) {
          $this.find(".sidebar__minus-icon").css({opacity: 1});
          $this.find(".sidebar__plus-icon").css({opacity: 0});
        } else {
          $this.find(".sidebar__minus-icon").css({opacity: 0});
          $this.find(".sidebar__plus-icon").css({opacity: 1});
        }
      })
    })


    //SUBLIST
    // $(".tes").click(function(){
    //   const $list = $(this).next();
    //   //const $check = $(this).find(".sidebar__input");
    //   console.log($(this))
    //   // if($check.is(':checked')) {
    //   //   console.log("is checked")
    //   // } else {
    //   //   console.log("is not check")
    //   // }

    //   //$list.slideUp()
    //   // if($list.is(":visible")) {
    //   //   console.log("true")
    //   //   //$list.slideUp();
    //   //   //$list.addClass("hide")
    //   // }else {
    //   //   console.log("false")
    //   //   //$list.slideDown();
    //   //   //$list.removeClass("hide")
    //   // }
    // })

    // $(".tes").each(function() {
    //   $(this).click(function() {
    //     console.log($(this))
    //   })
      
    // })




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






