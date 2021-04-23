
$(document).ready(function(){
  // $('.banner-slider').slick({
  //   dots: true,
  //   //infinite: true,
  //   //speed: 300,
  //   slidesToShow: 1,
  //   arrows:false
  // });
  $('.banner-slider').bxSlider();

  $('.slider').bxSlider();

  $('.partener-slider').bxSlider({
    // maxSlides: 8,
    // minSlides: 4

    slideWidth: 170,
    minSlides: 8,
    maxSlides: 8,
    slideMargin: 10
  });
});