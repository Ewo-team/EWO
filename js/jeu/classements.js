$(document).ready(function() {
   var destination = $('.highlight').offset().top;
   $("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination-50}, 500 );
   return false;
});