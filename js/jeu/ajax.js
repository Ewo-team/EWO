/**
* retourne un objet xmlHttpRequest.
* méthode compatible entre tous les navigateurs (IE/Firefox/Opera)
*/
function getXhr(){
  var xhr=null;
  if(window.XMLHttpRequest) // Firefox et autres
  xhr = new XMLHttpRequest();
  else if(window.ActiveXObject){ // Internet Explorer
    try {
      xhr = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e1) {
        xhr = null;
      }
    }
  }
  else { // XMLHttpRequest non supporté par le navigateur
    alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
  }
  return xhr;
}

/**
* Déclaration des var globale
*/
var edit_area = 0;
var drop_flot = 1;
var info_bulle = 0;
var ecran_notif = 0;
var temps_decoule = 2000;
var lastid = -1;
var session_on_var = 1;
/**
* fonction stripslashes et addslashes equivalente à celle en php
*/
function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\\\\/g,'\\');
	str=str.replace(/\\0/g,'\0');
return str;
}
function addslashes(str) {
	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\0/g,'\\0');
return str;
}

function chariot(variable){
var retour = variable.split("\n");
return retour[1];
}


function wordwrap (str, int_width, str_break, cut) {
    var m = ((arguments.length >= 2) ? arguments[1] : 75   );
    var b = ((arguments.length >= 3) ? arguments[2] : "\n" );    var c = ((arguments.length >= 4) ? arguments[3] : false);
 
    var i, j, l, s, r;
 
    str += ''; 
    if (m < 1) {
        return str;
    }
     for (i = -1, l = (r = str.split(/\r\n|\n|\r/)).length; ++i < l; r[i] += s) {
        for (s = r[i], r[i] = ""; s.length > m; r[i] += s.slice(0, j) + ((s = s.slice(j)).length ? b : "")){
            j = c == 2 || (j = s.slice(0, m + 1).match(/\S*(\s)?$/))[1] ? m : j.input.length - j[0].length || c == 1 && m || j.input.length + (j = s.slice(m).match(/^\S*/)).input.length;
        }
    }    
    return r.join("\n");
}

/**
* Efface le texte dans un input
*/
function efface(id){
	document.getElementById(id).value = "";
}

function ucfirst( str ) { 
    var f = str.charAt(0).toUpperCase();
    return f + str.substr(1, str.length-1);
}

/*
* Affiche masque #
*/
function hideshow(button,div){
	$(button).click(function () {
		$(div).toggle();
	});
}


/**
* Edition du message du jour
*/
function edition_click_mdj(id){
	if(edit_area == 0){
		var text = $("#"+id).text();
		if (text == '-Mdj-'){
			textarea = '';
		}else{
			textarea = text;
		}
		$("#"+id+"_p").html("<textarea id='"+id+"' name='valeur' class='form_infobulle' rows=6>"+textarea+"</textarea><br /><input type='button' value='Ok' onclick=\"edition_get_mdj('"+id+"');\"/>");
		edit_area = 1;
	}
}

/**
* Edition du message du jour
*/
function edition_get_mdj(id){
	var text = $("#"+id).val();
		var xhr = getXhr()
		xhr.onreadystatechange = function(){
		//- Ajax : X Y carte_ID artefact_ID Nom
		if(xhr.readyState == 4 && xhr.status == 200){			
					if(xhr.responseText != 'null'){
						if(xhr.responseText == ''){
							textarea = '-Mdj-';
						}else{
							textarea = wordwrap(text, 25, "<br />", true);
						}
						$("#"+id+"_p").html("<span id='"+id+"' onclick=\"edition_click_mdj(this.id);\">"+textarea+"</span>");
						edit_area = 0;
					}
				}
			}
	// Envoi en GET -- 
	xhr.open("GET","../jeu/bdd_mdj.php?mdj="+text,true);
	xhr.send(null);
}

/**
* Augmente de 1 le des d'attaque
*/
function des_plus(){
var des_def = document.gestion_des.des_defense.value;
 	if (des_def>1){
		document.gestion_des.des_defense.value--;
		document.gestion_des.des_attaque.value++;
	}								
}

/** 
* Augmente de 1 le des d'attaque
*/
function des_max(){
var des_def = document.gestion_des.des_defense.value;
 	while (des_def>1){
		document.gestion_des.des_defense.value--;
		document.gestion_des.des_attaque.value++;
		des_def--;
	}								
}

/**
* Reduit de 1 le des d'attaque
*/
function des_moins(){						
var des_att = document.gestion_des.des_attaque.value;
 	if (des_att>1){
		document.gestion_des.des_defense.value++;
		document.gestion_des.des_attaque.value--;
	}								
}

/**
* Reduit de 1 le des d'attaque
*/
function des_min(){						
var des_att = document.gestion_des.des_attaque.value;
 	while (des_att>1){
		document.gestion_des.des_defense.value++;
		document.gestion_des.des_attaque.value--;
		des_att--;
	}								
}

/**
* Modifie la valeur des dés
*/
function des_modifier(perso_id, carac, des_attaque){
				var xhr = getXhr()
				
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr.readyState == 4 && xhr.status == 200){			
						if(xhr.responseText != 'null'){
							document.getElementById("fonction_des").style.display = "";
							document.getElementById("modifier_des").disabled=true;
							}
						}
					}
				
		
				// Envoi en GET
				xhr.open("GET","../jeu/action_ajax.php?perso_id="+perso_id+"&action=maj_des"+"&caracs_max="+carac+"&des_attaque="+des_attaque,true);
				xhr.send(null);		
}

/**
* Modifie le marqueur d'esquive magique
*/
function esq_mag_modifier(perso_id){
				var xhr = getXhr()
				
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr.readyState == 4 && xhr.status == 200){			
						if(xhr.responseText != 'null'){
							document.getElementById("fonction_esq_mag").style.display = "";
							document.getElementById("modifier_esq_mag").disabled=true;
							}
						}
					}
				
		
				// Envoi en GET
				xhr.open("GET","../jeu/action_ajax.php?perso_id="+perso_id+"&action=maj_esq_mag",true);
				xhr.send(null);		
}

function set_info(type, perso_id){
				var xhr1 = getXhr()
				var txt;
				var reload=false;
				// On défini ce qu'on va faire quand on aura la réponse
				xhr1.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr1.readyState == 4 && xhr1.status == 200){			
						if(xhr1.responseText != ""){
							txt = xhr1.responseText; 
							if(txt.match(":erreur")){
								txt = txt.replace(":erreur","");
								
								reload=true;
								}
							document.getElementById("infos_action").innerHTML= txt;
							document.getElementById('infos_action').style.display = '';
							if(window.getComputedStyle){
								var style = window.getComputedStyle(document.getElementById('infos_action'),null).backgroundColor;
								}
								else{
									var style = document.body.currentStyle.backgroundColor;
									}
							switch(type){
								case 'action' :
									if(xhr1.responseText=="Vous n'&ecirc;tes pas suffisament endurant pour faire autant d'actions" || reload ){
										document.getElementById('infos_action').style.backgroundColor = "#ff8d8d";
										}else {
											if(style != "rgb(160, 255, 204)"){
												document.getElementById('infos_action').style.backgroundColor = "#A0FFCC";
												}else document.getElementById('infos_action').style.backgroundColor= "#A0CCFF";
											}
									document.getElementById("sbt1").disabled = false;
									break;
								
								//Pour les futurs déplacements en arrière plan
								case 'deplacement':
									break;
								default :
								}
							if (reload){
								var str = 'document.location="./index.php?perso_id='+perso_id+'"';
								setTimeout(str,3000);
								}
							}
						}
					}
				// Envoi en GET
				xhr1.open("GET","./infos_action.php?type="+type,true);
				xhr1.send(null);		
}

	
/**
* Effectue une action
*/
function action(perso_id, ActionID, Cible1ID, Cible1Type, Cible1Nom, Cible1_X, Cible1_Y, Cible2ID, Cible2Type, Cible1allie, Cible2allie, Cible2nom, choix, X, Y){
				xhr=getXhr();
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr.readyState == 4 && xhr.status == 200) {		
						set_info('action', perso_id);
						if(xhr.responseText == "erreur"){
							document.location='./index.php?perso_id='+perso_id;
							}
						//document.getElementById("infos_action").innerHTML='./actions_test.php?ActionID='+ActionID+'&Cible1ID='+Cible1ID+'&Cible1Type='+Cible1Type+'&Cible1Nom='+Cible1Nom+'&Cible1_X='+Cible1_X+'&Cible1_Y='+Cible1_Y+'&Cible2ID='+Cible2ID+'&Cible2Type='+Cible2Type+'&Cible1allie='+Cible1allie+'&Cible2allie='+Cible2allie+'&Cible2Nom='+Cible2nom+'&choix='+choix+'&X='+X+'&Y='+Y;
						//document.getElementById("infos_action").innerHTML= xhr.responseText;
						//document.getElementById('infos_action').style.display = '';
						}
					}
				// Envoi en GET
				xhr.open("GET",'./actions.php?ActionID='+ActionID+'&Cible1ID='+Cible1ID+'&Cible1Type='+Cible1Type+'&Cible1Nom='+Cible1Nom+'&Cible1_X='+Cible1_X+'&Cible1_Y='+Cible1_Y+'&Cible2ID='+Cible2ID+'&Cible2Type='+Cible2Type+'&Cible1allie='+Cible1allie+'&Cible2allie='+Cible2allie+'&Cible2Nom='+Cible2nom+'&choix='+choix+'&X='+X+'&Y='+Y,true);
				xhr.send(null);		
				return xhr;
}

function renew_forum_link(perso_nom){
				var xhr = getXhr()
				
				xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status == 200){			
						if(xhr.responseText != 'null'){
							document.getElementById("fofo_cookie").href=xhr.responseText;
							return false;
							}
						}
					}

				xhr.open("GET","../connexion/action_ajax.php?perso_nom="+perso_nom+"&action=renew_link",true);
				xhr.send(null);	
}


/**
* Modifie la session de personnage chargé sur le forum
*/
function forum_switch(perso_nom){
				var xhr = getXhr()
				
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr.readyState == 4 && xhr.status == 200){			
						if(xhr.responseText != 'null'){
							renew_forum_link(perso_nom);
							new Effect.Highlight('forum_con', { startcolor: '#fffff', endcolor: '#00000' }); 
							return false;
							}
						}
					}

				// Envoi en GET
				xhr.open("GET","../connexion/action_ajax.php?perso_nom="+perso_nom+"&action=switch",true);
				xhr.send(null);		
}

/**
* Reinit le drop
*/
function reinitdrop(time){
		setTimeout("initdrop()",time);
}

/**
* Initialise le drop
*/
function initdrop(){
	drop_flot = 1;
}

/**
* Dépose un artefact sur le damier
*/
function drop_artefact(id_inventaire){
if(drop_flot == 1){
	drop_flot = 0;
		var xhr = getXhr()
		xhr.onreadystatechange = function(){
		//- Ajax : X Y carte_ID artefact_ID Nom
		if(xhr.readyState == 4 && xhr.status == 200){
					if(xhr.responseText != 'null'){
							//-- Transformation json objet
							var jsonobjet =	xhr.responseText;
							var donnees = JSON.parse(jsonobjet);
							//- Extraction du tableau de données
							var coordX = donnees['position']['pos_x'];
							var coordY = donnees['position']['pos_y'];
							var Image = donnees['image'];
							var Nom = donnees['nom'];
							var Desc = donnees['description'];
							var Cout = donnees['cout'];
							var Poid = donnees['poid'];
						
								//-- Supression des elements dans l'inventaire
									//-- Icone de l'artefact
									var noeud = document.getElementById('inventaire');
									var elt_inclus=document.getElementById(id_inventaire+'-artefact');
									noeud.removeChild(elt_inclus);

							//-- Ajout de l'image sur le damier
							var divParent = document.getElementById(coordX+':'+coordY+"-artefact");
								//-Element
								var nouveauDiv = document.createElement('img');
								divParent.appendChild(nouveauDiv);
								nouveauDiv.src = "./../images/"+Image;
								nouveauDiv.className = 'damier_artefact';
								nouveauDiv.id = id_inventaire+'-artefact-damier';
								new Effect.Pulsate(id_inventaire+'-artefact-damier');
													
							//-- Ajout de la bulle de l'artefact sur le damier
								var divParent = document.getElementById(coordX+':'+coordY+"-case");
								//-Element
								var nouveauDiv = document.createElement('div');
								nouveauDiv.innerHTML = "<div class='bubulle'><img src='../images/damier_vide.png' /><div class='infobulle bulledamier'><table border='0px' CELLPADDING='0' CELLSPACING='0'><tr><td colspan='3' class='haut_bulle'></td></tr><tr><td class='gauche_bulle'><b>Nom : </b>"+Nom+"</td><td class='middle_bulle'><img class='img_bulle' src='../images/damier_vide.png' /></td><td class='droit_bulle'></td></tr><tr><td colspan='3' class='centre_bulle'><b>Description : </b>"+Desc+"</td></tr><tr><td colspan='3' class='bas_bulle'></td></tr></table></div></div>";
								divParent.appendChild(nouveauDiv);	
								nouveauDiv.className = 'damier_bulle formulaire';
								nouveauDiv.style.zIndex = '99999';		
								
								//-- Modification du poid et du cout total
								var cout = document.getElementById("val_total").innerHTML;
								var poid = document.getElementById("poid_total").innerHTML;
								
								document.getElementById("val_total").innerHTML = Math.round((cout - Cout)*1000)/1000;
								document.getElementById("poid_total").innerHTML = Math.round((poid - Poid)*1000)/1000;
							
								//-- Réinitialisation de la valeur pour autoriser un nouveau depot dans 1sec
								reinitdrop(500);
					}else{
						reinitdrop(500);
						alert('Aucun espace libre pour deposer votre objet');
					}
				}
			}
	// Envoi en GET -- 
	//xhr.open("GET","../jeu/inventaire/drop.php?pos_x="+X+"&pos_y="+Y+"&carte_id="+carte_id+"&id_objet="+id+"&pv="+pv+"&id_inventaire="+id_inventaire,true);
	xhr.open("GET","../jeu/inventaire/drop.php?id_inventaire="+id_inventaire,true);
	xhr.send(null);

	}else{
		reinitdrop(500);
		alert('Double clic sur le lacher /!\\ je te vois jeune garnement !');					
	}
}

function cacherlayer(id){
	document.getElementById(id).style.display = 'none';
}

function afficherlayer(id){
	document.getElementById(id).style.display = 'block';
	new Effect.Highlight('menu_liste', { startcolor: '#fffff', endcolor: '#00000' }); return false;
}

/**
* Affiche/masque les block dans la page de jeu
*/
function cacherblock(id,idbutton){
	if(visibleblocks[id] == 1){
		var xhr = getXhr()
		xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			if(xhr.responseText != 'null'){	
				document.getElementById('layer-'+id).style.display = 'none';
				document.getElementById(idbutton).innerHTML = '[+]';
				visibleblocks[id] = 0;
			}
		}
		}
		// Envoi en GET -- 
		xhr.open("GET","../jeu/save_visible.php?block_id="+id+"&visible=0",true);
		xhr.send(null);	
	}else{
		var xhr = getXhr()
		xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			if(xhr.responseText != 'null'){		
				document.getElementById('layer-'+id).style.display = 'block';
				document.getElementById(idbutton).innerHTML = '[-]';
				visibleblocks[id] = 1;
			}
		}
		}
		// Envoi en GET -- 
		xhr.open("GET","../jeu/save_visible.php?block_id="+id+"&visible=1",true);
		xhr.send(null);		
	}
}

/**
* Affiche/masque le header du jeu
*/
function header(root_url){
	$('#header').slideToggle('slow');
	//Effect.toggle('header','slide',{ duration: 0.5 });
		var xhr = getXhr()
		xhr.onreadystatechange = function(){
		//- Ajax : X Y carte_ID artefact_ID Nom
		if(xhr.readyState == 4 && xhr.status == 200){
				if(xhr.responseText == 'on'){
					document.getElementById("img_head").src = root_url+"/images/site/header_off.png";
				}else{
					document.getElementById("img_head").src = root_url+"/images/site/header_on.png";
				}
			}
		}
	// Envoi en GET -- 
	xhr.open("GET","../template.php",true);
	xhr.send(null);
}

function top_bar(){
	//new Effect.Opacity('topbar', { from: 0, to: 1 });
}

/**
* Scrool dynamique dans la page pour cibler une ID
*/
function retour_top(id){
	//Effect.ScrollTo(id);
	$.scrollTo( $('#'+id), 800 );
	//return false;
}

/**
* Vérouille l'infobulle
*/
function infobulle_verouille(id, zindex){
	var classe = document.getElementById(id+'_bulle').className;
		if(classe=='infobulle' && info_bulle == 0){
			document.getElementById(id+'_bulle').className = 'infobulle_verouille';
			document.getElementById(id+'_damierbulle').style.zIndex = '99999';
			info_bulle = 1;
		}else if(classe=='infobulle_verouille' && info_bulle == 1){
			document.getElementById(id+'_bulle').className = 'infobulle';
			document.getElementById(id+'_damierbulle').style.zIndex = zindex;
			info_bulle = 0;
		}
	//alert(id);
}

/**
* Session On déclancheur
* Garde la session du jeu ouverte 2h30 : 9000000
*/
function session_on(){
	setTimeout("session_on_query()",600000);
}

/**
* Session On fonction ajax
* Garde la session du jeu ouverte
*/
function session_on_query(){
		var xhr = getXhr()
		xhr.onreadystatechange = function(){
		//- Ajax : X Y carte_ID artefact_ID Nom
		if(xhr.readyState == 4 && xhr.status == 200){
				if(xhr.responseText == 'on'){
					session_on_var = 1;
					session_on();
				}
			}
		}
	// Envoi en GET -- 
	xhr.open("GET","../../../notifications/session_on.php",true);
	xhr.send(null);
}


/*
* Notification
* fonction récurente qui appel la fonction de check des bals tous les X times.
*/
function appel_bal(){
	//-- 5 minutes avant de stoper.
	if (temps_decoule < 300000){
		setTimeout("check_bal()",temps_decoule);
	}
}

/*
* Notification
* Si de nouvelle notifs existe on les donne a creation div
*/
function check_bal(){
				var xhr = getXhr()
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr.readyState == 4 && xhr.status == 200){
						if(xhr.responseText != 'null'){
							if((xhr.responseText != 'aucun')){
							var jsonobjet =	xhr.responseText;
							var donnees = JSON.parse(jsonobjet);
							var tailledonnes = donnees.length;
								var ClassEwo = '_new';				
									for (var i=0;i<(tailledonnes)-1;i++){
										creation_div (donnees, i, ClassEwo);
									}								
								
								temps_decoule = temps_decoule + 2000;
								
								liste_id = donnees[tailledonnes-1]['liste_id'];
								
								nbnotif((tailledonnes)-1,'increm');
								document.getElementById('img_notif_bal').src = "../../images/site/mail_add.png";
								appel_bal();
							}else{
								document.getElementById('img_notif_bal').src = "../../images/site/mail_download.png";
								temps_decoule = temps_decoule + 2000;
								appel_bal();
							}
						//document.getElementById('ajax_notif').style.display = 'none';
						}
					}else if(xhr.readyState == 3){
						//document.getElementById('ajax_notif').style.display = 'block';
						document.getElementById('img_notif_bal').src = '../../images/site/ajax-loader.gif';
					}
				}
				// Envoi en GET
				xhr.open("GET","../../../notifications/check_bal.php",true);
				xhr.send(null);
}

/*
* Notification
* Création  du html des notifs
*/
function creation_div (donnees, a,ClassEwo){
	// recherche du noeud parent
		var divParent = document.getElementById('ecran_notif');
	//Cadre notification
		var nouveauDiv = document.createElement('div');
		nouveauDiv.appendChild(document.createTextNode(''));
		nouveauDiv.className = 'notif_lien'+ClassEwo;
		nouveauDiv.id = 'notif_lien'+ClassEwo+'_'+donnees[a]['id'];
		// raccord des noeuds
		divParent.appendChild(nouveauDiv);
		// création de l'id parent conteneur
		var divParents = document.getElementById('notif_lien'+ClassEwo+'_'+donnees[a]['id']);
	//Premier element
		var nouveauDiv1 = document.createElement('div');
		nouveauDiv1.appendChild(document.createTextNode(""));
		nouveauDiv1.innerHTML = "Bal de <a href='../messagerie/index.php?id="+donnees[a]['pseudo_mat']+"'>"+donnees[a]['pseudo']+"</a> ("+donnees[a]['pseudo_mat']+")";
		nouveauDiv1.className = 'notif_lien_text';
		//nouveauDiv1.onclick=function(){maj_check(id)};
	//Second element
		var nouveauDiv2 = document.createElement('div');
		nouveauDiv2.appendChild(document.createTextNode('test'));
		nouveauDiv2.className = 'notif_lien_date';
		// raccord des noeuds
		divParents.appendChild(nouveauDiv1);
		divParents.appendChild(nouveauDiv2);
}

/*
* Notification
* Nombre de notification
*/
function nbnotif(nombre,statut){
	if(statut == "increm"){
		nombre_init = document.getElementById('notif_nb').innerHTML;
		document.getElementById('notif_nb').innerHTML = parseInt(nombre) + parseInt(nombre_init);
	}else{
		document.getElementById('notif_nb').innerHTML = "0";
	}
}

/*
* Notification
* Changement de l'image en fonction de si notif présente ou non
*/
function img_notif_bal(statut){
	if(statut == 1){
		document.getElementById('img_notif_bal').src = "../images/site/mail_add.png";
	}else{
		document.getElementById('img_notif_bal').src = "../images/site/mail_download.png";
	}
}

/**
* Notification
* Affiche/cache les notification
*/
function afficher_notif(){
	if(ecran_notif == 0){
		document.getElementById('ecran_notif').style.display = 'block';
		ecran_notif = 1;
		img_notif_bal(0);
		nbnotif(0,'decrem');
		//- Met à jour le flag_lu dans la bdd.
		maj_check();
	}else{
		document.getElementById('ecran_notif').style.display = 'none';
		ecran_notif = 0;
		img_notif_bal(0);
	}
}

/**
* Notification
* Recherche des new notification
*/
function maj_check (){
				var xhr = getXhr()				
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr.readyState == 4 && xhr.status == 200){
						if(xhr.responseText == 'ok'){		
							//alert(nbnoti+","+notiftotal);
							setTimeout("effetnotif()",500);
						}
					}
				}		
				// Envoi en GET
				xhr.open("GET","../notifications/maj_check.php",true);
				xhr.send(null);
}

/**
* Notification
* Effet pulsate
*/
function effetnotif(){
	if(liste_id != 0){
	//var listing = JSON.parse(liste_id);
	//alert(listing);
	//var liste_id = ["57","56"];
		for(x=0;x<liste_id.length;x++){
			Effect.Pulsate('notif_lien_new_'+liste_id[x], { pulses: 4, duration: 1.3 });
			setTimeout("switchClass("+liste_id[x]+")",1400);
		}
		//alert(liste_id[x]);
		//setTimeout("switchClass("+i+")",1400);
		liste_id = 0;
	}
}

/**
* Notification
* Changement de la classe et de l'id pour la notification
*/
function switchClass (i){
	document.getElementById('notif_lien_new_'+i).className = 'notif_lien';
	document.getElementById('notif_lien_new_'+i).id = 'notif_lien_'+i;
}

/**
* Bloc image
* Gestion du bloc d'image
*/
function bloc_image(id){
	//alert(bloc_img['BlocImg'][id]['titre']);
	//-- Background
	document.getElementById('bloc_img').style.backgroundImage = 'url('+objetBloc['BlocImg'][id]['img']+')';
	//-- Phrase
	document.getElementById('bloc_phrase').innerHTML = objetBloc['BlocImg'][id]['phrase'];
	//-- Image
	document.getElementById('img_'+id).className = 'bloc_menu_right_img2';// = '2px solid #6F603F';
	if(lastid != -1){
		document.getElementById('img_'+lastid).className = 'bloc_menu_right_img';
	}
	lastid = id;
	new Effect.Opacity('bloc_img', { from: 0, to: 1 }); return false;	
}

/**
* Bloc image
* Rémanance du changement d'image tous les x times
*/
function timer_bloc_image(){
	setTimeout("changement_bloc_image("+lastid+")",8000);
}

/**
* Bloc image
* Changement de l'image
*/
function changement_bloc_image(){
	var tailledonnes = objetBloc['BlocImg'].length;
	randid = Math.floor(Math.random()*(tailledonnes));
	bloc_image(randid);
	timer_bloc_image();
}

function menu_reload(root){
				var xhr = getXhr()				
				var tab;
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr.readyState == 4 && xhr.status == 200){
						if(xhr.responseText != ''){		
						tab = xhr.responseText.split('|');
						for(i=0;i<tab.length;i+=2){
							document.getElementById("color_perso_"+tab[i]).style.color=tab[i+1];
							}
						}
					}
				}		
				// Envoi en GET
				xhr.open("GET",root+"/jeu/action_ajax.php?menu=1",true);
				xhr.send(null);
}

function offset_width(root){
				var xhr = getXhr()				
				var val;
				val = document.body.offsetWidth;
				// On défini ce qu'on va faire quand on aura la réponse
				xhr.onreadystatechange = function(){
				// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
				if(xhr.readyState == 4 && xhr.status == 200){
						if(xhr.responseText != ''){
						
						}
					}
				}		
				// Envoi en GET
				xhr.open("GET",root+"/jeu/action_ajax.php?offset_width="+val,true);
				xhr.send(null);
}
