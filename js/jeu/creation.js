jQuery(window).ready(function() {

	var RaceSelected = "";
	var GpSelected = "";

	jQuery(".choixrace").on("click", function() {
		
		if(RaceSelected == "") {
			jQuery("#description_" + this.name).css("display", "block");
			jQuery(this).addClass("button_selected");
			jQuery("#race").val(this.name);
			RaceSelected = this.name;
		} else {
			jQuery("#description_" + this.name).css("display", "none");
			jQuery(this).removeClass("button_selected");
			jQuery("#race").val("");
			RaceSelected = "";		
		}
		
		if(RaceSelected != "" && GpSelected != "") {
			jQuery("#suite").removeAttr('disabled');
		} else {
			jQuery("#suite").attr('disabled', '');
		}		
	
	});
	
	jQuery(".choixgameplay").on("click", function () {
		if(GpSelected == "") {
			jQuery(this).addClass("button_selected");
			GpSelected = this.name;
			jQuery("#gameplay").val(this.name);
		} else {
			jQuery(this).removeClass("button_selected");		
			GpSelected = "";	
			jQuery("#gameplay").val("");
		}
		
		if(RaceSelected != "" && GpSelected != "") {
			jQuery("#suite").removeAttr('disabled');
		} else {
			jQuery("#suite").attr('disabled', '');
		}
	});

	
	jQuery(".hover_ange").hover(function() {
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
	});	
});