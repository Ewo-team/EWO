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
			<!-- Affichage des coordonn�es terrestres-->
			<!-- <div id="fond_carte_terre_coord" style="visibility:hidden">Position en X : <span id="fond_carte_terre_coordX"></span> | Position en Y : <span id="fond_carte_terre_coordY"></span></div>-->
			<!-- Fin affichage des coordonn�es terrestres -->
			<div class='centrage'>
				<div id="map">
					<embed id="emb" style="left:0;top:0;" type="image/svg+xml" src="svg_althian.php"></embed>
				</div>
			</div>

	<p>
		[<span class='curspointer' onclick="layer_carte('carte_terre_viseur');">Mes personnages</span>]
	</p>
	<p>	
		[<span class='curspointer' onclick="layer_carte('carte_terre_grille');">Grille</span>]
		[<span class='curspointer' onclick="layer_carte('carte_terre_portes');">Porte</span>]
		[<span class='curspointer' onclick="layer_carte('carte_terre_boucliers');">Bouclier</span>]
	</p>
	<p>		
		[<span class='curspointer' onclick="layer_carte('carte_terre_R1G0');layer_carte('carte_terre_R1G4');layer_carte('carte_terre_R1G5');">Humain</span>]
		[<span class='curspointer' onclick="layer_carte('carte_terre_R4G0');layer_carte('carte_terre_R4G4');layer_carte('carte_terre_R4G5');">Demon</span>]
		[<span class='curspointer' onclick="layer_carte('carte_terre_R3G0');layer_carte('carte_terre_R3G4');layer_carte('carte_terre_R3G5');">Ange</span>]
		[<span class='curspointer' onclick="layer_carte('carte_terre_R2G0');layer_carte('carte_terre_R2G4');layer_carte('carte_terre_R2G5');">Paria</span>]
	</p>
	<p>		
		[<span class='curspointer' onclick="layer_all('terre')">Afficher tout</span>]
	</p>	
</div>

<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>