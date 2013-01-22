<?php
//-- Header --
$root_url = "./..";
$admin = 0;
$acces = "animateur";
include($root_url."/template/header_new.php");
include($root_url."/persos/fonctions.php");
/*-- Connexion basic requise --*/
ControleAcces('anim;admin',1);

if(ControleAcces('admin',0)) {
    $admin = 1;
    $acces = "administrateur";
}


		$menu['admin'][] = array('url' => '#', 'nom' => 'Admin', 'taille' => 'grand');
		$menu['admin'][] = array('url' => $root_url.'/admin/utilisateurs/', 'nom' => 'Editer Utilisateur');
		$menu['admin'][] = array('url' => $root_url.'/admin/persos/', 'nom' => 'Editer Personnage');
		$menu['admin'][] = array('url' => $root_url.'/admin/persos/creation_perso.php', 'nom' => 'Création de personnage');
		$menu['admin'][] = array('url' => $root_url.'/admin/persos/ewolution.php', 'nom' => 'Simulateur d\'ewolution');
		$menu['admin'][] = array('url' => $root_url.'/event/eventperso.php', 'nom' => 'Evenement d\'animation');
		$menu['admin'][] = array('url' => $root_url.'/news/liste_news.php', 'nom' => 'Gestion des News');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_actions/', 'nom' => 'Gestion Actions');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_camp/', 'nom' => 'Gestion Camps');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_race/gestion_race.php', 'nom' => 'Gestion Races');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_grade/gestion_grade.php', 'nom' => 'Gestion Grades');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_galon/', 'nom' => 'Gestion Galons');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_icone/', 'nom' => 'Gestion Icônes');
		$menu['admin'][] = array('url' => $root_url.'/legion/index.php?p=3', 'nom' => 'Gestion des Légions');
		$menu['admin'][] = array('url' => $root_url.'/admin/gestion_invitation/', 'nom' => 'Gestion des invitations');
		$menu['admin'][] = array('url' => $root_url.'/editeur/', 'nom' => 'Editeur');
		$menu['admin'][] = array('url' => $root_url.'/admin/newsletter/', 'nom' => 'Newsletter');
		$menu['admin'][] = array('url' => $root_url.'/admin/logs/liste_logs.php', 'nom' => 'Logs des actions');
		$menu['admin'][] = array('url' => 'http://blog.ewo-le-monde.com/wp-admin/index.php', 'nom' => 'DevBlog');
		$menu['admin'][] = array('url' => $root_url.'/statistique/stats_grades.php', 'nom' => 'Répartition des grades/galons');


?>
<h1>Bienvenue dans l'administration d'EWO</1>
<h2>Vous disposez d'un accès <?php echo $acces; ?></h2>

<?php if($admin) { ?>
<h3>Règles du jeu</h3>
<ul>
    <li><a href="<?php echo $root_url.'/admin/gestion_actions/'; ?>">Actions</a></li>
    <li><a href="<?php echo $root_url.'/admin/gestion_camp/'; ?>">Camps</a></li>
    <li><a href="<?php echo $root_url.'/admin/gestion_race/gestion_race.php'; ?>">Races</a></li>
    <li><a href="<?php echo $root_url.'/admin/gestion_grade/gestion_grade.php'; ?>">Grades</a></li>
    <li><a href="<?php echo $root_url.'/admin/gestion_galon/'; ?>">Galons</a></li>
    <li><a href="<?php echo $root_url.'/admin/gestion_icone/'; ?>">Icones</a></li>
    
</ul><?php } ?>

<h3>Gestion active</h3>
<ul>
    <li><a href="<?php echo $root_url.'/admin/persos/creation_perso.php'; ?>">Création de personnage</a></li>
    <li><a href="<?php echo $root_url.'/event/eventperso.php'; ?>">Evenements d'animation</a></li>
    <li><a href="<?php echo $root_url.'/legion/index.php?p=3'; ?>">Gestion des Légions</a></li>   
	<li><a href="<?php echo $root_url.'/admin/persos/liste_pnj.php'; ?>">Liste des PNJ</a></li>
<?php if($admin) { ?>    <li><a href="<?php echo $root_url.'/admin/utilisateurs/'; ?>">Editer utilisateur</a></li>
    <li><a href="<?php echo $root_url.'/admin/persos/'; ?>">Editer personnage</a></li>
    <li><a href="<?php echo $root_url.'/editeur/'; ?>">Editeur de carte (ancien)</a></li>
    <li><a href="<?php echo $root_url.'/carte/generateur.php'; ?>">Generateur de carte</a></li><?php } ?>
</ul>

<h3>Informations</h3>
<ul>
    <li><a href="<?php echo $root_url.'/news/liste_news.php'; ?>">Gestion des news</a></li>
    <li><a href="<?php echo $root_url.'/statistique/stats_grades.php'; ?>">Répartition des grades/galons</a></li>
<?php if($admin) { ?>    <li><a href="<?php echo $root_url.'/admin/persos/ewolution.php'; ?>">Simulateur d'ewolution</a></li>
    <li><a href="<?php echo $root_url.'/admin/newsletter/'; ?>">Newsletter</a></li>
    <li><a href="<?php echo $root_url.'/admin/logs/liste_logs.php'; ?>">Logs des actions</a></li><?php } ?>
</ul>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>