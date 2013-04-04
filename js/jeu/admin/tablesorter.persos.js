jQuery(document).ready(function() {
    "use strict";

  var pagerOptions = {
        // target the pager markup - see the HTML block below
        container: $(".pager"),
        // output string - default is '{page}/{totalPages}'; possible variables: {page}, {totalPages}, {startRow}, {endRow} and {totalRows}
        output: '{startRow} - {endRow} / {filteredRows} ({totalRows})',
        // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
        // table row set to a height to compensate; default is false
        fixedHeight: true,
        // remove rows from the table to speed up the sort of large tables.
        // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
        removeRows: false,
        // go to page selector - select dropdown that sets the current page
        cssGoto:     '.gotoPage'
  };

    jQuery("#liste_persos").tablesorter({
        widthFixed : true,
        widgets: ["zebra"],

        widgetOptions : {
              filter_columnFilters : true,
              filter_hideFilters : false,
              filter_ignoreCase : true,
              filter_functions : {            
                3 : {
                    "Anges"      : function(e, n, f, i) { return n == "ange"; },
                    "Démon"      : function(e, n, f, i) { return n == "demon"; },
                    "Humain"      : function(e, n, f, i) { return n == "humain"; },
                    "Paria"      : function(e, n, f, i) { return n == "paria"; },
                    "Autre"      : function(e, n, f, i) { return (n != "ange" && n != "demon" && n != "humain" && n != "paria"); }                    
                },
                4 : {
                    "T3"      : function(e, n, f, i) { return n == 3; },
                    "T4"      : function(e, n, f, i) { return n == 4; },                 
                }                    
              }

        }        
    }).tablesorterPager(pagerOptions);

    jQuery.get("liste_ajax.php", function(html) {

      jQuery("#liste_persos tbody").append(html);

      jQuery("#liste_persos")[0].config.widgets =  ["zebra", "filter"];

      jQuery("#liste_persos th").removeClass("sorter-false");

      var resort = true;
      $("#liste_persos").trigger("update", [resort]);
      $('#liste_persos').trigger('applyWidgets');
    });
});