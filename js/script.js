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

    $("#search-js, #modal-btn-js").click(function(){
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
    })
});






