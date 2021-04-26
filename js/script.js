
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
});