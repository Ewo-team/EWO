var idSel = 0;
var races = new Array();

function changeRace() {

	jQuery("#chef").autocomplete(
		"option",
		"source", "../../ajax/persos.php?race=" + jQuery('#race').val()
	);
}

jQuery(window).ready(function() {
	jQuery("#del").click(function() {
		jQuery(this).attr("disabled", "true");
		jQuery("#confirmDel").slideToggle();
	});
	jQuery("#cancel").click(function() {
		jQuery("#del").removeAttr("disabled");
		jQuery("#confirmDel").slideToggle();
	});

	jQuery("#chef").autocomplete({
		source : "../../ajax/persos.php?race=" + jQuery('#race').val(),
		delay : 0,
		minLength : 0,
		select : function(event, ui) {
			if (ui.item) {
				idSel = ui.item.id;
				jQuery("#chef_mat").val(idSel);
			}
		}
	});
	jQuery('#race').change(function(){
		changeRace();
	});
	
	// Editeur
	jQuery('#editDescr').ckeditor({
		toolbar : 'Basic'
	});
	// Recherche Légion
	jQuery('#search').autocomplete({
		source : "../../ajax/legion.php",
		delay : 0,
		source : function(request, response) {
			jQuery.getJSON("../../ajax/legion.php", {
				term : request.term
			}, function(data) {
				response(jQuery.map(data, function(item) {
					return {
						label : item.label,
						value : item.label,
						model : item.value
					}
				}));
			});
		},
		minLength : 0,
		select : function(event, ui) {
			if (ui.item) {
				if (is_admin) {
					jQuery("#editName").val(ui.item.model.nom);
				} else {
					jQuery("#editName").html(ui.item.model.nom);
				}
				jQuery('#editId').val(ui.item.model.id);

				jQuery("#editRace").html(races[ui.item.model.race]);
				jQuery('#editDescr').val(ui.item.model.descr);
				jQuery('#editType option').filter(function() {
					return jQuery(this).attr('value') == ui.item.model.type;
				}).attr('selected', true);
				jQuery('#editAlign option').filter(function() {
					return jQuery(this).attr('value') == ui.item.model.align;
				}).attr('selected', true);
			}
		}
	});
});