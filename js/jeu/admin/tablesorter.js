jQuery(document).ready(function() {
    "use strict";
    jQuery.tablesorter.addParser({
        // set a unique id 
        id: 'cercles',
        is: function(s) {
            // return false so this parser is not auto detected 
            return false;
        },
        format: function(s) {
            // format your data for normalization 
            return s.toLowerCase().replace(/techno/, 7).replace(/paria/, 6).replace(/soin/, 5).replace(/effrois/, 4).replace(/et/, 3).replace(/glace/, 2).replace(/feu/, 1).replace(/novice/, 0);
        },
        // set type, either numeric or text 
        type: 'numeric'
    });
    jQuery("#actions").tablesorter({
        sortList: [
            [3, 0],
            [4, 0]
        ],
        widgets: ['zebra'],
        headers: {
            3: {
                sorter: 'cercles'
            },
            4: {
                sorter: 'races'
            },
            11: {
                sorter: false
            }
        },
        cssHeader: "actionheader"
    }).tablesorterPager({
        container: jQuery("#pager"),
        size: 25,
        positionFixed: false
    });
    jQuery(".delete").click(function() {
        if (!confirm("Voulez-vous vraiment supprimer cette action ?")) {
            return false;
        }
    });
});