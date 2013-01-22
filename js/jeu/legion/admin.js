        var idSel = 0;
        var races = new Array();

        jQuery(window).ready(function() {
            $("#del").click(function(){
                $(this).attr("disabled", "true");
                $("#confirmDel").slideToggle();
            });
            $("#cancel").click(function(){
                $("#del").removeAttr("disabled");
                $("#confirmDel").slideToggle();
            });

            $("#chef" ).autocomplete({
                source: "../ajax/persos.php?race="+$('#race').val()+"&",
                delay: 0,
                select: function( event, ui ) {
                    if(ui.item){
                        idSel  = ui.item.id;
                        $("#chef_mat").val(idSel);
                    }
                }
            });
            //Editeur
            jQuery('#editDescr').ckeditor({ toolbar : 'Basic' } );
            //Recherche Légion
            jQuery('#search').autocomplete({
                source: "ajax/legion.php",
                delay: 0,
                source: function( request, response ) {
                    jQuery.getJSON(
                        "../ajax/legion.php",
                        {
                            term: request.term
                        },
                        function( data ) {
                            response( jQuery.map( data, function( item ) {
                                return {
                                    label: item.label,
                                    value: item.label,
                                    model: item.value
                                }
                            }));
                        }
                    );
                },
                minLength: 0,
                select: function( event, ui ) {
                   if(ui.item){
                        if(is_admin) {
                            jQuery("#editName").val(ui.item.model.nom);
                        } else {
                            jQuery("#editName").html(ui.item.model.nom);
                        }
                        jQuery('#editId').val(ui.item.model.id);
                        jQuery("#editRace").html(races[ui.item.model.race]);
                        jQuery('#editDescr').val(ui.item.model.descr);
                        jQuery('#editType option').filter(function() {return $(this).attr('value') == ui.item.model.type;})
                            .attr('selected', true);
                        jQuery('#editAlign option').filter(function() {return $(this).attr('value') == ui.item.model.align;})
                            .attr('selected', true);
                   }
                }
            });
        });