jQuery(document).ready(function ($) {
$('.has_hover').hover ( function () {
$('.has_active').toggleClass('inner3_background');	    
});	   
$('.part_01 h2').append('<i class="mdplus mbhide fa fa-plus"></i>');   
$('.part_01 h2').append('<i class="mdminus mbhide fa fa-minus"></i>');   
$('.part_01').click ( function () {
 $(this).find('p').toggle(300);   
}); 

$('.part_01').click ( function () {
 $(this).find('h2 .mdplus').toggle();
  $(this).find('h2 .mdminus').toggle();
});    

// Set the date we're counting down to
var countDownDate = new Date();
countDownDate.setHours(countDownDate.getHours() + 4);    

// Update the count down every 1 second
var x = setInterval(function() {

  // Get todays date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
  document.getElementById("demo").innerHTML = hours + "h "
  + minutes + "m " + seconds + "s ";
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);    

    
// Scrolling Menus
$(".scrollTo").on('click', function(e) {
     e.preventDefault();
     var target = $(this).attr('href');
     $('html, body').animate({
       scrollTop: ($(target).offset().top)
     }, 1000);
  });
    
 $(window).scroll(function() {
   if ($(this).scrollTop() > 150){
      $('#head_bar').addClass("sticky-bar");
   } else {
      $('#head_bar').removeClass("sticky-bar");
   }
});   
    
    
});




