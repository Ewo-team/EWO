<?php
//-- Header --
$root_url = "..";
if(isset($admin_mode)){
	$root_url = "../..";
	}
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

	$nom = $_SESSION['temp']['perso_nom'];
	$perso_bg = $_SESSION['temp']['perso_bg'];
	$avatar = $_SESSION['temp']['perso_avatar'];
	
	$inc = $_SESSION['temp']['perso_inc'];	
	
	$id = $_SESSION['persos']['id'][$inc];
	$perso_race	= $_SESSION['persos']['race']['nom'][$inc];
	$grade_id	= $_SESSION['persos']['grade'][$inc];
	$perso_grade	= $_SESSION['persos']['grade']['nom'][$inc];			
?>

<div align='center'>
<h2>Votre personnage</h2>

<table class='tab_list_perso'>
	<tr>
		<td class='tab_td_icone'><img src='<?php echo $root_url; ?>/images/<?php echo $avatar; ?>' alt='avatar' title='Avatar de <?php echo $nom; ?>' /></td>
		<td class='tab_td'><a href='<?php echo $root_url; ?>/event/liste_events.php?id=<?php echo $id; ?>'><?php echo $nom; ?></a> (<?php echo $id; ?>)</td>
	</tr>

	<tr>
		<td colspan='2'>
			<table class='tab_list_perso_carac'>
				<tr class='tab_tr_ligne_titre'>
					<td colspan='2'><img class='tab_puce' src='<?php echo $root_url; ?>/images/transparent.png' alt='puce' /> Caractéristiques du personnage :</td>
				</tr>		
				<tr class='tab_tr_ligne0'>
					<td>Nom : </td>
					<td><?php echo $nom; ?></td>
				</tr>
				<tr class='tab_tr_ligne1'>
					<td>Race : </td>
					<td><?php echo $perso_race; ?></td>
				</tr>
				<tr class='tab_tr_ligne0'>
					<td>Grade : </td>
					<td><?php echo $perso_grade; ?> (<?php echo $grade_id; ?>)</td>
				</tr>
				<tr class='tab_tr_ligne_titre'>
					<td colspan='2'><img class='tab_puce' src='<?php echo $root_url; ?>/images/transparent.png' alt='puce' /> Actions du personnage :</td>
				</tr>
				<tr class='tab_tr_ligne0'>
					<td align='center' colspan='2'>
						[<a href='<?php echo $root_url; ?>/persos/editer_perso.php?id=<?php echo $id; ?>'>Editer ce personnage</a>]
			 		</td>
				</tr>
				<tr class='tab_tr_ligne0'>
					<td colspan='2'>Votre personnage est désincarné</td>
				</tr>
				<tr class='tab_tr_ligne1'>
					<td colspan='2'>[<a href='<?php echo $root_url; ?>/jeu/index.php?perso_id=<?php echo $inc; ?>'> Sélectionner une zone de réincarnation </a>]</td>
				</tr>
			</table>
		</td>
	</tr>			
</table>	
	
</div>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
