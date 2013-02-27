jQuery(window).ready(function() {

	var RaceSelected = "";
	var GpSelected = "";

	jQuery(".choixrace").on("click", function() {
		
		if(RaceSelected == "") {
			jQuery("#race").val(this.name);
			RaceSelected = this.name;
		} else {
			jQuery("#race").val("");
			RaceSelected = "";		
		}	
                
                if(RaceSelected=="humain") {
                    jQuery("#choixclasse1humain, choixclasse2humain, choixclasse3humain, choixclasse4humain").css("display", "block");
                    jQuery("#choixclasse1ange, choixclasse2ange, choixclasse3ange, choixclasse4ange").css("display", "none");
                    jQuery("#choixclasse1demon, choixclasse2demon, choixclasse3demon, choixclasse4demon").css("display", "none");				
                }
                
                if(RaceSelected=="ange") {
                    jQuery("#choixclasse1humain, choixclasse2humain, choixclasse3humain, choixclasse4humain").css("display", "none");
                    jQuery("#choixclasse1ange, choixclasse2ange, choixclasse3ange, choixclasse4ange").css("display", "block");
                    jQuery("#choixclasse1demon, choixclasse2demon, choixclasse3demon, choixclasse4demon").css("display", "none");				
                }

                if(RaceSelected=="demon") {
                    jQuery("#choixclasse1humain, choixclasse2humain, choixclasse3humain, choixclasse4humain").css("display", "none");
                    jQuery("#choixclasse1ange, choixclasse2ange, choixclasse3ange, choixclasse4ange").css("display", "none");
                    jQuery("#choixclasse1demon, choixclasse2demon, choixclasse3demon, choixclasse4demon").css("display", "block");					
                }
	
	});
	
	jQuery(".choixgameplay").on("click", function () {
		if(GpSelected == this.name) {
                    	GpSelected = "";	
			jQuery("#gameplay").val("");

		} else {
			GpSelected = this.name;
			jQuery("#gameplay").val(this.name);
		}
                
                if(GpSelected == "T3") {
                    jQuery("#perso2,#perso3,#perso4").css("display", "none");
                }

                if(GpSelected == "T4") {
                    jQuery("#perso2,#perso3,#perso4").css("display", "block");
                }
	});

	
	/*jQuery(".hover_ange").hover(function() {
		//if(!jQuery(this).hasClass("lock")) {
		jQuery("#description_ange").css("display", "block");
		//}
	}, function() {
		//if(!jQuery(this).hasClass("lock")) {
		jQuery("#description_ange").css("display", "none");
		//}
	});
	
	jQuery(".hover_humain").hover(function() {
		//if(!jQuery(this).hasClass("lock")) {
			jQuery("#description_humain").css("display", "block");
		//}
	}, function() {
		//if(!jQuery(this).hasClass("lock")) {
		jQuery("#description_humain").css("display", "none");
		//}
	});

	jQuery(".hover_demon").hover(function() {
		//if(!jQuery(this).hasClass("lock")) {
		jQuery("#description_demon").css("display", "block");
		//}
	}, function() {
		//if(!jQuery(this).hasClass("lock")) {
		jQuery("#description_demon").css("display", "none");
		//}
	});	*/
});