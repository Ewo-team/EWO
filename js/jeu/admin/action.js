jQuery(document).ready(function() {
    "use strict";
    jQuery(".deleffect").live('click', function() {
        var element = jQuery(this);
        element.parent().remove();
    });
    jQuery(".addeffect").click(function(event) {
        if (this.id === 'new_effets_lanceur') {
            var element = jQuery("<tr></tr>");
            var idSelect = 'effet_type_lanceur[' + jQuery('#index_lanceur').val() + ']';
            var idInput = 'effet_valeur_lanceur[' + jQuery('#index_lanceur').val() + ']';
            var col1 = jQuery("<td></td>");
            var col2 = jQuery("<td></td>");
            var col3 = jQuery("<td></td>");
            col3.addClass("deleffect");
            jQuery("#nouveaulanceur").clone().appendTo(col1);
            jQuery("#new_valeur_lanceur").clone().appendTo(col2);
            jQuery("<img>").appendTo(col3).attr("src", root_url + "/images/site/delete.png");
            element.append(col1).append(col2).append(col3);
            element.find("#nouveaulanceur").removeAttr("disabled");
            element.find("#nouveaulanceur").attr("name", idSelect);
            element.find("#new_valeur_lanceur").attr("name", idInput);
            element.find("#nouveaulanceur").removeAttr("id");
            element.find("#new_valeur_lanceur").removeAttr("id");
            jQuery("#e_lanceur").prepend(element);
            jQuery('#index_lanceur').val(1 * jQuery('#index_lanceur').val() + 1);
        } else {
            var element = jQuery("<tr></tr>");
            var idSelect = 'effet_type_cible[' + jQuery('#index_cible').val() + ']';
            var idInput = 'effet_valeur_cible[' + jQuery('#index_cible').val() + ']';
            var col1 = jQuery("<td></td>");
            var col2 = jQuery("<td></td>");
            var col3 = jQuery("<td></td>");
            col3.addClass("deleffect");
            jQuery("#nouveaucible").clone().appendTo(col1);
            jQuery("#new_valeur_cible").clone().appendTo(col2);
            jQuery("<img>").appendTo(col3).attr("src", "<?php echo jQueryroot_url; ?>/images/site/delete.png");
            element.append(col1).append(col2).append(col3);
            element.find("#nouveaucible").removeAttr("disabled");
            element.find("#nouveaucible").attr("name", idSelect);
            element.find("#new_valeur_cible").attr("name", idInput);
            element.find("#nouveaucible").removeAttr("id");
            element.find("#new_valeur_cible").removeAttr("id");
            jQuery("#e_cibles").prepend(element);
            jQuery('#index_cible').val(1 * jQuery('#index_cible').val() + 1);
        }
    });
    jQuery('.changeNom').change(function() {
        //alert("changement");
        if (jQuery(".changeNom:checked").val() === 'simple') {
            jQuery(".nommultiple").hide();
            jQuery("#libelleNom").html("Nom de l'action:");
        } else {
            jQuery(".nommultiple").show();
            jQuery("#libelleNom").html("Nom pour les Humains:");
        }
    });
    jQuery(".changeNom").change();
});