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
			<div id="fond_carte_terre_coord" style="visibility:hidden">Position en X : <span id="fond_carte_terre_coordX"></span> | Position en Y : <span id="fond_carte_terre_coordY"></span></div>
			<!-- Fin affichage des coordonn�es terrestres -->
			<div class='centrage'>
				<div id='fond_carte_terre'>
				<?php if (ControleAcces('utilisateur',0) == TRUE){
						echo "<span class='fond_carte' id='carte_terre_portes' style='display:block;'><img src='".SERVER_URL."/jeu/carte/carte_terre.php?porte=1' alt='terre'></span> ";
						echo "<span class='fond_carte' id='carte_terre_boucliers' style='display:block;'><img src='".SERVER_URL."/jeu/carte/carte_terre.php?bouclier=1' alt='terre'></span>";
						for($inci=1;$inci<=4;$inci++){
								echo "<span class='fond_carte' id='carte_terre_R".$inci."G0' style='display:block;'><img src='".SERVER_URL."/jeu/carte/carte_terre.php?race=".$inci."&grade=0' alt='terre'></span> ";
								echo "<span class='fond_carte' id='carte_terre_R".$inci."G4' style='display:block;'><img src='".SERVER_URL."/jeu/carte/carte_terre.php?race=".$inci."&grade=4' alt='terre'></span> ";
								echo "<span class='fond_carte' id='carte_terre_R".$inci."G5' style='display:block;'><img src='".SERVER_URL."/jeu/carte/carte_terre.php?race=".$inci."&grade=5' alt='terre'></span> ";							
							}
						
						echo "<span class='fond_carte' id='carte_terre_R1G-1' style='display:block;'><img src='".SERVER_URL."/jeu/carte/carte_terre.php?race=1&grade=-1' alt='terre'></span> ";
						echo "<span class='fond_carte' id='carte_terre_grille' style='display:block;'><img src='".SERVER_URL."/jeu/carte/carte_terre.php?grille=1' alt='terre'></span>";
						echo "<span class='fond_carte' id='carte_terre_viseur' style='display:block;'><img src='".SERVER_URL."/jeu/carte/carte_terre.php?viseur=1' alt='terre'></span>";
						?>
						<!-- POUR ERASE -->
						<span class='fond_carte' id='Gcarte_terre_R3G0' style='display:none;'><img src='./carte_terre.php?race=3&grade=0&zoom=1' alt='Gterre'></span>
						<!-- FIN POUR ERASE -->
						<?php
					} else {?>
							<img src='../images/cartes/carte.jpg' alt='Terre'>
					<?php	} ?>
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
/*-- Connexion basic requise --*/
if (ControleAcces('utilisateur',0) == TRUE){

/*-----------------------------*/
$id_utilisateur = $_SESSION['utilisateur']['id'];

// Recup?ration des donn?es
        
for ($inc=1; $inc<=$_SESSION['persos']['inc']; $inc++){

	$perso_id 	= $_SESSION['persos']['id'][$inc];
	$race_grade = recup_race_grade($perso_id);
	if($race_grade['race_id']==1 || $race_grade['race_id']==3 || $race_grade['race_id']==4)
		{
			$race = $race_grade['race_id'];
			break;
		}
	}
	
if(($carte_par_ok || $carte_en || $carte_fac_par) && !$trich){
?>

<h2>Celestia</h2>

<div align='center'>
		<!-- Affichage des coordonn�es paradis -->
		<div id="fond_carte_paradis_coord" style="visibility:hidden">Position en X : <span id="fond_carte_paradis_coordX"></span> | Position en Y : <span id="fond_carte_paradis_coordY"></span></div>
		<!-- Fin Affichage des coordonn�es paradis -->
			<div class='centrage'>
				<div id='fond_carte_paradis'>
				<?php
					echo "<span class='fond_carte'  id='carte_paradis_portes'><img src='".SERVER_URL."/jeu/carte/carte_paradis.php?porte=1' alt='paradis'></span> ";
					echo "<span class='fond_carte'  id='carte_paradis_boucliers'><img src='".SERVER_URL."/jeu/carte/carte_paradis.php?bouclier=1' alt='paradis'></span>";
					for($inci=1;$inci<=4;$inci++){
							echo "<span class='fond_carte' id='carte_paradis_R".$inci."G0'><img src='".SERVER_URL."/jeu/carte/carte_paradis.php?race=".$inci."&grade=0' alt='paradis'></span> ";
							echo "<span class='fond_carte' id='carte_paradis_R".$inci."G4'><img src='".SERVER_URL."/jeu/carte/carte_paradis.php?race=".$inci."&grade=4' alt='paradis'></span> ";
							echo "<span class='fond_carte'  id='carte_paradis_R".$inci."G5'><img src='".SERVER_URL."/jeu/carte/carte_paradis.php?race=".$inci."&grade=5' alt='paradis'></span> ";							
						}
					
					echo "<span class='fond_carte'  id='carte_paradis_R1G-1'><img src='".SERVER_URL."/jeu/carte/carte_paradis.php?race=1&grade=-1' alt='paradis'></span> ";
					echo "<span class='fond_carte' id='carte_paradis_grille'><img src='".SERVER_URL."/jeu/carte/carte_paradis.php?grille=1' alt='paradis'></span>";
					echo "<span class='fond_carte' id='carte_paradis_viseur'><img src='".SERVER_URL."/jeu/carte/carte_paradis.php?viseur=1' alt='paradis'></span>";
				?>
				</div>
			</div>			

			<p>
				[<span class='curspointer' onclick="layer_carte('carte_paradis_viseur');">Mes personnages</span>]
			</p>
			<p>	
				[<span class='curspointer' onclick="layer_carte('carte_paradis_grille');">Grille</span>]
				[<span class='curspointer' onclick="layer_carte('carte_paradis_portes');">Porte</span>]
				[<span class='curspointer' onclick="layer_carte('carte_paradis_boucliers');">Bouclier</span>]
			</p>
			<p>		
				[<span class='curspointer' onclick="layer_carte('carte_paradis_R1G0');layer_carte('carte_paradis_R1G4');layer_carte('carte_paradis_R1G5');">Humain</span>]
				[<span class='curspointer' onclick="layer_carte('carte_paradis_R4G0');layer_carte('carte_paradis_R4G4');layer_carte('carte_paradis_R4G5');">Demon</span>]
				[<span class='curspointer' onclick="layer_carte('carte_paradis_R3G0');layer_carte('carte_paradis_R3G4');layer_carte('carte_paradis_R3G5');">Ange</span>]
				[<span class='curspointer' onclick="layer_carte('carte_paradis_R2G0');layer_carte('carte_paradis_R2G4');layer_carte('carte_paradis_R2G5');">Paria</span>]
			</p>
			<p>		
				[<span class='curspointer' onclick="layer_all('terre')">Afficher tout</span>]
			</p>	
</div>

<?php
	}
if(($carte_enf_ok || $carte_en || $carte_fac_enf) && !$trich){
?>
<h2>Ciferis</h2>

<div align='center'>
		<!-- Affichage des coordonn�es enfer -->
		<div id="fond_carte_enfer_coord" style="visibility:hidden">Position en X : <span id="fond_carte_enfer_coordX"></span> | Position en Y : <span id="fond_carte_enfer_coordY"></span></div>
		<!-- Fin Affichage des coordonn�es enfer -->
			<div class='centrage'>
				<div id='fond_carte_enfer'>
			<?php
					echo "<span class='fond_carte' id='carte_enfer_portes'><img src='".SERVER_URL."/jeu/carte/carte_enfer.php?porte=1' alt='Enfer'></span> ";
					echo "<span class='fond_carte' id='carte_enfer_boucliers'><img src='".SERVER_URL."/jeu/carte/carte_enfer.php?bouclier=1' alt='Enfer'></span>";
					for($inci=1;$inci<=4;$inci++){
							echo "<span class='fond_carte' id='carte_enfer_R".$inci."G0'><img src='".SERVER_URL."/jeu/carte/carte_enfer.php?race=".$inci."&grade=0' alt='Enfer'></span> ";
							echo "<span class='fond_carte' id='carte_enfer_R".$inci."G4'><img src='".SERVER_URL."/jeu/carte/carte_enfer.php?race=".$inci."&grade=4' alt='Enfer'></span> ";
							echo "<span class='fond_carte' id='carte_enfer_R".$inci."G5'><img src='".SERVER_URL."/jeu/carte/carte_enfer.php?race=".$inci."&grade=5' alt='Enfer'></span> ";							
						}
					
					echo "<span class='fond_carte' id='carte_enfer_R1G-1'><img src='".SERVER_URL."/jeu/carte/carte_enfer.php?race=1&grade=-1' alt='Enfer'></span> ";
					echo "<span class='fond_carte' id='carte_enfer_grille'><img src='".SERVER_URL."/jeu/carte/carte_enfer.php?grille=1' alt='Enfer'></span>";
						echo "<span class='fond_carte' id='carte_enfer_viseur'><img src='".SERVER_URL."/jeu/carte/carte_enfer.php?viseur=1' alt='enfer'></span>";
			?>		
					</div>
			</div>	
						
			<p>
				[<span class='curspointer' onclick="layer_carte('carte_enfer_viseur');">Mes personnages</span>]
			</p>
			<p>	
				[<span class='curspointer' onclick="layer_carte('carte_enfer_grille');">Grille</span>]
				[<span class='curspointer' onclick="layer_carte('carte_enfer_portes');">Porte</span>]
				[<span class='curspointer' onclick="layer_carte('carte_enfer_boucliers');">Bouclier</span>]
			</p>
			<p>		
				[<span class='curspointer' onclick="layer_carte('carte_enfer_R1G0');layer_carte('carte_enfer_R1G4');layer_carte('carte_enfer_R1G5');">Humain</span>]
				[<span class='curspointer' onclick="layer_carte('carte_enfer_R4G0');layer_carte('carte_enfer_R4G4');layer_carte('carte_enfer_R4G5');">Demon</span>]
				[<span class='curspointer' onclick="layer_carte('carte_enfer_R3G0');layer_carte('carte_enfer_R3G4');layer_carte('carte_enfer_R3G5');">Ange</span>]
				[<span class='curspointer' onclick="layer_carte('carte_enfer_R2G0');layer_carte('carte_enfer_R2G4');layer_carte('carte_enfer_R2G5');">Paria</span>]
			</p>
			<p>		
				[<span class='curspointer' onclick="layer_all('terre')">Afficher tout</span>]
			</p>	
</div>
	<?php
		}
	}
	?>
	

<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>