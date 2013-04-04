jQuery(document).ready(function(){
    if (sessionStorage != null) {
        var nomMessagerie = $("#mat_perso").val();

        // Si y'a des données dans sessionStorage, les ajouter à la page
        loadMessage(nomMessagerie);
    }
});

jQuery(window).unload(function() {
    if (sessionStorage != null) {
        var nomMessagerie = $("#mat_perso").val();
		
        // Enregistrer les données dans sessionStorage (si y'en a. Si vide, supprimer sessionStorage)
        saveMessage(nomMessagerie);
    }
});


function saveMessage(nom) {

    var textfield = $("#cke_contents_text > iframe")[0].contentDocument.body.innerHTML;
	
    // Pour tester si il y a du contenu, on récupère la valeur du formulaire, et supprime les espaces avant et après
    if(textfield.replace(/<[^>]*>/g,"") != "")
    {
        // Si il y a des données dans le formulaire, on les sauves dans sessionStorage
        sessionStorage[nom] = textfield;
    } else {
        // Sinon, on efface la clé de sessionStorage
        sessionStorage.removeItem(nom);
    }
}

function loadMessage(nom) {
    try {
        $("#text").val(sessionStorage[nom]);
    } catch(e) {
    // Problème, on ne le remonte pas
    }

}

function emptyForm() {
    $("#text").val("");
}

jQuery(document).ready( function() {
    $(".scroll").click( function() {
        var destination = $('#form-form').offset().top;
        $("html:not(:animated),body:not(:animated)").animate({
            scrollTop: destination-50
        }, 500 );	
    });

    $("#scrolltop").click( function() {
        $("html:not(:animated),body:not(:animated)").animate({
            scrollTop:0
        }, 'slow');
        return false;
    });		

    $("#balling-list").change( function() {
        if($(this).find("option:selected").val() == 'null') {
            $("#mat").removeAttr("disabled");
        } else {
            $("#mat").attr("disabled", true); 
        }
    });

    $('#bal-form').bind('submit', function() {
        bal_send(); 
        return false;
    });

    $('#cocheTout').click(function() { // clic sur la case cocher/decocher
           
        var cases = $("#cases").find(':checkbox'); // on cherche les checkbox qui dépendent de la liste 'cases'
        if(this.checked){ // si 'cocheTout' est coché
            cases.attr('checked', true); // on coche les cases
        //$('#cocheText').html('Tout decocher'); // mise à jour du texte de cocheText
        }else{ // si on décoche 'cocheTout'
            cases.attr('checked', false);// on coche les cases
        //$('#cocheText').html('Cocher tout');// mise à jour du texte de cocheText
        }          
              
    });
	
    $('.bal_listeid').click(function() {
        var bulle = $(this).next();
        $(bulle).toggle();	
    });	

    $(".curspointer").click( function(event) {
        var id = $(this).parent().parent().attr("id");
        if($(this).attr("title") == 'Supprimer') {
            if(confirm('Voulez-vous vraiment supprimer la liste ' + $("#"+id).children().html() + '?')) {
                actions('supp',id,mat);
            }
        }

        if($(this).attr("title") == 'Editer') {
            // aller à la page demandée
            //alert("Fonction pas encore ajoutée au jeu ^^");
            window.location.replace("listes.php?id="+mat+"&liste="+id.substr(2));
        }

        if($(this).attr("title") == 'Quitter') {
            if(confirm('Voulez-vous vraiment quitter la liste ' + $("#"+id).children().html() + '?')) {
                actions('quit',id,mat);
            }
        }			
    });

    $("#ajoute").click( function(event) {
        var mat = $("#mat").val();
        var idliste = $("#liste_id").val();

        $.post('liste_action.php', {
            action: "add", 
            id: idliste, 
            mat: proprio, 
            membre: mat
        }, function(data) {
            window.location.replace("listes.php?id="+proprio+"&liste="+idliste.substr(2));
        });				

    });

    $(".curspointer").click( function(event) {
        var id = $(this).parent().parent().attr("id");
        var idliste = $("#liste_id").val();

        if($(this).attr("title") == 'Supprimer') {
            if(confirm('Voulez-vous vraiment supprimer ' + $("#"+id).children().html() + ' la liste ?')) {

                $.post('liste_action.php', {
                    action: "renvoi", 
                    id: idliste, 
                    mat: proprio, 
                    membre: id
                }, function(data) {
                    if(data == 'ok') {
                        $("#"+id).remove();
                    }
                });						
            }
        }
    });
});

var actions = function(act, idliste, matricule) {
    $.post('liste_action.php', {
        action: act, 
        id: idliste, 
        mat: matricule
    }, function(data) {
        if(data == 'ok') {
            $("#"+idliste).remove();
        }
    });	
};


/**
* Affiche la bal / charge la bal / masque la bal
*/
function afficher_bal(id_bal,auteur,page){
    if(balid[id_bal][0] == 0){
        if(balid[id_bal][1] == 0){

            var xhr = getXhr()
				
            // On défini ce qu'on va faire quand on aura la réponse
            xhr.onreadystatechange = function(){
                // On ne fait quelque chose que si on a tout reçu et que le serveur est ok
                if(xhr.readyState == 4 && xhr.status == 200){			
                    if(xhr.responseText != 'null'){
                        document.getElementById('bal_content_'+id_bal).innerHTML = xhr.responseText;
                        $('#bal_'+id_bal).toggle();
                        //Effect.toggle('bal_'+id_bal,'blind',{ duration: 0.1 });
                        balid[id_bal][0] = 1;
                        balid[id_bal][1] = 1;
                        //Si bal non lu on change la couleur et le flag dans la bbd
                        bal_lu(id_bal,auteur, page);
                    }else{
                        alert('Erreur, aucune bal ne correspond à votre demande.');
                    }
                }
            }
				
            // Envoi en GET
            xhr.open("GET","../messagerie/affiche_bal.php?id="+id_bal+"&exp="+auteur+"&page="+page,true);
            xhr.send(null);	

        }else{
            $('#bal_'+id_bal).toggle();
            //Effect.toggle('bal_'+id_bal,'blind',{ duration: 0.1 });
            balid[id_bal][0] = 0;
        }
    }else{
        $('#bal_'+id_bal).toggle();
        //Effect.toggle('bal_'+id_bal,'blind',{ duration: 0.1 });
        balid[id_bal][0] = 0;
    }
}

function repondre(Mat,Sujet,Liste){
    $(document).ready( function() {
        $("#mat").val(Mat);
        $("#titre").val(Sujet);
        //$('#balling-list > option:selected').removeAttr("selected"); 
        if(Liste != 'null') {
            $("#mat").val('');
        }
        $('#balling-list > option[value="'+Liste+'"]').attr("selected", true); 
        $('#balling-list').change();		
    });
}

function contacter(Mat,Sujet,Liste){
    $(document).ready( function() {
        $("#mat").val(Mat);
        $("#titre").val(Sujet);
        //$('#balling-list > option:selected').removeAttr("selected"); 
        if(Liste != 'null') {
            $("#mat").val('');
        }
        $('#balling-list > option[value="'+Liste+'"]').attr("selected", true); 
        $('#balling-list').change();		
    });
}


/**
* Supprime la bal - new
*/
function bal_supprime(id,perso){
    $("#titre_"+id).remove();
    $("#cont_"+id).remove();

    //gestion de la decrem des bal
    if($("#titre_"+id).hasClass("bal_titre_nonlu")){
        decrem_nb_bal(perso);
    }
}

/**
* Change le statut d'une bal en lu.
*/
function bal_lu(id, perso, page){
    var bal_lu = document.getElementById('titre_'+id).className;
    if (bal_lu == 'bal_titre_nonlu') {

        $.get("../messagerie/bal_lu.php?id="+id+"&exp="+perso+"&page="+page, function(data) {
            $('#titre_'+id).removeClass();
            $('#titre_'+id).addClass('bal_titre_lu');
            if (page == 'recu'){
                decrem_nb_bal(perso);
            }			

        });

    }
}

/**
* Envoie les bals
*/
function bal_send() {
    var vMat = $('#mat').val();
    var vTitre = $('#titre').val();
    var vMatperso = $('#mat_perso').val();
    var vListe = $('#balling-list').val();
    var vType_message = $('#type_message').val();
    var vText = $('#text').val();
	
    $("#form-ok").empty();
    $("#form-load").show();
    $("#form-form").hide();//cache le formulaire et affiche l'image de loading	
	
    try {
        $.post('../messagerie/send_bal.php',
        { 
            ajax: '1',
            mat: vMat,
            titre: vTitre, 
            matperso: vMatperso, 
            liste: vListe, 
            type_message: vType_message, 
            text: encodeURIComponent(vText)
        },
        function(data) {
            if(data == 'ok') {
                $("#form-ok").empty();
                $("#form-form").show();
                $("#form-load").hide();
                $("#form-ok").append("<b>Message remis à tous les destinataires</b>");
                $("#mat").removeClass("form-erreur");
                $("#titre").removeClass("form-erreur");
                $("#text").removeClass("form-erreur");	
                emptyForm();		
            } else if(data == 'erreur') {
                $("#form-ok").empty();
                $("#form-form").show();
                $("#form-load").hide();                     
                $("#mat").addClass("form-erreur");
                $("#titre").addClass("form-erreur");
                $("#text").addClass("form-erreur");			
            } else {
                $("#form-ok").empty();
                $("#form-ok").append("<b>Ces matricules sont incorrects : "+data+"</b>");
                $("#form-form").show();
                $("#form-load").hide();
                $("#mat").addClass("form-erreur");
                $("#titre").removeClass("form-erreur");
                $("#text").removeClass("form-erreur");//afficher			
            }
            return false;
        });
    } catch(e) {
        $("#form-ok").empty();
        $("#form-form").show();
        $("#form-load").hide();                     
        $("#mat").addClass("form-erreur");
        $("#titre").addClass("form-erreur");
        $("#text").addClass("form-erreur");		
        return false;
    }
}

/**
* Suppression des bals recus
*/
function bal_del(id, perso,page){
    var xhr = getXhr()
				
    // On défini ce qu'on va faire quand on aura la réponse
    xhr.onreadystatechange = function(){
        // On ne fait quelque chose que si on a tout reçu et que le serveur est ok
        if(xhr.readyState == 4 && xhr.status == 200){			
            if(xhr.responseText != 'null'){
                bal_supprime(id,perso);
            }
        }
    }
				
    // Envoi en GET
    xhr.open("GET","../messagerie/del_bal.php?id="+id+"&exp="+perso+"&page="+page,true);
    xhr.send(null);
}

/**
* Archivage des bal
*/
function bal_archive(id, perso){
    var xhr = getXhr()
				
    // On défini ce qu'on va faire quand on aura la réponse
    xhr.onreadystatechange = function(){
        // On ne fait quelque chose que si on a tout reçu et que le serveur est ok
        if(xhr.readyState == 4 && xhr.status == 200){			
            if(xhr.responseText == 'true'){
                bal_supprime(id,perso);
            }else{
                alert('Une erreur d\'archivage s\'est produite');
            }
        }
    }
				
    // Envoi en GET
    xhr.open("GET","../messagerie/bal_archive.php?id="+id+"&exp="+perso,true);
    xhr.send(null);
}

/**
* Suppression de tous les bals send
*/
function bal_del_all(perso, page){
    var cases = $("#cases").find(':checkbox');
    for (var i=0;i < cases.length; i++){
        if ( cases[i].checked ){
            var id_bal = cases[i].value;
            if($("#titre_"+id_bal).hasClass("bal_titre_nonlu")){
                decrem_nb_bal(perso);
            }
            bal_del(id_bal, perso, page);
        }
    }
}

/**
* Marquage comme lu de tous les bals
*/
function bal_lu_all(perso,page){
    var cases = $("#cases").find(':checkbox');
    for (var i=0;i < cases.length; i++){
        if ( cases[i].checked ){
            var id_bal = cases[i].value;
            if($("#titre_"+id_bal).hasClass("bal_titre_nonlu")){
                bal_lu(id_bal, perso, page);									
            }
        }
    }
}

/**
* Archivage de tous les bals
*/
function bal_archive_all(perso){
    var cases = $("#cases").find(':checkbox');
    for (var i=0;i < cases.length; i++){
        if ( cases[i].checked ){
            var id_bal = cases[i].value;
            if($("#titre_"+id_bal).hasClass("bal_titre_nonlu")){
                decrem_nb_bal(perso);
            }
            bal_archive(id_bal, perso);							
        }
    }
}

/**
* decremente le nb de bal afficher en titre
*/
function decrem_nb_bal(perso){
    var bal_total = $('#bal_total').text();
    var bal_perso = $('#total_bal_'+perso).text();

    var nb_bal_total = parseInt(bal_total)-1;
    var nb_bal_perso = parseInt(bal_perso)-1;

    $('#bal_total').empty();  
    $('#bal_total').append(nb_bal_total);

    $('#total_bal_'+perso).empty();  
    $('#total_bal_'+perso).append(nb_bal_perso);
}

/**
* Mise ou demise en favoris
*/
function bal_fav(id,perso){
    var xhr = getXhr()
				
    // On défini ce qu'on va faire quand on aura la réponse
    xhr.onreadystatechange = function(){
        // On ne fait quelque chose que si on a tout reçu et que le serveur est ok
        if(xhr.readyState == 4 && xhr.status == 200){			
            if(xhr.responseText != 'null'){
                document.getElementById("bal_fav_"+id).src = "../images/site/fav_"+xhr.responseText+".png";
            }else{
                alert(xhr.responseText);
            }
        }
    }
				
    // Envoi en GET
    xhr.open("GET","../messagerie/bal_fav.php?id="+id+"&exp="+perso,true);
    xhr.send(null);	
}
