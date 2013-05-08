<?php
/**
 * Index des cartes
 *
 * Affiche toutes les cartes en fonctions de la race et des autorisations
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package carte
 */
//-- Header --

$css_files = 'carte';

$header['title'] = "Carte du monde";
$header['desc'] = "Terrain de jeux violents entre les Démons, les Anges et les Humains d'EWO. Peut aussi servir de circuit de vitesse pour des escargots bourrés.";

require_once __DIR__ . '/../../conf/master.php';

include_once SERVER_ROOT . '/template/header_new.php';
include_once SERVER_ROOT . '/persos/fonctions.php';

//------------

if (ControleAcces('utilisateur',0) == TRUE){

$nb_perso =  $_SESSION['persos']['inc'];

	$carte_enf_ok = 0;
	$carte_par_ok = 0;
	$carte_fac_par = 0 ;
	$carte_fac_enf = 0 ;
	$carte_en = 0;
	$trich	  = 0;
	$vision = '';
	$n_fac = 0;

	for($inc=1 ; $inc<=$nb_perso ; $inc++){
		$camp = recup_camp($_SESSION['persos']['race'][$inc]);
		if($camp==4){
			$carte_enf_ok = 1;
			}
		if($camp==3){
			$carte_par_ok = 1;
			}
		if($camp==-1){
			$trich = 1;
			}
		if(isset($_SESSION['persos']['carte'][$inc]) && $_SESSION['persos']['carte'][$inc]==2  && $camp!=4){
			$carte_en = 1;
			}
		
		if(isset($_SESSION['persos']['carte'][$inc]) && $_SESSION['persos']['carte'][$inc]==3  && $camp!=3){
			$carte_en = 1;
			}
			
		if($_SESSION['persos']['faction']['id'][$inc] && $camp!=3){
			$sql = "SELECT persos.id AS perso_id, persos.grade_id AS grade_id, persos.galon_id AS galon_id, damier_persos.pos_x, damier_persos.pos_y
						FROM damier_persos
							INNER JOIN persos ON persos.id = damier_persos.perso_id
							WHERE persos.faction_id=".$_SESSION['persos']['faction']['id'][$inc]." AND carte_id=3";
			$res_fac = mysql_query ($sql) or die (mysql_error());
			while($res_fac_id=mysql_fetch_array($res_fac)){
				$carte_fac_par = 1;
				}
			}
		
		if($_SESSION['persos']['faction']['id'][$inc] && $camp!=4){
			$sql = "SELECT persos.id AS perso_id, persos.grade_id AS grade_id, persos.galon_id AS galon_id, damier_persos.pos_x, damier_persos.pos_y
						FROM damier_persos
							INNER JOIN persos ON persos.id = damier_persos.perso_id
							WHERE persos.faction_id=".$_SESSION['persos']['faction']['id'][$inc]." AND carte_id=2";
			$res_fac = mysql_query ($sql) or die (mysql_error());
			while($res_fac_id=mysql_fetch_array($res_fac)){
				$carte_fac_enf = 1;
				}
			}
		}
	}
        
$js->addScript('carte');       
?>



<h2>Althian</h2>

<div align='center'>
			<div class='centrage'>
				<div id="boutons"></div>
				<div id="map">
					<embed id="emb" style="left:0;top:0;" type="image/svg+xml" src="svg_althian.php?hori=5&vert=3"></embed>
				</div>
			</div>
			
			
			<script>
				getElementsByClassName = function(cl) {
					var retnode = [];
					var myclass = new RegExp('\\b'+cl+'\\b');
					var elem = this.getElementsByTagName('*');
					for (var i = 0; i < elem.length; i++) {
						var classes = elem[i].className;
						if (myclass.test(classes)) retnode.push(elem[i]);
					}
					return retnode;
				}; 
				
				var tabZooms = [0.5, 2];
				var tabLimitZooms = [-2, 2];
				var tabBoucliers = [];
				var tabRaces = ['humain', 'paria', 'ange', 'demon'];
				var svg;
				var mouvement = 0;
				function init(){
					// On initialise notre svg
					svg = getSvg();
				
					// DÃ©but RÃ©cupÃ©ration de tous les boucliers ************************//
						var titreBoucliers = getAllBouclier();
				
						var elems = svg.getElementsByClassName('bouclier');
						for(var i=0, y=elems.length;i<y;i++){
							if(elems[i].getAttribute('id')){
								tabBoucliers.push(new Array(titreBoucliers[elems[i].id],elems[i]));
							}
						}	
					// Fin RÃ©cupÃ©ration de tous les boucliers *************************//

					
				}
				
				function afficheCalque(elem){
					if(elem.style.display == "none"){elem.style.display = "block";
					}else{elem.style.display = "none";}
				}

				function zOom(type){
					var op = '+';
					var facZoom = tabZooms[1];
					if(type=='-'){facZoom = tabZooms[0]; op = '-';}
					if(op == '+'){mouvement++;}else{mouvement--;}
					if(mouvement < tabLimitZooms[1] && mouvement > tabLimitZooms[0]){
						var elems = svg.getElementsByTagName('*');
						for(var i=0, y=elems.length; i<y;i++){
							var tabAtr = elems[i].attributes;
							for(var m=0, n=tabAtr.length;m<n;m++){
								if(tabAtr[m].localName && tabAtr[m].nodeValue){
									if(!isNaN(tabAtr[m].nodeValue)){
										tabAtr[m].nodeValue = tabAtr[m].nodeValue*facZoom;
									}
								}
							}
							if(elems[i].tagName == 'text'){
								if(elems[i].style.fontSize != ""){
									var elem = elems[i].style.fontSize;
									var px = elem.split('px');
									if(op == '+'){
										elems[i].style.fontSize = parseInt(parseInt(px[0])+7)+'px';
									}else{
										elems[i].style.fontSize = parseInt(parseInt(px[0])-7)+'px';
									}
								}else{
									if(op == '+'){
										elems[i].style.fontSize = '17px';
									}else{
										elems[i].style.fontSize = '3px';
									}
								}
							}
						
						}
					}else{
						if(op == '+'){mouvement--;}else{mouvement++;}
					}
				}
				
				function afficheBoutons(){
					var ret = '';
					for(var i=0, y=tabBoucliers.length;i<y;i++){
						ret += "<input type='button' value='"+tabBoucliers[i][0]+"' onclick='afficheCalque(svg.getElementById(\""+tabBoucliers[i][1].id+"\"))'>";
					}
					for(var i=0,y=tabRaces.length; i<y; i++){
						ret += "<input type='button' value='"+tabRaces[i]+"' onclick='afficheCalque(svg.getElementsByClassName(\""+tabRaces[i]+"\")[0])'>";
					}
					ret += "<input type='button' value='Portes' onclick='afficheCalque(svg.getElementsByClassName(\"porte\")[0])'>";
					ret += "<input type='button' value='Mes Personnages' onclick='afficheCalque(svg.getElementsByClassName(\"viseurs\")[0])'>";
					//ret += "<input type='button' value='+' onclick='zOom(this.value)'>";
					//ret += "<input type='button' value='-' onclick='zOom(this.value)'>";
					document.getElementById('boutons').innerHTML += ret;
				}
				
				
				function Affiche(sens) {
					if(sens > 0){zOom('+');
					}else{zOom('-');}
				}

				function Molette(event){
					var sens = 0;
					if (!event){event = window.event;}
					if (event.wheelDelta) {
						sens =(window.opera)?-event.wheelDelta/120: event.wheelDelta/120; 
					}else {sens=(event.detail)?-event.detail/3:sens;}
					if (sens){
						Affiche(sens);
					}
				}

				window.onload = function(){
					init();
					afficheBoutons();
					//if (svg.addEventListener){svg.addEventListener('DOMMouseScroll', Molette, false);}
					//else{svg.onmousewheel =function(){Molette()};svg.onmousewheel = function(){Molette()};}
				}
				
			</script>			
</div>

<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>