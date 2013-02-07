<?php

namespace messagerie;

/**
 * Affiche les bals recus
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */
//-- Header --

require_once __DIR__ . '/../conf/master.php';

$css_files = 'messagerie';


include(SERVER_ROOT . "/template/header_new.php");

/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/
?>

<?php

if(isset($_GET['id'])){
	$id_perso = $_GET['id'];
} else {
	$id_perso = $_SESSION['persos']['id'][1];
}

$_SESSION['perso']['id'] = $id_perso;
$utilisateur_id = $_SESSION['utilisateur']['id'];
$droits = $_SESSION['utilisateur']['droits'];

	// Paramètres de connexion à la base de données
	$conn = messagerieDAO::getInstance();

	if($res = $conn->VerifPersoExisteUtilisateur($id_perso, $utilisateur_id)){
		$id_per = $res['id'];
		$nom_per = nom_perso($res['id']);
		
	$recu = 0;
	$archive = 0;
	$send = 0;
	
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 'recu';
	}
	
	switch($page) {
		case 'archive':
			$res = $conn->SelectBalArchive($id_perso);
			$archive = 1;
			$titre = "Messages archivés de $nom_per ($id_per)";
			break;
		case 'send':
			$res = $conn->SelectBalEnvoye($id_perso);
			$send = 1;
			$titre = "Messages envoyés de $nom_per ($id_per)";			
			break;
		case 'recu':
		default:
			$res = $conn->SelectBalRecu($id_perso);
			$recu = 1;
			$titre = "Messages reçus de $nom_per ($id_per)";
			break;
	}
	
	$nom_listes = array(
		'faction' => 'Faction',
		'plan' => 'Tout les occupants du plan',
                'mass_joueur' => 'Tout les joueurs',
                'mass_camp' => 'Tout les personnages du camp',
		'admin' => 'Administrateurs',
		'anim' => 'Animateurs',
		'at' => 'Anti-triche'
	);
        
$js->addVariables('balid', 'new Array()', true);
?>

<link href="../js/lib/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<p align='center'>[ <a href='index.php?page=recu&id=<?php echo $id_perso; ?>'>Messages Reçus </a> ] [ <a href='index.php?page=archive&id=<?php echo $id_perso; ?>'>Messages Archivés</a> ] [ <a href='index.php?page=send&id=<?php echo $id_perso; ?>'>Messages envoyés</a> ] [ <a href='listes.php?id=<?php echo $id_perso; ?>'>Listes de BALs</a> ]</p>

<h2><?php echo $titre; ?></h2>

<div id='contact' align='center'>
	
<table class='bal_tab' width='900px' id='cases'>
<tr>
	<td width='15px'></td>
	<td width='20px'></td>
	<td width='20px'></td>
	<td width='200px'></td>
	<td width='500px'></td>
	<td width='145px'></td>
</tr>
<?php

$nom_liste = '';
$i = 0;
foreach ($res as $bal) {
	//Couleur lu et non lu
	if ($bal['lu'] == 0){
		$lunonlu = 'bal_titre_nonlu';
	}else{
		$lunonlu = 'bal_titre_lu';
	}
	//Bal favoris or not
	$balfav = $bal['flag_fav'];
	if ($balfav == 0){
		$balfav = "<img id='bal_fav_".$bal['id_bals']."' src='../images/site/fav_none.png' alt='favenone' />";
	}else{
		$balfav = "<img id='bal_fav_".$bal['id_bals']."' src='../images/site/fav_done.png' alt='favedone' />";
	}

	$titre = addslashes($bal['titre']);
	
	$reponse_simple = 'null';
	$reponse_tous = 'null';		
	static $info_liste;
	
	if(!$send) {
		$nom_liste = $bal['expediteur'].' ('.$bal['id_expediteur'].')</span> ';	

		if(isset($bal['liste_bal'])) {
			$bal['liste_mats'] = $bal['id_expediteur'];
                        
			switch($bal['liste_bal']) {
				case 'faction' :
						$liste_id = $bal['liste_bal'];
						$nom = $nom_listes[$liste_id];	
						
						$reponse_simple = 'faction';
						$reponse_tous = 'faction';
					break;
                                case 'plan':    
				case 'mass_joueur':
                                case 'mass_camp':      
						$liste_id = $bal['liste_bal'];
						$nom = $nom_listes[$liste_id];	                                    
                                    
						$reponse_simple = 'null';
						$reponse_tous = 'null';
					break;
				case 'admin' :
				case 'anim' :
				case 'at' :                                 
						$liste_id = $bal['liste_bal'];
						$nom = $nom_listes[$liste_id];	
						
						$reponse_simple = 'null';
						$reponse_tous = $bal['liste_bal'];					
					break;
				default:
					
					$id_liste = $bal['liste_bal'];
					if(!isset($info_liste[$id_liste])) {
						$info_liste[$id_liste] = $conn->SelectInfoListeNumerique($bal['liste_bal']);
					}
					$nom = $info_liste[$id_liste];
					if(isset($nom[0])) {
						$nom = $nom[0]['libelle'];
					} else {
						$nom = 'Liste supprimée';
					}
					// l'expéditeur est dans la liste
					if($conn->IsOnListe($bal['liste_bal'], $bal['id_expediteur'])) {
						$reponse_simple = $bal['liste_bal'];
						$reponse_tous = $bal['liste_bal'];
					} else {
						$reponse_simple = 'null';
						$reponse_tous = $bal['liste_bal'];						
					}
					break;
			}
			
			if($bal['liste_bal'] != 'plan') {
				$nom_liste .= '<span class="bal_listeid curspointer">[Liste]</span><div class="bal_infobulle">'.$nom.'</div>';
			}
		}
	} else {
		if(isset($bal['liste_bal'])) {
			$reponse_simple = $bal['liste_bal'];
			$reponse_tous = $bal['liste_bal'];	
			
			if(is_numeric($bal['liste_bal'])) {
				$id_liste = $bal['liste_bal'];
				if(!isset($info_liste[$id_liste])) {
					$info_liste[$id_liste] = $conn->SelectInfoListeNumerique($bal['liste_bal']);
				}
				$nom = $info_liste[$id_liste];			
				if(isset($nom[0])) {
					$nom = $nom[0]['libelle'];
				} else {
					$nom_liste = 'Liste supprimée';
				}				
			} else {
				$liste_id = $bal['liste_bal'];
                                if(isset($nom_listes[$liste_id])) {
                                        $nom_liste = $nom_listes[$liste_id];
                                } else {
                                        $nom_liste = 'Liste supprimée';
                                }                                
				
			}
		}		
	}

$js->setVariables('balid['.$bal['id_bals'].']', 'new Array(0,0)', true);        
        
?>

<tr class='<?php echo $lunonlu; ?>' id='titre_<?php echo $bal['id_bals']; ?>'>
	<td><img src='../images/site/<?php echo $bal['flag_exp']; ?>.png' alt='exp' /></td>
	<td><input type="checkbox" name="bal_id[]" value="<?php echo $bal['id_bals']; ?>"></td>
	<?php if(!$send) { ?>
		<td><span class='curspointer' onclick="bal_fav('<?php echo $bal['id_bals']; ?>','<?php echo $id_perso; ?>');" title='Mettre en favoris'><?php echo $balfav; ?></span></td>
		<td><span><?php echo $nom_liste; ?></td>
	<?php } else { 
		if(isset($bal['liste_bal'])) {
			echo '<td></td><td><span>'.$nom_liste.'</td>';
		} else {
	?>
		<td></td>
		<td><span>(<?php echo substr($bal['id_expediteur'],0,20); ?>...) </span><span class="bal_listeid curspointer">[voir]</span>
			<div class="bal_infobulle">
				<?php
					$matricule =  preg_split('#/|\.|-#', $bal['id_expediteur']);
					foreach ($matricule as $mat){
						echo nom_perso($mat,true).'<br />';
					}

				?>
			</div>
		</td>	
	<?php } } ?>	
	<td class='bal_titre_sujet' onclick="afficher_bal('<?php echo $bal['id_bals']; ?>',<?php echo $id_perso; echo ", '$page'"; ?>);"><div id='titre_sujet_<?php echo $bal['id_bals']; ?>'><?php echo $bal['titre']; ?></div></td>
	<td><?php echo $bal['date']; ?></td>
</tr>

<tr class='bal_content' id='cont_<?php echo $bal['id_bals']; ?>'>
	<td id='bal_<?php echo $bal['id_bals']; ?>' colspan='6' style='display:none;'>
		<div class='bal_outil'>
			<img class='curspointer scroll' onclick="contacter('<?php echo $bal['id_expediteur']; ?>','<?php echo $titre; ?>','<?php echo $reponse_simple; ?>');" src='../images/site/reply.png' title='Répondre'/ >
			<?php if(!$send) { ?><img class='curspointer scroll' onclick="repondre('<?php echo $bal['liste_mats']; ?>','<?php echo $titre; ?>','<?php echo $reponse_tous; ?>');" src='../images/site/reply_all.png' title='Répondre à tous'/ > <?php } ?>
			<?php if($recu) { ?><img class='curspointer' src='../images/site/folder_files.png' title='Archiver'/ onclick="bal_archive('<?php echo $bal['id_bals']; ?>',<?php echo $id_perso; ?>);" /> <?php } ?>
			<img class='curspointer' src='../images/site/delete.png' title='Supprimer' onclick="bal_del('<?php echo $bal['id_bals']; ?>',<?php echo $id_perso; if($send) echo ", 'send'"; ?>);"/>
		</div>
		<div class='bal_corps' id='bal_content_<?php echo $bal['id_bals']; ?>'></div>
	</td>
</tr>

<?php
$i++;
}
?>
</table>

<div class='bal_align_left'>
<input type='checkbox' id='cocheTout' />
<span id='cocheText'></span>
<input class="bal_button" type="button" value="Supprimer" onclick="bal_del_all('<?php echo $id_perso; if($send) echo "', 'send"; ?>');" />
<?php if($recu) { ?><input class="bal_button" type="button" value="Archiver" onclick="bal_archive_all('<?php echo $id_perso; ?>');" /><?php } ?>
<?php if(!$send) { ?><input class="bal_button" type="button" value="Marquer comme lu" onclick="bal_lu_all('<?php echo $id_perso; if($send) echo "', 'send'"; ?>');" /><?php } ?>
</div>
<br />
<table class='bal_tab' width='900px' style='display:none;'>
<tr>
	<td width='100px'></td>
	<td width='300px' align='center' valign='top'>
		<table class='bal_repertoire' width='250px'>
			<tr>
				<td class='bal_titre_td'>Repertoire</td>
			</tr>	
			<tr>
				<td>
				liste<br />
				liste
				</td>
			</tr>
		</table>
	</td>
	<td width='100px'></td>
	<td width='300px' align='center' valign='top'>
		<table class='bal_repertoire' width='250px'>
			<tr>
				<td class='bal_titre_td'>Liste d'envoie</td>
			</tr>	
			<tr>
				<td>
				liste<br />
				liste
				</td>
			</tr>
		</table>	
	</td>
	<td width='100px'></td>
</tr>
</table>

<br />
<form id="bal-form">
<?php include("formulaire_bal.php"); 

$js->addLib('ckeditor/ckeditor');
$js->addLib('ckeditor/adapters/jquery');
$js->addLib('ckeditor/sample');
$js->addScript('messagerie');
$js->addScript('wysiwyg');

?>
</form>
</div>
<?php
	}else{
		echo "<h2>Vous ne possédez pas ce personnage</h2><div align='center'><img src='/images/site/erreur.png' alt='erreur'><p>Veuillez vous loguer ou ne pas abuser de la bal, Merci.</p></div>";
		include(SERVER_ROOT."/template/footer_new.php");exit;
	}


//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
