<?php

namespace site;

/**
 * Affiche le menu dans le header de EWo
 *
 * Ne s'affiche uniquement que si la var $template_on existe.
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package menus
 */

require_once __DIR__ . '/../conf/master.php';

include_once(SERVER_ROOT."/persos/creation/controle_persos.php");

if (isset($template_on)) {


    $menu = array();

    $menu['jeu'][] = array('url' => SERVER_URL, 'nom' => 'Index', 'style' => 'bold');

    if (!isset($_SESSION['utilisateur']['id'])) {
        $menu['jeu'][] = array('url' => SERVER_URL . '/compte/inscription/', 'nom' => 'Inscription');
    }

    $menu['jeu'][] = array('url' => 'http://wiki.ewo-le-monde.com', 'nom' => 'Guide du Jeu');

    $menu['jeu'][] = array('url' => SERVER_URL.'/bug/', 'nom' => 'Bugs');
    $menu['jeu'][] = array('url' => SERVER_URL . '/site/chat/', 'nom' => 'Chat');
    $menu['jeu'][] = array('url' => SERVER_URL . '/site/partenaires/', 'nom' => 'Partenaires');
    $menu['jeu'][] = array('url' => 'http://blog.ewo-le-monde.com', 'nom' => 'Blog d\'Ewo');
    $menu['jeu'][] = array('url' => SERVER_URL . '/site/contact/', 'nom' => 'Contact');
    $menu['jeu'][] = array('url' => SERVER_URL . '/site/boutique/', 'nom' => 'Boutique');

    $is_log = ControleAcces('utilisateur', 0);
    $is_admin = ControleAcces('admin', 0);
    $is_anim = ControleAcces('admin;anim', 0);
    $is_at = ControleAcces('admin;at', 0);

    if ($is_log) {
        $utilisateur_id = $_SESSION['utilisateur']['id'];


        $nbdem = 0;
        $nbdem_fac = 0;

        $nbdems = 'SELECT COUNT(w.vassal) AS nombre
				FROM persos p
                                JOIN wait_affil w
                                    ON w.superieur = p.id
				WHERE p.utilisateur_id = ' . $utilisateur_id;

        $resultat1 = mysql_query($nbdems) or die(mysql_error());
        $nbdem_ = mysql_fetch_array($resultat1);
        $nbdem = $nbdem_['nombre'];

        for ($inci = 1; $inci <= $_SESSION['persos']['inc']; $inci++) {

            $perso_id = $_SESSION['persos']['id'][$inci];
            if (isset($_SESSION['persos']['faction']['droits'][$inci])) {
                $droits = $_SESSION['persos']['faction']['droits'][$inci];
            } else {
                $droits = array(0, 0, 0, 0, 0, 0, 0, 0);
            }

            if ($droits[4] || $droits[0]) {
                $nbdems = "SELECT COUNT(wait_faction.faction_id) AS nombre
						FROM wait_faction
						INNER JOIN persos ON persos.id=$perso_id
						WHERE wait_faction.faction_id = persos.faction_id AND demandeur = '1'";

                $resultat1 = mysql_query($nbdems) or die(mysql_error());
                $nbdem_ = mysql_fetch_array($resultat1);
                $nbdem_fac += $nbdem_['nombre'];
            }
        }
        $nom = 'Jeu';
        $nom_aff = $nom_fac = '';


        if ($nbdem > 0) {
            $nom .= " <span style='color:#f12727'>($nbdem)</span>";
            $nom_aff = "<span style='color:#27f127'>($nbdem)</span>";
        }

        if ($nbdem_fac > 0) {
            $nom .= " <span style='color:#27f127'>($nbdem_fac)</span>";
            $nom_fac = " <span style='color:#27f127'>($nbdem_fac)</span>";
        }


		$nbperso = @$_SESSION['persos']['inc'];


        $menu['utilisateur'][] = array('url' => SERVER_URL . '/persos/liste_persos.php', 'nom' => $nom, 'taille' => 'grand');

        $menu['utilisateur'][] = array('url' => SERVER_URL . '/compte/', 'nom' => 'Mon compte');
        $menu['utilisateur'][] = array('url' => SERVER_URL . '/jeu/carte/', 'nom' => 'Carte du Monde');
        $menu['utilisateur'][] = array('url' => SERVER_URL . '/persos/annuaire/', 'nom' => 'Annuaire');
        $menu['utilisateur'][] = array('url' => SERVER_URL . '/persos/event/', 'nom' => 'Mes &eacute;v&eacute;nements');
        $menu['utilisateur'][] = array('url' => SERVER_URL . '/jeu/classement/', 'nom' => 'Classement');

		if($nbperso > 0) {
			$menu['utilisateur'][] = array('url' => SERVER_URL . '/jeu/affiliation/', 'nom' => 'Affiliations ' . $nom_aff);
			$menu['utilisateur'][] = array('url' => SERVER_URL . '/jeu/legion/', 'nom' => 'Légions personnages' . $nom_fac);			
		}		
		
		$creation = controleCreationPerso($utilisateur_id);
        
// Fin "Savoir si l'utilisateur peut encore créer des persos."
        //$menu['utilisateur'][] = array('url' => SERVER_URL.'/affiliation/liste_persos.php', 'nom' => 'Affiliation personnages'.$nom_aff);
        

        $menu['persos'][] = array('url' => SERVER_URL . '/persos/liste_persos.php', 'nom' => 'Pages de jeu');

        if ($creation['peutCreer']) {
            $menu['persos'][] = array('url' => SERVER_URL . '/persos/creation/', 'nom' => '<i>Créer un personnage</i>');
        }

        $tot_bal = 0;
        for ($inci = 1; $inci <= $nbperso; $inci++) {
            $datetour = $_SESSION['persos']['date_tour'][$inci];
            $datetour = strtotime($datetour);

            $time = time();

            if ($time >= $datetour) {
                $color = "style='color:#f12727'";
            } else {
                $color = "";
            }

            $id_perso = $_SESSION['persos']['id'][$inci];

            $nbbals = "SELECT COUNT(bals.id) AS nombre
					FROM bals
					INNER JOIN persos
					ON bals.perso_src_id = persos.id
					WHERE perso_dest_id = '$id_perso' AND flag_lu = '0'";

            $resultat1 = mysql_query($nbbals) or die(mysql_error());
            $nbbal = mysql_fetch_array($resultat1);
            $_SESSION['persos']['nbbal'][$inci] = $nbbal['nombre'];

            $tot_bal+=$_SESSION['persos']['nbbal'][$inci];

            if ($_SESSION['persos']['mortel'][$inci] != -1) {
                $menu['persos'][] = array('url' => SERVER_URL . '/jeu/index.php?perso_id=' . $inci, 'nom' => '<span id="color_perso_' . $inci . '" ' . $color . ' >' . $_SESSION['persos']['nom'][$inci] . '</span>');
            }
        }

        $nom = 'Messagerie';

        if ($tot_bal > 0) {
            $nom .= " <span style='color:#f12727'>(<span id='bal_total'>$tot_bal</span>)</span>";
        }

        $menu['bal'][] = array('url' => '#', 'nom' => $nom);

        for ($inci = 1; $inci <= $nbperso; $inci++) {
            $nom = $_SESSION['persos']['nom'][$inci];

            if ($_SESSION['persos']['nbbal'][$inci] > 0) {
                $nom .= " <span style='color:#f12727'>(<span id='total_bal_" . $_SESSION['persos']['id'][$inci] . "'>" . $_SESSION['persos']['nbbal'][$inci] . "</span>)</span>";
            }

            if ($_SESSION['persos']['mortel'][$inci] != -1) {
                $menu['bal'][] = array('url' => SERVER_URL . '/messagerie/index.php?id=' . $_SESSION['persos']['id'][$inci], 'nom' => $nom);
            }
        }


        $menu['forum'][] = array('url' => SERVER_URL . '/forum/', 'nom' => 'Forum');


        /* if($is_admin) {
          $menu['admin'][] = array('url' => '#', 'nom' => 'Admin', 'taille' => 'grand');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/utilisateurs/', 'nom' => 'Editer Utilisateur');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/persos/', 'nom' => 'Editer Personnage');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/persos/creation_perso.php', 'nom' => 'Création de personnage');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/persos/ewolution.php', 'nom' => 'Simulateur d\'ewolution');
          $menu['admin'][] = array('url' => SERVER_URL.'/event/eventperso.php', 'nom' => 'Evenement d\'animation');
          $menu['admin'][] = array('url' => SERVER_URL.'/news/liste_news.php', 'nom' => 'Gestion des News');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/gestion_actions/', 'nom' => 'Gestion Actions');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/gestion_camp/', 'nom' => 'Gestion Camps');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/gestion_race/gestion_race.php', 'nom' => 'Gestion Races');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/gestion_grade/gestion_grade.php', 'nom' => 'Gestion Grades');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/gestion_galon/', 'nom' => 'Gestion Galons');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/gestion_icone/', 'nom' => 'Gestion Icônes');
          $menu['admin'][] = array('url' => SERVER_URL.'/legion/index.php?p=3', 'nom' => 'Gestion des Légions');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/gestion_invitation/', 'nom' => 'Gestion des invitations');
          $menu['admin'][] = array('url' => SERVER_URL.'/editeur/', 'nom' => 'Editeur');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/newsletter/', 'nom' => 'Newsletter');
          $menu['admin'][] = array('url' => SERVER_URL.'/admin/logs/liste_logs.php', 'nom' => 'Logs des actions');
          $menu['admin'][] = array('url' => 'http://blog.ewo-le-monde.com/wp-admin/index.php', 'nom' => 'DevBlog');
          $menu['admin'][] = array('url' => SERVER_URL.'/statistique/stats_grades.php', 'nom' => 'Répartition des grades/galons');
          } */

        if ($is_anim) {
            $menu['admin'][] = array('url' => SERVER_URL . '/admin/', 'nom' => 'Powa Pannel');
            //$menu['admin'][] = array('url' => SERVER_URL.'/admin/', 'nom' => 'Administration');
            if ($is_at) {
                $menu['admin'][] = array('url' => '#', 'nom' => 'At');
            }
        }

        if ($is_at && !$is_anim) {
            $menu['at'][] = array('url' => '#', 'nom' => 'At');
            //$menu['at'][] = array('url' => SERVER_URL.'/admin/antitriche', 'nom' => 'Administration');
        }

        $menu['login'][] = array('url' => SERVER_URL . '/session.php', 'nom' => 'Déconnexion', 'class' => 'current');

        /* if($is_anim) {
          $menu['anim'][] = array('url' => '#', 'nom' => 'Animation');
          $menu['anim'][] = array('url' => '#', 'nom' => 'Anim');
          $menu['anim'][] = array('url' => SERVER_URL.'/event/eventperso.php', 'nom' => 'Evenement d\'animation');
          $menu['anim'][] = array('url' => SERVER_URL.'/statistique/stats_grades.php', 'nom' => 'Répartition des grades/galons');
          $menu['anim'][] = array('url' => SERVER_URL.'/affiliation/liste_affilies.php', 'nom' => 'Gestion des Affiliations');
          $menu['anim'][] = array('url' => SERVER_URL.'/legion/index.php?p=3', 'nom' => 'Gestion des Légions');
          $menu['anim'][] = array('url' => SERVER_URL.'/news/liste_news.php', 'nom' => 'Gestion des News');
          } */
    } else {

        $menu['forum'][] = array('url' => SERVER_URL . '/forum/', 'nom' => 'Forum');

        $menu['login'][] = array('url' => SERVER_URL . '/compte/connexion/', 'nom' => 'Connexion');
    }
    ?>
    <div id="nav">
        <div id="top_bar">
            <ul id="menuDeroulant">
                <?php
                foreach ($menu as $nom => $sousmenu) {

                    $html_id = (isset($sousmenu[0]['id'])) ? ' id="' . $sousmenu[0]['id'] . '"' : '';
                    $html_class = (isset($sousmenu[0]['class'])) ? ' class="' . $sousmenu[0]['class'] . '"' : '';
                    ?>
                    <li <?php echo $html_id;
            echo $html_class
                    ?>>

                        <a href="<?php echo $sousmenu[0]['url']; ?>"><?php echo $sousmenu[0]['nom']; ?></a>

                        <?php
                        if (count($sousmenu) > 1) {
                            ?><ul><?php
                    foreach ($sousmenu as $k => $item) {
                        if ($k != 0) {

                            $html_id = (isset($item['id'])) ? ' id="' . $item['id'] . '"' : '';
                            $html_class = (isset($item['class'])) ? '  class="' . $item['class'] . '"' : '';
                            echo '<li ' . $html_id . $html_class . '><a href="' . $item['url'] . '">' . $item['nom'] . '</a></li>';
                        }
                    }
                            ?></ul><?php
            }
            ?>
                    </li>				
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
}
?>
