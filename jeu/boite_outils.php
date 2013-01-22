<?php
	/*
$l_allie="";
$l_ennemi="";
$l_tous="";
if($is_spawn){
$perso_id 	= $_SESSION['persos']['current_id'];
$id 		= $_SESSION['persos']['id'][0];
$nom 		= $_SESSION['persos']['nom'][$id];
$camp 		= $_SESSION['persos']['camp'][$id];
$grade 		= $_SESSION['persos']['grade'][$id];
$galon 		= $_SESSION['persos']['galon'][$id];
$affil 		= $_SESSION['persos']['superieur'][$id];

if (isset($_SESSION['damier_persos']))
{
$liste_perso             = $_SESSION['damier_persos'];
}else {
	echo "<script language='javascript' type='text/javascript' >document.location='./$root_url/'</script>";exit;
	}

$bal_percept = false;
if($grade >= 3 || ($grade==2 && $galon >=2)) {
	$bal_percept = true;
}
	

for($inci=1 ; $inci<=$liste_perso['case']['inc'] ; $inci++){

		if($liste_perso['case']['camp']['id'][$inci]==$camp && $liste_perso['case']['id'][$inci]!=$perso_id){
			if($l_allie==""){
				$l_allie=$liste_perso['case']['id'][$inci];
				}else{
					$l_allie.="-".$liste_perso['case']['id'][$inci];
					}
			}
			elseif($liste_perso['case']['id'][$inci]!=$perso_id) {
				if($l_ennemi==""){
					$l_ennemi=$liste_perso['case']['id'][$inci];
					}else{
						$l_ennemi.="-".$liste_perso['case']['id'][$inci];
						}
				}
		if($liste_perso['case']['id'][$inci]!=$perso_id){
			if($l_tous==""){
						$l_tous=$liste_perso['case']['id'][$inci];
						}else{
							$l_tous.="-".$liste_perso['case']['id'][$inci];
							}
			}
    }
}
?>
<!-- Debut contour -->
<div class="block conteneur" id="block-5">
<div class='draghandle conteneur_titre'>Boite à outils<span id="block-5-button" class='curspointer' onclick="cacherblock('block-5',this.id);"><?php if($visiblock['5'] == 1){echo '[-]';}else{echo '[+]';} ?></span></div>
<div id="layer-block-5" style='display:<?php if($visiblock['5'] == 1){echo 'block';}else{echo 'none';} ?>'>
<!-- conteneur -->
	<a href="../persos/upgrades/upgrades.php">Evoluer</a><br/>
	<?php if($l_allie!="" && $bal_percept){?>
	<a href="../messagerie/index.php?id=<?php echo $perso_id ?>&dest=<?php echo $l_allie ?>#reponse">Envoyer un message aux alli&eacute;s visibles</a><br/>
	<?php }
		if($l_ennemi!="" && $bal_percept){?>
	<a href="../messagerie/index.php?id=<?php echo $perso_id ?>&dest=<?php echo $l_ennemi ?>#reponse">Envoyer un message aux ennemis visibles</a><br/>
	<?php }
		if($l_tous!="" && $bal_percept){?>
	<a href="../messagerie/index.php?id=<?php echo $perso_id ?>&dest=<?php echo $l_tous ?>#reponse">Envoyer un message &agrave; tous</a><br/>
	<?php }?>
<!-- fin conteneur -->
</div>
</div>
<!-- Fin contour -->	
<div class='separation'></div><php */ 

