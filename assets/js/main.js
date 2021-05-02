(function($) {
var winW = $(window).width();
$('.slider-single').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: true,
  fade: false,
  prevArrow: $('.fl-prv-nxt .fl-prev'),
  nextArrow: $('.fl-prv-nxt .fl-next'),
  asNavFor: '.slider-nav',
  infinite: true,
});

$('.slider-nav').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  asNavFor: '.slider-single',
  dots: false,
  arrows: false,
  focusOnSelect: true,
  infinite: false,
  centerMode: false,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        dots: false
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
        dots: false
      }
    }
  ]
});

 $('.mb-show-order-sm span').on('click', function(){
    $('.mb-sidebar-cntlr').slideToggle(300);
    $(this).toggleClass('active-collapse');
  });
  if($('.mHc').length){
    $('.mHc').matchHeight();
  };
  var timer2 = "10:00";
  var interval = setInterval(function() {


  var timer = timer2.split(':');
  //by parsing integer, I avoid all extra string processing
  var minutes = parseInt(timer[0], 10);
  var seconds = parseInt(timer[1], 10);
  --seconds;
  minutes = (seconds < 0) ? --minutes : minutes;
  if (minutes < 0) clearInterval(interval);
  seconds = (seconds < 0) ? 59 : seconds;
  seconds = (seconds < 10) ? '0' + seconds : seconds;
  //minutes = (minutes < 10) ?  minutes : minutes;
  if( minutes >= 0 ){
    $('.countdown').html('0'+minutes + ':' + seconds);
  }else{
    $('.countdown').html('--:--');
  }
  timer2 = minutes + ':' + seconds;
}, 1000);

if(winW < 767){
  $('.upsell-two-grid-tp-price span').on('click', function(){
    $(this).toggleClass('menu-expend');
    $('.upsell-two-grid-btm-price').slideToggle(500);
  });
}

})(jQuery);