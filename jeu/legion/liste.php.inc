<?php
/**
 * Légion - Liste des onglets de gauche pour les légions
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package Légion
 */


echo '
		<div style="margin-bottom : 5px;">
                    <div class="legionSection">
                        <p style="vertical-align:middle;text-align:center;">
                            <a href="index.php">Les l&eacute;gions</a>
                        </p>
                    </div>
		</div>';
$admin = $_SESSION['utilisateur']['droits'][1];
$anim  = $_SESSION['utilisateur']['droits'][2];
if($admin || $anim)
    echo '
                <div style="margin-bottom : 5px;">
                    <div class="legionSection">
                        <p style="vertical-align:middle;text-align:center;">
                            <a href="index.php?p=3">Administrer</a>
                        </p>
                    </div>
		</div>';

echo '
	<h2>Vos personnages</h2>';

	for($i=1;$i <= $_SESSION['persos']['inc'];++$i)
		echo '
		<div style="margin-bottom : 5px;">
			<div class="legionPersoLeft">
			<img src="'.$root_url.'/images/'.icone_persos($_SESSION['persos']['id'][$i]).'" alt="avatar"
				title="Avatar de '.$_SESSION['persos']['nom'][$i].'" />
			</div>
			<div class="legionPersoRight">
				<p style="vertical-align:middle;text-align:center;">
				<a href="?p=1&amp;mat='.$_SESSION['persos']['id'][$i].'">'.$_SESSION['persos']['nom'][$i].'</a>
				('.$_SESSION['persos']['id'][$i].')
				</p>
			</div>
		</div>';
?>
