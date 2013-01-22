jQuery(document).ready(function() {
    "use strict";
    jQuery("#actions").tablesorter({
        sortList: [
            [1, 0]
        ],
        widgets: ['zebra'],
        cssHeader: "actionheader"
    }).tablesorterPager({
        container: $("#pager"),
        size: 25,
        positionFixed: false
    });
	
	jQuery(".dialog").on("click", function() {
		jQuery(this).find(".value").dialog();
	});	
});