jQuery(window).ready(function() {
    "use strict";
    var cache = {},
        lastXhr;
    jQuery("#pseudo_perso").autocomplete({
        minLength: 2,
        source: function(request, response) {
            var term = request.term;
            if (term in cache) {
                response(cache[term]);
                return;
            }
            lastXhr = jQuery.getJSON("liste_ajax.php", request, function(data, status, xhr) {
                cache[term] = data;
                if (xhr === lastXhr) {
                    response(data);
                }
            });
        }
    });
    jQuery("#search").autocomplete({
        source: autocomplete_url,
        delay: 0,
        minLength: 0,
        select: function(event, ui) {
            if (ui.item) {
                jQuery("#mat").val(ui.item.id);
                jQuery("#mat").change();
            }
        }
    });
    jQuery("#form").validate();
    jQuery('select[name="eventId"]').change(function() {
        if (jQuery(this).val() === 0) {
            jQuery('textarea[name="event"]').show();
        } else {
            jQuery('textarea[name="event"]').hide();
        }
    });

    function tp() {
        if (confirm('Attention, la téléportation va vous faire perdre 1/3 de vos PVs')) {
            return true;
        }
        return false;
    }

    function grade() {
        jQuery.getJSON('../ajax/grade_galon.php', {
            mat: jQuery('input[name="mat"]').val()
        }, function(data) {
            if (data.grade !== undefined && data.galon !== undefined) {
                jQuery('input[name="grade_perso"]').val(data.grade).change();
                jQuery('input[name="galon_perso"]').val(data.galon).change();
            }
        });
    }

    function controlGrade() {
        var grade = jQuery('input[name="grade_perso"]').val();
        if (!jQuery.isNumeric(grade) || grade < 0) {
            grade = 0;
        }
        if (grade > 5) {
            grade = 5;
        }
        jQuery('input[name="grade_perso"]').val(grade);
    }

    function controlGalon() {
        var galon = jQuery('input[name="galon_perso"]').val();
        if (!jQuery.isNumeric(galon) || galon < 1) {
            galon = 1;
        }
        if (galon > 4) {
            galon = 4;
        }
        jQuery('input[name="galon_perso"]').val(galon);
    }
});