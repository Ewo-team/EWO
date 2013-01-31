<?php
//-- Header --

$admin = 0;
$acces = "animateur";

require_once __DIR__ . '/../conf/master.php';

include(SERVER_ROOT . "/template/header_new.php");
include(SERVER_ROOT."/persos/fonctions.php");
/*-- Connexion basic requise --*/
ControleAcces('anim;admin',1);

if(ControleAcces('admin',0)) {
    $admin = 1;
    $acces = "administrateur";
}

?>
<h1>Bienvenue dans l'administration d'EWO</1>
<h2>Vous disposez d'un accès <?php echo $acces; ?></h2>

<?php if($admin) { ?>
<h3>Règles du jeu</h3>
<ul>
    <li><a href="<?php echo SERVER_URL.'/admin/gestion_actions/'; ?>">Actions</a></li>
    <li><a href="<?php echo SERVER_URL.'/admin/gestion_camp/'; ?>">Camps</a></li>
    <li><a href="<?php echo SERVER_URL.'/admin/gestion_race/gestion_race.php'; ?>">Races</a></li>
    <li><a href="<?php echo SERVER_URL.'/admin/gestion_grade/gestion_grade.php'; ?>">Grades</a></li>
    <li><a href="<?php echo SERVER_URL.'/admin/gestion_galon/'; ?>">Galons</a></li>
    <li><a href="<?php echo SERVER_URL.'/admin/gestion_icone/'; ?>">Icones</a></li>
    
</ul><?php } ?>

<h3>Gestion active</h3>
<ul>
    <li><a href="<?php echo SERVER_URL.'/admin/persos/creation_perso.php'; ?>">Création de personnage</a></li>
    <li><a href="<?php echo SERVER_URL.'/event/eventperso.php'; ?>">Evenements d'animation</a></li>
    <li><a href="<?php echo SERVER_URL.'/legion/index.php?p=3'; ?>">Gestion des Légions</a></li>   
	<li><a href="<?php echo SERVER_URL.'/admin/persos/liste_pnj.php'; ?>">Liste des PNJ</a></li>
<?php if($admin) { ?>    <li><a href="<?php echo SERVER_URL.'/admin/utilisateurs/'; ?>">Editer utilisateur</a></li>
    <li><a href="<?php echo SERVER_URL.'/admin/persos/'; ?>">Editer personnage</a></li>
    <li><a href="<?php echo SERVER_URL.'/editeur/'; ?>">Editeur de carte (ancien)</a></li>
    <li><a href="<?php echo SERVER_URL.'/carte/generateur.php'; ?>">Generateur de carte</a></li><?php } ?>
</ul>

<h3>Informations</h3>
<ul>
    <li><a href="<?php echo SERVER_URL.'/news/liste_news.php'; ?>">Gestion des news</a></li>
    <li><a href="<?php echo SERVER_URL.'/statistique/stats_grades.php'; ?>">Répartition des grades/galons</a></li>
<?php if($admin) { ?>    <li><a href="<?php echo SERVER_URL.'/admin/persos/ewolution.php'; ?>">Simulateur d'ewolution</a></li>
    <li><a href="<?php echo SERVER_URL.'/admin/newsletter/'; ?>">Newsletter</a></li>
    <li><a href="<?php echo SERVER_URL.'/admin/logs/liste_logs.php'; ?>">Logs des actions</a></li><?php } ?>
</ul>
<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>