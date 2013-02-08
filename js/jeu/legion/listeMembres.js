
function updateGrade(mat, id, v){
    $.post(
        '../../ajax/membres.php',
        {
            'mat':mat,
            'id':id,
            'value':v
        });
}

$(window).ready(function(){
    $("#listeMembres").columnizeList({cols: 2});
});

function virer(mat, nom){
    $("#matDelMembre").val(mat);
    $("#nomDelMembre").html(nom);
    $("#confirmDelMembre").slideDown();
}
jQuery(window).ready(function(){
    $("#cancelMembre").click(function(){
        $("#confirmDelMembre").slideUp();
    });
});