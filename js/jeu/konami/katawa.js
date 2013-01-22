    $(document).ready(function(){
            $(window).konami(function(){
                    //jQuery('#divK').html('<object width="70%" height="70%"  style="margin-left:15%;margin-top:10%;box-shadow: 0px 0px 200px black;border:1px solid black;"><param name="movie" value="http://www.youtube.com/v/9bZkp7q19f0&hl=fr&fs=1&rel=0&color1=0x3a3a3a&color2=0x999999&border=1&autoplay=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/9bZkp7q19f0&hl=fr&fs=1&rel=0&color1=0x3a3a3a&color2=0x999999&border=1&autoplay=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="70%" height="70%"></embed></object>');
                    jQuery('#divK').html('<embed src="http://gry.giercownia.pl/gry/k/1326551981_katawa_crash.swf" '+
    'id="flashK" width="70%" height="70%" style="margin-left:15%;margin-top:10%;box-shadow: 0px 0px 200px black;border:1px solid black;"/>')

                    $('#divK').fadeIn('slow');
            });
    });