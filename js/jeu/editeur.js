/*--------------------------------------------------*/
/*- Fonctions de gestion de l'éditeur de damier    -*/
/*- Simonet Fabrice(c) Build-the-web.fr 22/12/2009 -*/
/*--------------------------------------------------*/

//-- Déclaration variable global.
var idimage_deco = "img_souris";
var idimage_objet = "img_souris";
var idimage_artefact = "img_souris";
var idimage_gomme = "img_souris";
var edit = 0;

//-- Selection des decors
function decors (id,Couleur,Mouv,id_decor){
	var Name = document.getElementById('name_'+id).innerHTML;
	var Lien = document.getElementById('img_'+id).src;
	
	document.getElementById('id_decor').innerHTML = id_decor;
	document.getElementById('name_deco').innerHTML = Name;
	document.getElementById('img_deco').innerHTML = "<img id='img_deco_src' src='"+Lien+"' />";
		document.getElementById('img_deco_ico').innerHTML = "<img id='img_deco_src_ico' style='position:absolute;display:block;z-index:5;' src='"+Lien+"' />";
	document.getElementById('color_deco').innerHTML = Couleur;
	document.getElementById('mouv_deco').innerHTML = Mouv+" mouv";
	document.getElementById('i_deco').innerHTML = "1";
		idimage_deco = "img_deco_src_ico";
		reset_gomme();
}

//-- Selection des objets
function objets (id,pvmax,id_objet){
	var Name = document.getElementById('objet_name_'+id).innerHTML;
	var Lien = document.getElementById('objet_img_'+id).src;
	
	document.getElementById('id_objet').innerHTML = id_objet;
	document.getElementById('name_objet').innerHTML = Name;
	document.getElementById('img_objet').innerHTML = "<img id='img_objet_src' src='"+Lien+"' />";
		document.getElementById('img_objet_ico').innerHTML = "<img id='img_objet_src_ico' style='position:absolute;display:block;z-index:5;' src='"+Lien+"' />";
	document.getElementById('pvmax_objet').innerHTML = pvmax;
	document.getElementById('i_objet').innerHTML = "1";
		idimage_objet = "img_objet_src_ico";
		reset_gomme();
}

//-- Selection des artefacts
function artefacts (id,pvmax,id_arte){
	var Name = document.getElementById('artefact_name_'+id).innerHTML;
	var Lien = document.getElementById('artefact_img_'+id).src;
	
	document.getElementById('id_artefact').innerHTML = id_arte;
	document.getElementById('name_artefact').innerHTML = Name;
	document.getElementById('img_artefact').innerHTML = "<img id='img_artefact_src' src='"+Lien+"' />";
		document.getElementById('img_artefact_ico').innerHTML = "<img id='img_artefact_src_ico' style='position:absolute;display:block;z-index:5;' src='"+Lien+"' />";
	document.getElementById('pvmax_artefact').innerHTML = pvmax;
	document.getElementById('i_artefact').innerHTML = "1";
		idimage_artefact = "img_artefact_src_ico";
		reset_gomme();
}

//-- Reset des selections
function reset (type){
	document.getElementById('name_'+type).innerHTML = '';
	document.getElementById('i_'+type).innerHTML = "0";
	document.getElementById('img_'+type).innerHTML = "";
		if (type == 'deco'){
			idimage_deco = "img_souris";
		}else if (type == 'objet'){
			idimage_objet = "img_souris";
		}else if (type == 'artefact'){
			idimage_artefact = "img_souris";
		}
		if(document.getElementById("img_"+type+"_src_ico")){
	  	document.getElementById("img_"+type+"_src_ico").style.left = (-50)+'px';
    	document.getElementById("img_"+type+"_src_ico").style.top  = (-50)+'px';	
    }
		reset_gomme();
}

function reset_all(){
	reset ('deco');
	reset ('objet');
	reset ('artefact');
}

function reset_gomme (){
    idimage_gomme = "img_souris";
    if(document.getElementById("img_gomme_src_ico")){
	  	document.getElementById("img_gomme_src_ico").style.left = (-50)+'px';
    	document.getElementById("img_gomme_src_ico").style.top  = (-50)+'px';	
    }
}

function gomme (type){
	reset ("deco");
	reset ("objet");
	reset ("artefact");
	document.getElementById('name_'+type).innerHTML = 'Gomme';
	document.getElementById('img_'+type).innerHTML = "<img id='img_"+type+"_src' src='../images/decors/motifs/vide.gif' />";
		document.getElementById('img_gomme_ico').innerHTML = "<img id='img_gomme_src_ico' style='position:absolute;display:block;z-index:5;' src='../images/decors/gomme.png' />";
	document.getElementById('i_'+type).innerHTML = "2";
	idimage_gomme = "img_gomme_src_ico";
}

function forme (){
	var Forme = document.tab_forme.forme.value;
	return Forme;
}

function taille (){
	var Taille = parseInt(document.tab_taille.taille.value);
	return Taille;
}

function plan (){
	var Plan = document.tab_plan.plan.value;
	return Plan;
}

//-- Récupération des coordonnées et du type d'information du damier
function damier(id){
	
	var CooXY = id.split(':');
	var X = parseInt(CooXY[0]);
	var Y = parseInt(CooXY[1]);
		set_decors(X,Y);
		set_objets (X,Y);
		set_artefacts(X,Y);
}

//-- Insertion des decors dans la bases de donnée
function set_decors (X,Y){
	if (document.getElementById('i_deco').innerHTML == 1){
		var Lien = document.getElementById('img_deco_src').src;
			
			var Taille = taille();
			var Forme = forme();
			
				var Carte_id = plan();
				var Type_id = document.getElementById('id_decor').innerHTML;
				var Type = 'decor';
				var Action= 'norm';
				var Divers = '';
			
			if (Forme == "normal"){
				ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien);
			}else if(Forme == "ligneh"){
				var Fin = X+Taille;
				while(X < Fin){
					ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien);
					X++;
				}
			}else if(Forme == "lignev"){
				var Fin = Y+Taille;
				while(Y < Fin){
					ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien);
					Y++;
				}
			}else if(Forme == "carre"){
				for(H=Taille; H>0; H--){
					for(L=0; L<Taille; L++){
						var coord_X = X+(L);
						var coord_Y = Y+(H-1);
						ajax (Type,coord_X,coord_Y,Carte_id,Type_id,Divers,Action,Lien);
					}
				}	
			}
	}else if (document.getElementById('i_deco').innerHTML == 2){
		var Lien = document.getElementById('img_deco_src').src;
			var Carte_id = plan();
			var Type_id = document.getElementById('id_decor').innerHTML;
			var Type = 'decor';
			var Action= 'sup';
			var Divers = '';
		ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien);
		//-- fonction ajax d'ajout dans la BDD - Suppression
	}
	
}

//-- Insertion des objets simples dans la base de donnée
function set_objets (X,Y){
	if (document.getElementById('i_objet').innerHTML == 1){
		var Lien = document.getElementById('img_objet_src').src;
			var Carte_id = plan();
			var Type_id = document.getElementById('id_objet').innerHTML;
			var pv_max = document.getElementById('pvmax_objet').innerHTML;
			var Type = 'objet';
			var Action= 'norm';
			var Divers = pv_max;
			//alert(Lien);
		ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien);
	//-- fonction ajax d'ajout dans la BDD - Modififaction ou création	
	}else if (document.getElementById('i_objet').innerHTML == 2){
		var Lien = document.getElementById('img_objet_src').src;
			var Carte_id = plan();
			var Type_id = document.getElementById('id_objet').innerHTML;
			var Type = 'objet';
			var Action= 'sup';
			var Divers = '';
		ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien);
		//-- fonction ajax d'ajout dans la BDD - Suppression
	}
}

//-- Insertion des artefacts dans la base de donnée
function set_artefacts (X,Y){	
	if (document.getElementById('i_artefact').innerHTML == 1){
		var Lien = document.getElementById('img_artefact_src').src;
			var Carte_id = plan();
			var Type_id = document.getElementById('id_artefact').innerHTML;
			var pv_max = document.getElementById('pvmax_artefact').innerHTML;
			var Divers = pv_max;
			var Type = 'artefact';
			var Action= 'norm';
		ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien);
	}else if (document.getElementById('i_artefact').innerHTML == 2){
		var Lien = document.getElementById('img_artefact_src').src;
			var Carte_id = plan();
			var Type_id = document.getElementById('id_artefact').innerHTML;
			var pv_max = document.getElementById('pvmax_artefact').innerHTML;
			var Divers = pv_max;
			var Type = 'artefact';
			var Action= 'sup';
		ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien);			
		//alert('suppression');
	}
}

function ajax (Type,X,Y,Carte_id,Type_id,Divers,Action,Lien){
	var xhr = getXhr()
	xhr.onreadystatechange = function(){
		//- Ajax : X Y carte_ID artefact_ID Nom
		if(xhr.readyState == 4 && xhr.status == 200){			
					if(xhr.responseText != 'null'){
							//-- Supprime Icone en place
							var noeud = document.getElementById(X+':'+Y+'-'+Type);
							var image = noeud.getElementsByTagName('img');
								if(image[0]){
									image[0].src = Lien;
									//alert('image ok');
								}else{
									var nouveauDiv = document.createElement('img');
									noeud.appendChild(nouveauDiv);
									nouveauDiv.src = Lien;
									//alert('image nok');
								}
					}
				}
			}
	// Envoi en GET -- Divers : implode avec -
	xhr.open("GET","../editeur/bdd_"+Type+".php?x="+X+"&y="+Y+"&carte_id="+Carte_id+"&id="+Type_id+"&divers="+Divers+"&action="+Action,true);
	xhr.send(null);
}

//--
document.onmousemove = suitsouris;
function suitsouris(evenement){
  if(navigator.appName=="Microsoft Internet Explorer"){
          var x = event.x+document.body.scrollLeft;
          var y = event.y+document.body.scrollTop;
  }else{
          var x =  evenement.pageX;
          var y =  evenement.pageY;
  }
  if(document.getElementById(idimage_deco)){
  document.getElementById(idimage_deco).style.left = (x+15)+'px';
  document.getElementById(idimage_deco).style.top  = (y+15)+'px';
  //document.getElementById(idimage_deco).style.zIndex = '999999';
  	document.getElementById(idimage_objet).style.left = (x+15)+'px';
  	document.getElementById(idimage_objet).style.top  = (y+15)+'px';
  	document.getElementById(idimage_objet).style.zIndex = '41';
				document.getElementById(idimage_artefact).style.left = (x+15)+'px';
				document.getElementById(idimage_artefact).style.top  = (y+15)+'px';
				document.getElementById(idimage_artefact).style.zIndex = '51';
		  		document.getElementById(idimage_gomme).style.left = (x+15)+'px';
		  		document.getElementById(idimage_gomme).style.top  = (y+15)+'px';
		  		document.getElementById(idimage_gomme).style.zIndex = '999';   		     
	}
}

function maj_objet(id,action,type){
if (action == 'lock'){
	var Class =	document.getElementById(id+'-lock').className;
		if(Class == 'lock open'){
			var xhr = getXhr()
				xhr.onreadystatechange = function(){
				//- Ajax : X Y carte_ID artefact_ID Nom
				if(xhr.readyState == 4 && xhr.status == 200){			
							if(xhr.responseText != 'null'){
								document.getElementById(id+'-lock').className = "lock close";
							}
						}
					}
			// Envoi en GET -- Divers : implode avec -
			xhr.open("GET","../editeur/bdd_lock.php?id="+id+"&action=close&type="+type,true);
			xhr.send(null);
		}else{
			var xhr = getXhr()
				xhr.onreadystatechange = function(){
				//- Ajax : X Y carte_ID artefact_ID Nom
				if(xhr.readyState == 4 && xhr.status == 200){			
							if(xhr.responseText != 'null'){
								document.getElementById(id+'-lock').className = "lock open";
							}
						}
					}
			// Envoi en GET -- Divers : implode avec -
			xhr.open("GET","../editeur/bdd_lock.php?id="+id+"&action=open&type="+type,true);
			xhr.send(null);	
		}
	}else if(action == 'supprimer'){
		var xhr = getXhr()
			xhr.onreadystatechange = function(){
			//- Ajax : X Y carte_ID artefact_ID Nom
			if(xhr.readyState == 4 && xhr.status == 200){			
						if(xhr.responseText != 'null'){
							var noeud = document.getElementById(id+'-porte');
							while (noeud.firstChild) {
								noeud.removeChild(noeud.firstChild);
							}
						}
					}
				}
		// Envoi en GET -- Divers : implode avec -
		xhr.open("GET","../editeur/bdd_lock.php?id="+id+"&action=supprimer&type="+type,true);
		xhr.send(null);
	}
}

//-- Edition des entrées dans la Bdd en fonction de : 
//@ nom : nom du champ
//@table : nom de la table
function edition_click(id,champ,table,where){
	if(edit == 0){
		var text = document.getElementById(id).innerHTML;
		//alert(text);
		document.getElementById(id+"_p").innerHTML = "<input type='text' id='"+id+"' name='valeur' value='' /><input type='button' value='Ok' onclick=\"edition_get('"+id+"','"+champ+"','"+table+"','"+where+"');\"/>";
		document.getElementById(id).value = text;
		edit = 1;
	}
}

function edition_get(id,champ,table,where){
	text = document.getElementById(id).value;
		var xhr = getXhr()
		xhr.onreadystatechange = function(){
		//- Ajax : X Y carte_ID artefact_ID Nom
		if(xhr.readyState == 4 && xhr.status == 200){			
					if(xhr.responseText != 'null'){
						document.getElementById(id+"_p").innerHTML = "<span id='"+id+"' onclick=\"edition_click(this.id,'"+champ+"','"+table+"','"+where+");\">"+text+"</span>";
						edit = 0;
					}
				}
			}
	// Envoi en GET -- 
	xhr.open("GET","../editeur/bdd_update.php?where="+where+"&text="+text+"&champ="+champ+"&table="+table,true);
	xhr.send(null);
}
//-------------------------------------


