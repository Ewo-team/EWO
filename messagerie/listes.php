<?php
/**
 * Affiche les bals recus
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */
 
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
include ("messagerieDAO.php"); 
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
$id_courant = array_search($id_perso, $_SESSION['persos']['id']);

	// Paramètres de connexion à la base de données
	$conn = messagerieDAO::getInstance();

	if($res = $conn->VerifPersoExisteUtilisateur($id_perso, $utilisateur_id)){

		$listes = $conn->SelectInfoListeNumerique(null, null, $id_perso);
		
		$inscription = array();
		$proprietaire = array();
		
		
		foreach($listes as $liste) {
			if($liste['owner'] == $id_perso) {
				$id = $liste['id'];
				$proprietaire[] = $liste;
				$liste_proprietaire[$id] = $liste['liste'];
			}
			
			if($liste['enregistre'] == 1 && ($liste['camp'] == null || $liste['camp'] == $_SESSION['persos']['camp'][$id_courant])) {
				$inscription[] = $liste;
			}
		}
			
		if(!isset($_GET['liste'])) {
?>

<p align='center'>[ <a href='index.php?page=recu&id=<?php echo $id_perso; ?>'>Messages Reçus </a> ] [ <a href='index.php?page=archive&id=<?php echo $id_perso; ?>'>Messages Archivés</a> ] [ <a href='index.php?page=send&id=<?php echo $id_perso; ?>'>Messages envoyés</a> ] [ <a href='listes.php?id=<?php echo $id_perso; ?>'>Listes de BALs</a> ]</p>

<h2>Listes de BALs</h2>

<div id='contact' align='center'>
	<?php 
		if(count($inscription) > 0) { 
			echo '<h3>Listes auquels vous participez</h3>
			<table width="60%">
				<tr>
					<th width="90%">Nom de la liste</th>
					<th width="10%"></th>
				</tr>		
			';
			
			foreach($inscription as $liste) {
			
				?>
				<tr id="in<?php echo $liste['id']; ?>">
					<td><?php echo $liste['libelle']; ?></td>
					<td><?php if($liste['owner'] != $id_perso) { echo "<img class='curspointer' src='../images/site/delete.png' title='Quitter'></a>"; } ?></td>
				</tr>
				<?php 
			}
			
			echo '</table>';
			
		} else {
			echo "<h3>Vous n'êtes sur aucune liste!</h3>
			<p>Pour s'inscrire sur une liste publique, il suffit d'y répondre</p>
			<p>Pour s'inscrire sur une liste privée, demandez à son propriétaire de vous y ajouter";
		}
		
		if(count($proprietaire) > 0) { 
			echo '<h3>Listes dont vous êtes propriétaires</h3>
			<table width="60%">
				<tr>
					<th width="90%">Nom de la liste</th>
					<th width="10%"></th>
				</tr>		
			';
			
			foreach($proprietaire as $liste) {
				?>
				<tr id="ow<?php echo $liste['id'] ?>">
					<td><?php echo $liste['libelle']; ?></td>
					<td>
						<img class='curspointer' src='../images/site/delete.png' title='Supprimer'>
						<img class='curspointer' src='../images/site/reply.png' title='Editer'>
					</td>
				</tr>
				<?php
			}
			
			echo '</table>';
			
		}
                
                $js->addVariables('mat',$id_perso);
                
                ?>
</div>
<?php
		} else {
			$liste = $_GET['liste'];
			if(isset($liste_proprietaire[$liste])) {
				// reformater la liste
				$tableau = explode("|",$liste_proprietaire[$liste]);
				$newtableau = array();
				
				foreach($tableau as $ligne) {
					if(!empty($ligne)) {
						$newtableau[] = $ligne;
					}
				}

				
				$liste_membres = implode(",", $newtableau);
				
				$membres = $conn->SelectMembresListeNumerique($liste_membres);
			?><p align='center'>[ <a href='index.php?page=recu&id=<?php echo $id_perso; ?>'>Messages Reçus </a> ] [ <a href='index.php?page=archive&id=<?php echo $id_perso; ?>'>Messages Archivés</a> ] [ <a href='index.php?page=send&id=<?php echo $id_perso; ?>'>Messages envoyés</a> ] [ <a href='listes.php?id=<?php echo $id_perso; ?>'>Listes de BALs</a> ]</p>

<h2>Edition d'une liste</h2>

<div id='contact' align='center'>
	<?php 
		
		echo '<h3>Membres de la liste</h3>
		Matricule : <input id="mat" type="number"> <img id="ajoute" src="../images/site/reply.png" title="Ajouter"/ ><input type="hidden" id="liste_id" value="li'.$liste.'">
		<table width="60%" id="liste_membres"  border="1">
			<tr>
				<th width="90%">Pseudo</th>
				<th width="10%"></th>
			</tr>		
		';
		
		foreach($membres as $perso) {
			?>
			<tr class="tableau" id="mat<?php echo $perso['id'] ?>">
				<td><?php echo $perso['nom']; ?> (mat. <?php echo $perso['id']; ?>)</td>
				<td>
					<img class='curspointer' src='../images/site/delete.png' title='Supprimer'>
				</td>
			</tr>
			<?php
		}
		
                $js->addVariables('proprio',$id_perso);
                $js->addScript('messagerie');
                
		?></table>

</div>				
	<?php			
				
			} else {
				echo "<h2>Vous ne possédez pas cette liste</h2><div align='center'><img src='/images/site/erreur.png' alt='erreur'><p>Veuillez vous loguer ou ne pas abuser de la bal, Merci.</p></div>";
				include($root_url."/template/footer_new.php");exit;			
			}
		}
	}else{
		echo "<h2>Vous ne possédez pas ce personnage</h2><div align='center'><img src='/images/site/erreur.png' alt='erreur'><p>Veuillez vous loguer ou ne pas abuser de la bal, Merci.</p></div>";
		include($root_url."/template/footer_new.php");exit;
	}


//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
