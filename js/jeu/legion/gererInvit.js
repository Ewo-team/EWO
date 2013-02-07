            var idSel = 0;
	$(window).ready(function() {
		$( "#name_input" ).autocomplete({
			source: "../../ajax/persos.php?race=" + getRace + "&",
                        delay: 0,
			select: function( event, ui ) {
				if(ui.item){
                                    idSel  = ui.item.id;
                                    $("#invitation_mat").val(idSel);
                                }
			}
		});
	});
        function valide(){
           return idSel != 0;
        }