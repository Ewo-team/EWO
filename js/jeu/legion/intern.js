jQuery(window).ready(function() {
    $("#del").click(function(){
        $(this).attr("disabled", "true");
        $("#confirmDel").slideToggle();
    });
    $("#cancel").click(function(){
        $("#del").removeAttr("disabled");
        $("#confirmDel").slideToggle();
    });
});