var popupStatus = 0;
jQuery(document).ready(function() {
    "use strict";
    jQuery(".popup").click(function() {
        var cle = jQuery(this).attr("id");
        var nom = jQuery(this).html();
        jQuery("#popup > h1").html(nom);
        jQuery("#ical_link").val("http://ewo-le-monde.com/api/ical.php?k=" + cle);
        centerPopup();
        loadPopup();
    });

    function loadPopup() {
        if (popupStatus === 0) {
            jQuery("#backgroundPopup").css({
                "opacity": "0.7"
            });
            jQuery("#popup").fadeIn("fast");
            popupStatus = 1;
        }
    }

    function disablePopup() {
        if (popupStatus === 1) {
            jQuery("#popup").fadeOut("fast");
            popupStatus = 0;
        }
    }

    function centerPopup() {
        var windowWidth = document.documentElement.clientWidth;
        var windowHeight = document.documentElement.clientHeight;
        var popupHeight = jQuery("#popup").height();
        var popupWidth = jQuery("#popup").width();
        //centering  
        jQuery("#popup").css({
            "position": "absolute",
            "left": windowWidth / 2 - popupWidth
        });
    }
    jQuery("#popupClose").click(function() {
        disablePopup();
    });
    jQuery("#popup > input").click(function() {
        jQuery(this).select();
    });
    jQuery(document).keypress(function(e) {
        if (e.keyCode === 27 && popupStatus === 1) {
            disablePopup();
        }
    });
});