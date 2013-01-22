var domCharge = false;
var persoId;
var timer=setInterval("refresh_page()", 120000);	
//var timer=setInterval("refresh_page()", 5000);	

jQuery(document).ready(function(){
	persoId = $("#perso_id").val();
	if( typeof persoId != 'undefined' ) {
		domCharge = true;
	}
	
	$( ".tuto" ).tooltip();
	//$( ".tuto" ).tooltip("open");
	
});

    $(function() {
        $(window).scroll(function(){
                                var scrollTop = $(window).scrollTop();
                                if(scrollTop != 0)
                                        $('#nav').stop().animate({'opacity':'0.2'},400);
                                else	
                                        $('#nav').stop().animate({'opacity':'1'},400);
                        });

                        $('#nav').hover(
                                function (e) {
                                        var scrollTop = $(window).scrollTop();
                                        if(scrollTop != 0){
                                                $('#nav').stop().animate({'opacity':'1'},400);
                                        }
                                },
                                function (e) {
                                        var scrollTop = $(window).scrollTop();
                                        if(scrollTop != 0){
                                                $('#nav').stop().animate({'opacity':'0.2'},400);
                                        }
                                }
                        );
    });

function refresh_page() {
	if(domCharge) {
	
		// On rafraichi les carac
		refresh_carac();
	}
}

function refresh_carac() {
	$.getJSON('../ajax/carac.php', { perso_id: persoId }, function(data) {
		$.each(data, function(i,item){
			if(i != 'xp') {
				if ( typeof item.max == 'undefined') { 
					var valeur = item.actu;
				} else {
					var valeur = item.actu + "/" + item.max;
				}
				
				if(i == 'res_mag') {
					valeur = valeur + "%";
				}

				var classe = item.classcolor;
				var taille = item.taille;
				
				$("#carac_" + i + " > .caracs_sup").html(valeur);
				$("#carac_" + i + " > .color_red > span").removeClass();
				$("#carac_" + i + " > .color_red > span").addClass(classe);
				$("#carac_" + i + " > .color_red > span").css("width", taille+"%");
			
			} else {
				$("#carac_xp").html("Px " + item.px + " | Pi " + item.pi);
			}
		});
	});
};

