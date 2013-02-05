<?php
//-- Header --

$header['title'] = "Statistiques de jeu";
require_once __DIR__ . '/../../conf/master.php';

include(SERVER_ROOT . "/template/header_new.php");

//------------
include (SERVER_ROOT."/jeu/statistique/echantillon.php");

if(isset($_SESSION['stat']['default']) AND $_SESSION['stat']['default'] == 'ok'){
	$ret_date_fin = $_SESSION['stat']['ret_date_fin'];
	$ret_date_debut = $_SESSION['stat']['ret_date_debut'];
}else{
	$erreur = "Les dates ne sont pas correctes!";
	$ret_date_fin = time();	
	$ret_date_debut = $ret_date_fin - 86400*10;
}
//AND $ret_date_debut > 1270072800

$liste_utilisateur = liste_utilisateur($ret_date_debut, $ret_date_fin);
$liste_perso = liste_personnage($ret_date_debut, $ret_date_fin, 'total');
?>
	Selection à partir du 01/04/2010
	<p><?php if(isset($erreur)){echo $erreur;}?></p>
	<form name='infos' action="stat_analyse.php" method="post">
		<input type="text" name="debut_date" value="<?php if(isset($_SESSION['date_debut'])){echo $_SESSION['date_debut'];}else{echo '';} ?>" />
		<input type="text" name="fin_date" value="<?php if(isset($_SESSION['date_fin'])){echo $_SESSION['date_fin'];}else{echo '';} ?>" />			
		<input type="submit" value="Modifier" class="bouton" />
	</form>
	
	Structure : <b>jj/mm/yyyy</b><br />
	Pour date debut : <b>day-x</b><br />
	Pour date fin : <b>now</b>

	<?php	
        
        $js->addLib('graph/highcharts');
        $js->addScript('graph/statistiques_joueurs');
        
        $js->addVariables('liste_joueurs_cat',$liste_utilisateur[0]);
        $js->addVariables('liste_joueurs_data',$liste_utilisateur[1]);
        $js->addVariables('liste_persos_cat',$liste_perso[0]);
        $js->addVariables('liste_persos_data',$liste_perso[1]);
        
	?>

		<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div>

		<div id="contain" style="width: 800px; height: 400px; margin: 0 auto"></div>		
<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
