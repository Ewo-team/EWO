jQuery(window).ready(function() {



	jQuery(".choixrace").on("click", function() {
		
		jQuery(".choixrace.button_selected").removeClass("button_selected");
		
		if(RaceSelected == "") {
			jQuery("#race").val(this.name);
			RaceSelected = this.name;
			jQuery(this).addClass("button_selected");
		} else {
			jQuery("#race").val("");
			RaceSelected = "";		
		}	
                
		if(RaceSelected=="humain") {
			jQuery("#choixclasse1humain, #choixclasse2humain, #choixclasse3humain, #choixclasse4humain").css("display", "block");
			jQuery("#choixclasse1ange, #choixclasse2ange, #choixclasse3ange, #choixclasse4ange").css("display", "none");
			jQuery("#choixclasse1demon, #choixclasse2demon, #choixclasse3demon, #choixclasse4demon").css("display", "none");	
			jQuery("#classe1").css("display", "block");
			jQuery("#classe3").css("display", "none");
			jQuery("#classe4").css("display", "none");
		}
		
		if(RaceSelected=="ange") {
			jQuery("#choixclasse1humain, #choixclasse2humain, #choixclasse3humain, #choixclasse4humain").css("display", "none");
			jQuery("#choixclasse1ange, #choixclasse2ange, #choixclasse3ange, #choixclasse4ange").css("display", "block");
			jQuery("#choixclasse1demon, #choixclasse2demon, #choixclasse3demon, #choixclasse4demon").css("display", "none");		
			jQuery("#classe1").css("display", "none");
			jQuery("#classe3").css("display", "block");
			jQuery("#classe4").css("display", "none");
		}

		if(RaceSelected=="demon") {
			jQuery("#choixclasse1humain, #choixclasse2humain, #choixclasse3humain, #choixclasse4humain").css("display", "none");
			jQuery("#choixclasse1ange, #choixclasse2ange, #choixclasse3ange, #choixclasse4ange").css("display", "none");
			jQuery("#choixclasse1demon, #choixclasse2demon, #choixclasse3demon, #choixclasse4demon").css("display", "block");	
			jQuery("#classe1").css("display", "none");
			jQuery("#classe3").css("display", "none");
			jQuery("#classe4").css("display", "block");
		}
	
	});
	
	jQuery(".choixgameplay").on("click", function () {
	
		jQuery(".choixgameplay.button_selected").removeClass("button_selected");
	
		if(GpSelected == this.name) {
            GpSelected = "";	
			jQuery("#gameplay").val("");
		} else {
			GpSelected = this.name;
			jQuery("#gameplay").val(this.name);
			jQuery(this).addClass("button_selected");
		}
                
		if(GpSelected == "T3") {
			jQuery("#perso2,#perso3,#perso4").css("display", "none");
		}

		if(GpSelected == "T4") {
			jQuery("#perso2,#perso3,#perso4").css("display", "block");
		}
	});
	
	jQuery("#creation_perso").submit(function() {
		messageErreur = "";

		result = creation_perso();
		
		if(result) {
			return true;
		} else {
			alert(messageErreur);
			return false;
		}			
	});
	

	
	/*function validationScript(fnc) {
	
		var result = true;
	
		result = window[fnc]();
	}*/

	
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

var RaceSelected = "";
var GpSelected = "";

function choix_du_gp() {
	var result = true;
	
	if(GpSelected == "") {
		result = false;
		messageErreur = "Veuillez choisir le gameplay";
	}
	
	return result;
}

function choix_du_camp() {
	var result = true;

	if(RaceSelected == "") {
		result = false;
		messageErreur = "Veuillez choisir le camps";
	}
	
	return result;
}	

function creation_perso() {
	var result = true;
	
	var msg = "";
	
	var nom1 = jQuery("#nom1").val(),
		sexe1 = jQuery("#sexe").val(),
		classe1,
		nom2 = jQuery("[name='nom2']").val(),
		sexe2 = jQuery("[name='sexe2']").val(),
		classe2,
		nom3 = jQuery("[name='nom3']").val(),
		sexe3 = jQuery("[name='sexe3']").val(),
		classe3
		nom4 = jQuery("[name='nom4']").val(),
		sexe4 = jQuery("[name='sexe4']").val(),
		classe4;
		

	classe1 = jQuery("#choixclasse1" + RaceSelected).val();
	classe2 = jQuery("#choixclasse2" + RaceSelected).val();
	classe3 = jQuery("#choixclasse3" + RaceSelected).val();
	classe4 = jQuery("#choixclasse4" + RaceSelected).val();
	
	if(nom1 == "") {
		result = false;
		msg += "Votre personnage doit avoir un nom.\n";
	}

	if(sexe1 == "") {
		result = false;
		msg += "Votre personnage doit avoir un sexe.\n";
	}

	if(classe1 == "") {
		result = false;
		msg += "Votre personnage doit avoir une classe.\n";
	}

	
	if(GpSelected == "T4") {
		if(nom2 == "") {
			result = false;
			msg += "Votre 2ème personnage doit avoir un nom.\n";
		}
		
		if(nom3 == "") {
			result = false;
			msg += "Votre 3ème personnage doit avoir un nom.\n";
		}

		if(nom4 == "") {
			result = false;
			msg += "Votre 4ème personnage doit avoir un nom.\n";
		}

		if(sexe2 == "") {
			result = false;
			msg += "Votre 2ème personnage doit avoir un sexe.\n";
		}
		
		if(sexe3 == "") {
			result = false;
			msg += "Votre 3ème personnage doit avoir un sexe.\n";
		}

		if(sexe4 == "") {
			result = false;
			msg += "Votre 4ème personnage doit avoir un sexe.\n";
		}

		if(classe2 == "") {
			result = false;
			msg += "Votre 2ème personnage doit avoir une classe.\n";
		}
		
		if(classe3 == "") {
			result = false;
			msg += "Votre 3ème personnage doit avoir une classe.\n";
		}

		if(classe4 == "") {
			result = false;
			msg += "Votre 4ème personnage doit avoir une classe.\n";
		}	
	}
	
	messageErreur = msg;
	return result;

}