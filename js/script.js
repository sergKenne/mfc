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
    if ($(window).width() < 426) {
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

AOS.init();

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

    $('.newslider').bxSlider({
      //mode: 'fade',
      // captions: true,
      // slideWidth: 600
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
    });

    //SLIDE MENU
    $(".header__burger-svg--bars").click(function(){
      const $this = $(this);
      $(".header__modal-menu").slideDown("fast", function(){
        $this.css({display: "none"});
        $(".header__burger-svg--close").css({display: "block"});
      }) 
    });

    $(".header__burger-svg--close").click(function(){
      const $this = $(this);
      $(".header__modal-menu").slideUp("fast", function(){
        $this.css({display: "none"});
        $(".header__burger-svg--bars").css({display: "block"});
      }) 
    });

    $(".header__modal-menu").click(function(){
      $(this).slideUp("fast", function(){
        $(".header__burger-svg--close").css({display: "none"});
        $(".header__burger-svg--bars").css({display: "block"});
      }) 
    })



    $(".sidebar__label-service").click(function(e){
     // e.preventDefault();
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

    //slide filter on 1024 adaptive
    $(".our-services__filter-btn").bind({
      click: function () {
        const $plus = $(this).find(".our-services__filter-icon-plus");
        const $minus = $(this).find(".our-services__filter-icon-minus");
        const $sidebar = $(".our-services__sidebar");
        
        if($plus.is(":visible")) {
          $sidebar.css({ left: 0 });
          $plus.css({display: "none"}); 
          $minus.css({display: "inline-block"})
        } else {
          $sidebar.css({ left: "-110%" });
          $plus.css({display: "inline-block"}); 
          $minus.css({display: "none"})
        }
      }
    });

    $(".our-services__name").click(function(){
      const $this = $(this);
      const $filter_list = $(".our-services__filter-list");
      const $icon = $this.find(".our-services__sort-icon");
      if($filter_list.is(":visible")) {
        $filter_list.css({
          display: "none",
          height: "2px",
          opacity: 0,
        });
        $icon.css({transform: "rotate(0deg)"})

      } else {
        $filter_list.css({
          display: "block",
          height: "initial",
          opacity: 1, 
        });
        $icon.css({transform: "rotate(180deg)"})
      }
      //$(this).next()
    })


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



fetch("https://tasty.p.rapidapi.com/recipes/list?from=0&size=20&tags=under_30_minutes", {
	"method": "GET",
	"headers": {
		"x-rapidapi-key": "74ec274a6dmsh672f38dbce8dfa3p104fb5jsne0dc744ec624",
		"x-rapidapi-host": "tasty.p.rapidapi.com"
	}
})
.then(response => response.json())
.then(data => console.log("data:", data))
.catch(err => {
	console.error(err);
});