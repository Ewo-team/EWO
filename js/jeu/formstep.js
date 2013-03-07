jQuery(window).ready(function() {
    var steps = $("fieldset");

	
        var count = steps.size();

        steps.each(function(i) {
            $(this).wrap("<div id='step" + i + "'></div>");
            $(this).append("<p id='step" + i + "commands'></p>");
            $(this).append("<p id='step" + i + "message'></p>");

            if (i == 0) {
                createNextButton(i);        // to do
                selectStep(i);                  // to do
            }
            else if (i == count - 1) {
                $("#step" + i).hide();
                createPrevButton(i);       // to do
                //createSubmitCheck(i);       // to do
            }
            else {
                $("#step" + i).hide();
                createPrevButton(i);       // to do
                createNextButton(i);       // to do
            }
        });
        
        function createPrevButton(i) {

            var stepName = "step" + i;

            $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Prev' class='prev button'>< En arrière!</a>");

            $("#" + stepName + "Prev").bind("click", function(e) {
                $("#" + stepName).hide();
                $("#step" + (i - 1)).show();
                selectStep(i - 1);
            });
        }        
      
        function createNextButton(i) {
            var stepName = "step" + i;
            $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Next' class='next button'>Etape suivante ></a>");
            $("#" + stepName + "Next").bind("click", function(e) {
                
                
                var fnc = $("#" + stepName + " > fieldset").data("validation");
                var result = true;
                
				messageErreur = "";
				
				if('function' == typeof(window[fnc])){
                    result = window[fnc]();
                }
				
                //$("#" + stepName + "message").html(messageErreur);
				
                if(result) {
					$("#" + stepName).hide();				
                    $("#step" + (i + 1)).show();
                    selectStep(i + 1);
                } else {
					alert(messageErreur);
					/*$("#" + stepName + "message").dialog({
					  height: 140,
					  modal: true
					});		*/		
				}
            });
        }  

		/*function createSubmitCheck(i) {
			var stepName = "step" + i;
			
			var btn = $("input.submit");
			
			
			btn.click(function(e) {
			
                var fnc = $("#" + stepName + " > fieldset").data("validation");
                var result = true;
                
				messageErreur = "";
				
				if('function' == typeof(window[fnc])){
                    result = window[fnc]();
                }
				
                //$("#" + stepName + "message").html(messageErreur);
				
                if(result) {
					jQuery(this).parent("form").submit();
                } else {
					alert(messageErreur);
					return false;
				}	
			});				
		}*/
        
        function selectStep(i) {
            $("#steps li").removeClass("current");
            $("#stepDesc" + i).addClass("current");
        }    
});

var messageErreur = "";