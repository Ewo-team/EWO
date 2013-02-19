<?php

use conf\VariableStorage as VariableStorage;

/**
 * Fonction général pour ewo
 *
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package conf
 * @category fonctions
 */

/**
 * Gestion du temps de chargement des pages PHP.
 * @return microtime
 */
function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

/**
 * lister les dossier pour trouver les templates
 * @return string $template
 */
function template_list() {

    $rep = "../template/themes/";
    $dir = opendir($rep);

    //les dossiers (is_dir) ou les fichiers (is_file)
    while ($f = readdir($dir)) {
        if (is_dir($rep . $f) AND $f != '.' AND $f != '..' AND $f != '.svn') {
            $template[] = $f;
        }
    }
    return $template;
    closedir($dir);
}

/**
 * vérification du type de mail et si oui ou non le joueur veut recevoir le mail
 * @param $perso_id
 * @return mail : true or false et type : html ou text
 */
function mail_defaut($perso_id) {
    $sql = "SELECT options FROM persos WHERE id = '" . $perso_id . "'";
    $resultat = mysql_query($sql) or die(mysql_error());
    $option = mysql_fetch_array($resultat);

    if ($option[0][4] == "1") {
        $bal_type = 'text';
    } else {
        $bal_type = 'html';
    }

    if ($option[0][3] == "1") {
        $bal_rec = 'true';
    } else {
        $bal_rec = 'false';
    }
    return $mail = array("mail" => $bal_rec, "type" => $bal_type);
}

/**
 * Ajouter la signature par defaut ou non
 * @param $perso_id
 */
function signature_defaut($perso_id) {
    $sql = "SELECT options FROM persos WHERE id = '" . $perso_id . "'";
    $resultat = mysql_query($sql) or die(mysql_error());
    $signature = mysql_fetch_array($resultat);

    if ($signature[0][0] == "1") {
        $signature_def = true;
    } else {
        $signature_def = false;
    }
    return $signature_def;
}

/**
 * Utilisation de BBCODE
 * @param $str chaine de caractere a parser
 * @return $str retour de la chaine parser et completer du BBBCODE
 */
function bbcode_format($str) {
    // Convert all special HTML characters into entities to display literally
    $str = htmlentities($str);
    // The array of regex patterns to look for
    $format_search = array(
        '#\[b.*?\](.*?)\[/b.*?\]#is', // Bold ([b]text[/b]
        '#\[i.*?\](.*?)\[/i.*?\]#is', // Italics ([i]text[/i]
        '#\[u.*?\](.*?)\[/u.*?\]#is', // Underline ([u]text[/u])
        '#\[s.*?\](.*?)\[/s.*?\]#is', // Strikethrough ([s]text[/s])
        '#\[quote.*?\](.*?)\[/quote.*?\]#is', // Quote ([quote]text[/quote])
        '#\[code.*?\](.*?)\[/code.*?\]#is', // Monospaced code [code]text[/code])
        '#\[size=([1-9]|1[0-9]|20).*?\](.*?)\[/size.*?\]#is', // Font size 1-20px [size=20]text[/size])
        '#\[color=\#?([A-F0-9]{3}|[A-F0-9]{6}).*?\](.*?)\[/color.*?\]#is', // Font color ([color=#00F]text[/color])
        '#\[url=((?:ftp|https?)://.*?).*?\](.*?)\[/url.*?\]#i', // Hyperlink with descriptive text ([url=http://url]text[/url])
        '#\[url.*?\]((?:ftp|https?)://.*?)\[/url.*?\]#i', // Hyperlink ([url]http://url[/url])
        '#\[img.*?\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img.*?\]#i' // Image ([img]http://url_to_image[/img])
    );
    // The matching array of strings to replace matches with
    $format_replace = array(
        '<strong>$1</strong>',
        '<em>$1</em>',
        '<span style="text-decoration: underline;">$1</span>',
        '<span style="text-decoration: line-through;">$1</span>',
        '<blockquote>$1</blockquote>',
        '<pre>$1</' . 'pre>',
        '<span style="font-size: $1px;">$2</span>',
        '<span style="color: #$1;">$2</span>',
        '<a href="$1">$2</a>',
        '<a href="$1">$1</a>',
        '<img src="$1" alt="imageForum" />'
    );
    // Perform the actual conversion
    $str = preg_replace($format_search, $format_replace, $str);
    // Convert line breaks in the <br /> tag
    $str = nl2br($str);
    $str = html_entity_decode($str);
    return $str;
}

/**
 * Tronquage des texts longs
 * @param $chaine chaine de caractaire brut
 * @param $lg_max taille du troncage
 * @return $chaine Chaine tronqué a la taille maxi avec '...' rajouté en fin de ligne
 */
function tronquage($chaine, $lg_max) {
    //$lg_max = 500; //nombre de caractère autoriser
    if (strlen($chaine) > $lg_max) {
        $chaine = substr($chaine, 0, $lg_max);
        $last_space = strrpos($chaine, " ");
        $chaine = substr($chaine, 0, $last_space) . "...";
    }
    return $chaine;
}

/**
 * Grille Damier
 *
 * Determine si la grille du damier est On ou Off
 * @param $id_utilisateur ID de l'utilisateur
 * @return $damier_grille true or false en fonction de la réponse
 */
function grille_damier($id_utilisateur) {
    $compte = new compte\Compte($id_utilisateur);
    return $compte->grille;
}

/**
 * Rose Damier
 *
 * Determine si la rose des vents doit être affiché en boite ou sur le damier
 * @param $id_utilisateur ID de l'utilisateur
 * @return $damier_rose true or false en fonction de la réponse
 */
function rose_damier($id_utilisateur) {
    $compte = new compte\Compte($id_utilisateur);
    return $compte->rose;
}

/**
 * Redirection aprés la connexion
 * @param $id_utilisateur ID de l'utilisateur
 * @return Retourne le lien aprés redirection
 */
function redirection_connexion($id_utilisateur) {
    $compte = new compte\Compte($id_utilisateur);
    $page = $compte->redirection;

    if ($page == 1) {
        $redirec = '';
    } elseif ($page == 2) {
        $redirec = '/persos/liste_persos.php';
    } elseif ($page == 3) {
        $redirec = '/forum/';
    } else {
        $redirec = '';
    }
    return $redirec;
}

/**
 * Transcription d'une date US en Date FR
 * @param $date Date au format US
 * @return $date_fr Date au format FR
 */
function date_fr($date) {
    $date = explode(' ', $date);
    $year = $date[0];
    $heure = $date[1];
    $year = explode('-', $year);
    $annee = $year[0];
    $mois = $year[1];
    $jour = $year[2];
    $date_fr = $jour . '-' . $mois . '-' . $annee . ' ' . $heure;
    return $date_fr;
}

/**
 * Affichage du : heure, minutes, seconde depuis la reception d'une bal en fonction d'une date.
 * @param $date_var Timestamp d'une date
 * @return $date_retour Affiche le nombre de temps depuis la reception d'un bal en , minutes, secondes, heures.
 */
function date_compare($date_var) {
    $date = strtotime($date_var);
    $date_courante = time();

    if (floor(($date_courante - $date) / 60) > 59) {
        $moment = floor(($date_courante - $date) / (60 * 60));
        $sufix = "heures";
    } elseif (floor(($date_courante - $date)) > 59) {
        $moment = floor(($date_courante - $date) / 60);
        $sufix = "mins";
    } else {
        $moment = floor(($date_courante - $date));
        $sufix = "secs";
    }

    $date_retour['moment'] = $moment;
    $date_retour['sufix'] = $sufix;

    return $date_retour;
}

/**
 * test le format de la date (un equivalent à is_date qui n'existe pas)
 * format accepte: annee-mois-jour heure:minutes:secondes
 * @param $date Date
 * @return Retourne true ou false en fonction de si le format est correcte ou non
 */
function is_date($date) {
    if (!isset($date) || $date == "") {
        return false;
    }

    @list($yy, $mm, $dd) = @explode("-", $date);
    @list($dd, $h) = @explode(" ", $dd);
    @list($h, $m, $s) = @explode(":", $h);

    return @checkdate($mm, $dd, $yy) && is_numeric($h) && is_numeric($m) && is_numeric($s);
}

/**
 * Statistique sur le nombre de personnage inscrit en fonction de la race
 * @param $race ID d'une race
 * @return $nbperso Nombres de personnage inscrit de cette race.
 */
function statistique_perso_inscrit($race) {
    $persos = "SELECT count(race_id) AS nbperso FROM persos WHERE race_id=" . $race . "";
    $resultat = mysql_query($persos) or die(mysql_error());
    $nbperso = mysql_fetch_array($resultat);
    return $nbperso['nbperso'];
}

/**
 * Statistique sur le nombre de joueur inscrit
 * @return $nbperso Nombre d'utilisateur inscrit sur EWO
 */
function statistique_joueur_inscrit() {
    $persos = "SELECT count(id) AS nbjoueur FROM utilisateurs";
    $resultat = mysql_query($persos) or die(mysql_error());
    $nbperso = mysql_fetch_array($resultat);
    return $nbperso['nbjoueur'];
}

/**
 * Statistique sur le nombre de persos vivant par race grade plan
 * Race: 3 Ange, 4 Demon, 1 Humain
 * Plan: 1 Terre, 2 Enfer, 3 Paradis
 * Grade: de 0 à 5.
 * @param $race Id de la race
 * @param $grade Nom du grade, par defaut -1 si non demandé
 * @param $plan Id du plan, par defaut -1 si non demandé
 * @return $nbperso Nombre de perso en fonction de la demande
 */
function statistique_persos_vivant($race, $grade = -1, $plan = -1) {
    if (isset($race) AND $grade == -1 AND $plan == -1) {
        $persos = "SELECT count(persos.id) AS nbpersos
						FROM persos
							INNER JOIN damier_persos
								ON damier_persos.perso_id = persos.id
									WHERE persos.race_id = '" . $race . "'";
    } elseif (isset($race) AND $grade != -1 AND $plan == -1) {
        $persos = "SELECT count(persos.id) AS nbpersos
						FROM persos
							INNER JOIN damier_persos
								ON damier_persos.perso_id = persos.id
									WHERE persos.race_id = '" . $race . "' AND persos.grade_id='" . $grade . "'";
    } elseif (isset($race) AND $grade != -1 AND $plan != -1) {
        $persos = "SELECT count(persos.id) AS nbpersos
						FROM persos
							INNER JOIN damier_persos
								ON damier_persos.perso_id = persos.id
									WHERE persos.race_id = '" . $race . "' AND persos.grade_id='" . $grade . "' AND damier_persos.carte_id = '" . $plan . "'";
    }

    $resultat = mysql_query($persos) or die(mysql_error());
    $nbperso = mysql_fetch_array($resultat);

    return $nbperso['nbpersos'];
}

/**
 * Verification de la session de connexion a l'api
 * @param $id
 * @return $retour Retourne un json_encode() avec 'connexion' 'noconnexion'
 */
function api_verifconnexion($id) {
    if (!isset($id)) {
        echo json_encode(array('statut' => 'noconnexion'));
        exit;
    } else {
        $retour = array('statut' => 'connexion');
        return $retour;
    }
}

/**
 * Récuperation du nom de la carte
 * @param numeric $id ID du plan
 * @return string $plan Retourne le nom du plan
 */
function get_plan($id) {
    $plans = "SELECT nom FROM cartes WHERE id=" . $id . "";
    $resultat = mysql_query($plans) or die(mysql_error());
    $plan = mysql_fetch_array($resultat);
    return $plan['nom'];
}

/**
 * Recuperation du nom d'une cibles
 * @param $id ID de l'objet
 * @param $type Type de l'objet
 * @param $afficheMatricule, par defaut false, si true affiche le matricule de l'objet
 * @return string Nom de l'objet
 */
function nom_cible($id, $type, $afficheMatricule = false) {
    if ($type == "persos") {
        return nom_perso($id, $afficheMatricule);
    } elseif ($type == "objet_complexe") {
        return nom_objet_complexe($id, $afficheMatricule);
    } elseif ($type == "porte") {
        return nom_porte($id, $afficheMatricule);
    } elseif ($type == "objet_simple") {
        return nom_objet_simple($id, $afficheMatricule);
    } elseif ($type == "porte_mauve") {
        return nom_porte($id, $afficheMatricule);
    } elseif ($type == "bouclier") {
        return nom_bouclier($id, $afficheMatricule);
    } elseif ($type == "action") {
        return nom_action($id, $afficheMatricule);
    }
}

/**
 * Récuperation du nom d'un personnage
 * @param $id ID du personnage
 * @param $afficheMatricule, par defaut false, si true affiche le matricule de l'objet
 * @return string $nom Retourne le nom d'un personnage
 */
function nom_perso($id, $afficheMatricule = false, $italique = true) {
    if (isset($id) && is_numeric($id)) {
        $storage_nom = VariableStorage::Consulte('persos.pseudo.' . $id . '.nom');
        $storage_titre = VariableStorage::Consulte('persos.pseudo.' . $id . '.titre');

        if (!$storage_nom) {
            $noms = "SELECT nom, titre FROM persos WHERE id=" . $id . "";
            $resultat = mysql_query($noms) or die(mysql_error());
            $nom = mysql_fetch_array($resultat);
            \conf\VariableStorage::Sauve('persos.pseudo.'.$id.'.nom', $nom['nom'], 60*60);
            \conf\VariableStorage::Sauve('persos.pseudo.'.$id.'.titre', $nom['titre'], 60*60);
        } else {
            $nom['nom'] = $storage_nom;
            $nom['titre'] = $storage_titre;
        }

        $it1 = $it2 = '';

        if ($italique) {
            $it1 = '<i>';
            $it2 = '</i>';
        }

        if (isset($nom['titre'])) {
            $pseudo = $nom['nom'] . ' - ' . $it1 . $nom['titre'] . $it2;
        } else {
            $pseudo = $nom['nom'];
        }

        if ($afficheMatricule) {
            return $pseudo . "[" . $id . "]";
        } else {
            return $pseudo;
        }
    }

    return '(personnage supprimé)';
}

/**
 * Récuperation du nom de l'action
 * @param $id ID de l'action
 * @param $afficheMatricule, par defaut false, si true affiche le matricule de l'objet
 * @return string $nom Retourne le nom de l'action
 */
function nom_action($id, $afficheMatricule = false) {
    $noms = "SELECT nom FROM action WHERE id=" . $id . "";
    $resultat = mysql_query($noms) or die(mysql_error());
    $nom = mysql_fetch_array($resultat);
    if ($afficheMatricule) {
        return $nom['nom'] . "[" . $id . "]";
    } else {
        return $nom['nom'];
    }
}

/**
 * Nom d'un objet simple
 * @param $id ID de l'action
 * @param $afficheMatricule, par defaut false, si true affiche le matricule de l'objet
 * @return string $nom Retourne le nom d'un objet simple
 */
function nom_objet_simple($id, $afficheMatricule = false) {
    $noms = "SELECT nom FROM categorie_objet_simple WHERE id=" . $id . "";
    $resultat = mysql_query($noms) or die(mysql_error());
    $nom = mysql_fetch_array($resultat);
    if ($afficheMatricule) {
        return $nom['nom'] . "[" . $id . "]";
    } else {
        return $nom['nom'];
    }
}

/**
 * Nom d'un objet complexe
 * @param $id ID de l'action
 * @param $afficheMatricule, par defaut false, si true affiche le matricule de l'objet
 * @return string $nom Retourne le nom d'un objet complexe
 */
function nom_objet_complexe($id, $afficheMatricule = false) {
    $noms = "SELECT nom FROM categorie_objet_complexe WHERE id=" . $id . "";
    $resultat = mysql_query($noms) or die(mysql_error());
    $nom = mysql_fetch_array($resultat);
    if ($afficheMatricule) {
        return $nom['nom'] . "[" . $id . "]";
    } else {
        return $nom['nom'];
    }
}

/**
 * Nom d'une porte
 * @param $id ID de l'action
 * @param $afficheMatricule, par defaut false, si true affiche le matricule de l'objet
 * @return string $nom Retourne le nom d'une porte
 */
function nom_porte($id, $afficheMatricule = false) {
    $noms = "SELECT nom FROM damier_porte WHERE id=" . $id . "";
    $resultat = mysql_query($noms) or die(mysql_error());
    $nom = mysql_fetch_array($resultat);
    if ($afficheMatricule) {
        return $nom['nom'] . "[" . $id . "]";
    } else {
        return $nom['nom'];
    }
}

/**
 * Nom d'un bouclier
 * @param $id ID de l'action
 * @param $afficheMatricule, par defaut false, si true affiche le matricule de l'objet
 * @return string $nom Retourne le nom d'un bouclier
 */
function nom_bouclier($id, $afficheMatricule = false) {
    $noms = "SELECT nom FROM damier_bouclier WHERE id=" . $id . "";
    $resultat = mysql_query($noms) or die(mysql_error());
    $nom = mysql_fetch_array($resultat);
    if ($afficheMatricule) {
        return $nom['nom'] . "[" . $id . "]";
    } else {
        return $nom['nom'];
    }
}

/**
 * Recupération du nom de la race
 * @param $race_id Id de la race concernée
 * @param String $nom_race Nom de race personnalisé
 * @return $result Retourne le nom de la race
 */
function nom_race($race_id, $nom_race = null) {
    if ($nom_race)
        return $nom_race;
    $sql = "SELECT nom
		FROM races
			WHERE race_id=$race_id AND grade_id=-2";
    $result = mysql_query($sql) or die(mysql_error());
    $result = mysql_fetch_array($result);
    return $result['nom'];
}

/**
 * Récupère le type de jeu associé à la race
 * @param $race Id de la race dont on cherche le type de jeu
 * @return Retourne le type de jeu
 */
function recup_type($race) {
    if ($race != 0) {
        $sql = "SELECT type
					FROM races
						WHERE race_id = $race LIMIT 1";

        $reponse = mysql_query($sql) or die(mysql_error());
        $reponse = mysql_fetch_array($reponse);
        return $reponse['type'];
    }else
        return 3;
}

/**
 * Récuperation de l'id de l'utilsiateur avec l'id d'un perso
 * Vérification de l'existance de l'appartenance d'un perso a un utilisateur
 * @param $id_personnage Id du personnage
 * @param $idutilisateur Id de l'utilisateur si vérification de l'appartenance
 * @return $iduser ou false
 */
function id_utilisateur($idduperso, $idutilisateur = 'none') {
    if ($idutilisateur == 'none') {
        $persoid = "SELECT utilisateur_id FROM persos WHERE id='" . $idduperso . "'";
    } else {
        $persoid = "SELECT utilisateur_id FROM persos WHERE id='" . $idduperso . "' AND utilisateur_id='" . $idutilisateur . "'";
    }
    $result = mysql_query($persoid) or die(mysql_error());
    $iduser = mysql_fetch_row($result);
    if ($iduser != false) {
        return $iduser[0];
    } else {
        return false;
    }
}

/*
 * Récupération de l'adresse email d'un utilisateur
 * @param $id_utilisateur
 * @return $mail Retourne le mail de l'utilisateur
 */

function mail_utilisateur($id_utilisateur) {
    $persoid = "SELECT email FROM utilisateurs WHERE id='" . $id_utilisateur . "'";
    $result = mysql_query($persoid) or die(mysql_error());
    $mail = mysql_fetch_row($result);
    return $mail[0];
}

/**
 * Fonction de controle et de netoyage de chaine
 * $type = num ou char
 */
function clean_up($var, $type) {
    switch ($type) {
        //- test si la var est numeric
        case "num";
            if (is_numeric($var)) {
                return $var;
            } else {
                return false;
            }
            break;
        //- test si la var est un char et nettoi les carac
        case "char";
            if (is_string($var)) {
                $var = trim($var);
                //$var = htmlentities($var);
                //$var = htmlspecialchars($var);
                $var = str_replace("/", '', $var);
                $var = str_replace("\\", '', $var);
                $var = mysql_real_escape_string($var);
                return $var;
            } else {
                return false;
            }
            break;
    }
}

/**
 * Affichage de la page d'erreur avec le message passer en param
 * @param $titre : Titre de l'erreur
 * @param $text : texte a afficher au client
 * @param $lien : lien de retour ou doit etre rooter le client par defaut ./news.php
 * @param $redirec Si $redirec est a 1 : redirection javascript, si a 0 redirection php
 */
function gestion_erreur($titre, $text, $lien, $redirec = 0) {
    if (empty($lien)) {
        $lien = './../../..';
    }
    $_SESSION['message']['titre'] = $titre;
    $_SESSION['message']['text'] = $text;
    $_SESSION['message']['lien'] = $lien;

    if ($redirec == 0) {
        header("location:" . SERVER_URL . "/msg/message.php");
        exit;
    } else {
        echo "<script language='javascript' type='text/javascript' >document.location='" . SERVER_URL . "/msg/message.php'</script>";
        exit;
    }
}

/**
 * Affichage du galon d'un perso
 * @param $id ID du personnage.
 * @return $url_icone Retourne lien lien du galon
 */
function galon_persos($id) {

    $sql = "SELECT galon_id, grade_id FROM persos WHERE id = '$id'";
    $resultat = mysql_query($sql) or die(mysql_error());
    $carac = mysql_fetch_array($resultat);

    $id_galon = $carac['galon_id'];
    $id_grade = $carac['grade_id'];
    if ($id_galon == 0) {
        $url_icone = "galons/galon.png";
    } else {
        if ($id_grade >= 2) {
            $id_galon = ($id_grade - 1) * 4 + $id_galon - 1;
        }
        //-- Selection du galon du persos
        $sql1 = "SELECT*FROM icone_galons	WHERE id= '$id_galon'";
        $resultat1 = mysql_query($sql1) or die(mysql_error());
        $carac = mysql_fetch_array($resultat1);

        $url_icone = $carac['icone_url'];
    }
    return $url_icone;
}

/**
 * Selection de l'icone d'un personnage.
 * @param $id_perso ID du personnage.
 * @return $carac Lien de l'icone du personnage
 */
function icone_persos($id_perso) {

    $sql = "SELECT persos.icone_id, races.camp_id as camp, races.type as type, persos.grade_id as grade, persos.sexe as sexe, caracs.px as xp
	FROM persos
	INNER JOIN races ON (races.race_id = persos.race_id AND persos.grade_id = races.grade_id)
	INNER JOIN caracs ON (caracs.perso_id = persos.id)
	WHERE persos.id = $id_perso";
    $resultat = mysql_query($sql) or die(mysql_error());
    $carac = mysql_fetch_array($resultat);

    if (!empty($carac['icone_id'])) {
        // Il y a une icone perso
        $sql = "SELECT icone_url
						FROM icone_persos
						WHERE id = " . $carac['icone_id'];
        $resultat = mysql_query($sql) or die(mysql_error());
        $icone = mysql_fetch_array($resultat);
        return $icone['icone_url'];
    } else {
        // Pas d'icone perso


        $xp = $carac['xp'];
        $camp = $carac['camp'];
        $type = $carac['type'];
        $grade = $carac['grade'];
        $sexe = $carac['sexe'];


        $sql = "SELECT * FROM icone_persos WHERE
			camp_id=$camp AND (type = $type OR type = 3) AND
                (grade_id = $grade OR grade_id = -3) AND (sexe_id = $sexe OR sexe_id = 1) AND
                xp_min < $xp AND xp_max > $xp";
        $resultat = mysql_query($sql) or die(mysql_error());

        $choix = array();

        while ($icone = mysql_fetch_array($resultat)) {
            if ($icone['grade_id'] == $grade && $icone['type'] == $type && $icone['sexe_id'] == $sexe) {
                // Elle correspond, on la retourne
                return $icone['icone_url'];
            }

            if ($icone['grade_id'] == $grade && $icone['type'] == $type) {
                // choix 1
                $choix[1] = $icone['icone_url'];
            }

            if ($icone['grade_id'] == $grade && $icone['sexe_id'] == $sexe) {
                // choix 2
                $choix[2] = $icone['icone_url'];
            }

            if ($icone['type'] == $type && $icone['sexe_id'] == $sexe) {
                // choix 3
                $choix[3] = $icone['icone_url'];
            }

            if ($icone['grade_id'] == $grade) {
                // choix 4
                $choix[4] = $icone['icone_url'];
            }

            if ($icone['type'] == $type) {
                // choix 5
                $choix[5] = $icone['icone_url'];
            }

            if ($icone['sexe_id'] == $sexe) {
                // choix 6
                $choix[6] = $icone['icone_url'];
            }
        }

        sort($choix);

        $url = array_shift($choix);

        if (isset($_SESSION['utilisateur']['icones_pack'])) {
            return $_SESSION['utilisateur']['icones_pack'] . '/' . $url;
        }

        return $url;
    }


    /*

      // sexe : (sexe = $sexe OR sexe = 1) ORDER BY sexe ASC

      if(empty($carac['icone_id'])){
      //-- Selection de l'icone du persos
      $sql_grade = "SELECT persos.grade_id, persos.race_id
      FROM persos
      WHERE persos.id='$id_perso'";
      $res_grade = mysql_query ($sql_grade) or die (mysql_error());
      $grade = mysql_fetch_array ($res_grade);

      $race_id = $grade['race_id'];
      $grade_id = $grade['grade_id'];

      if ($race_id>4){
      $sql = "SELECT camp_id FROM `races` WHERE race_id=$race_id LIMIT 1 ";
      $use_race = mysql_query($sql) or die(mysql_error());
      $use_race = mysql_fetch_array($use_race);
      $race_id = $use_race['camp_id'];
      }

      $sql1 = "SELECT C.px
      FROM caracs C
      WHERE C.perso_id = '$id_perso'";
      $resultat1 = mysql_query ($sql1) or die (mysql_error());
      $carac = mysql_fetch_array ($resultat1);
      $px = $carac['px'];
      $sql1 = "SELECT icone_persos.icone_url
      FROM icone_persos
      WHERE icone_persos.race_id = $race_id AND icone_persos.grade_id = $grade_id AND ($px BETWEEN icone_persos.xp_min AND icone_persos.xp_max)";
      $resultat1 = mysql_query ($sql1) or die (mysql_error());
      $carac = mysql_fetch_array ($resultat1);
      if (!isset($carac['icone_url']))
      {
      $sql1 = "SELECT icone_persos.icone_url
      FROM icone_persos
      WHERE icone_persos.race_id = $race_id AND icone_persos.grade_id = 0 AND ($px BETWEEN icone_persos.xp_min AND icone_persos.xp_max)";
      $resultat1 = mysql_query ($sql1) or die (mysql_error());
      $carac = mysql_fetch_array ($resultat1);
      }
      }else{
      //-- Selection de l'icone du persos

      }
      return $carac['icone_url']; */
}

/**
 * Detecte la présence d'une sidebar dans le dossier courant
 * @param $location header ou autre
 */
function detect_sidebar($location) {
    global $template_url;
    global $width;
    global $width_page;
    global $width_content_jeu;
    global $pagetype;
    if (dirname($_SERVER['PHP_SELF']) == '/') {
        $link = '';
    } else {
        $link = dirname($_SERVER['PHP_SELF']);
    };
    $sidebar = $_SERVER['DOCUMENT_ROOT'] . $link . "/sidebar.php";

    if (isset($pagetype) && $pagetype == 'accueil') {
        /*
         *                     
         */
        if ($location == 'header') {
            echo "<!-- End Header -->
                    <div id='page'>
                            <!-- Start Content -->
                            <div id='content_accueil'>
                                    
                                           ";
                        } else {
                            echo "<div class='separation'></div>
                                            <div class='clear'></div>
                                            </div>
                                    </div>
                            ";
        }
    } else {

        if (file_exists($sidebar)) {

            if ($location == 'header') {
                echo "<!-- End Header -->
    <div id='page'>
            <!-- Start Content -->
            <div id='content_bg'>
                    <div id='corps'>
                            <div id='colonne'>";
                include($sidebar);
                echo "</div>
                            <div id='content'>";
            } else {
                echo "<div class='separation'></div>
                            <div class='clear'></div>
                            </div>
                    </div>
            </div>
    </div>";
            }
        } else {


            if ($location == 'header') {
                echo "<!-- End Header -->
                                                            <div id='page' $width_page>
                                                                    <!-- Start Content -->
                                                                    <div id='content_bg_jeu' $width_content_jeu>
";
            } else {
                echo "<!-- FIN DE CODE -->
                                            <div class='separation'></div>
                                            <div class='clear'></div>

                    </div>";
            }
        }
    }
}

/**
 * Detecte la présence d'une sidebar dans le dossier courant
 * @param $location header ou autre
 */
function detect_sidebar_new($location) {
    global $template_url;
    global $width;
    global $width_page;
    global $width_content_jeu;
    if (dirname($_SERVER['PHP_SELF']) == '/') {
        $link = '';
    } else {
        $link = dirname($_SERVER['PHP_SELF']);
    };
    $sidebar = $_SERVER['DOCUMENT_ROOT'] . $link . "/sidebar_new.php";

    if (file_exists($sidebar)) {

        if ($location == 'header') {
            echo '<div id="sidebar">';
            include($sidebar);
            echo '</div>
                    <div id="page" class="pageside">';
        } else {
            echo "</div>";
        }
    } else {


        if ($location == 'header') {
            echo '<div id="page">';
        } else {
            echo "</div>";
        }
    }
}

/**
 * Fonction de sérialisation de tableau
 * @param $array Tableau a sérialiser
 * @param $bdd ...
 * @return $retour Retourne la chaine sérialisé
 */
function seritab($array, $bdd) {
    $retour = '';
    foreach ($array as $key => $value) {
        $retour = $retour . $key . '|' . mysql_real_escape_string($value, $bdd) . '|';
    }
    return $retour;
}

/**
 * Fonction de désérialisation de tableau
 * @param string $seriarray Chaine sérialisé
 * @return array $retour Retourne la chaine sous forme d'un array
 */
function unseritab($seriarray) {
    $explode = explode('|', $seriarray);
    $retour = array();
    $nb = count($explode) - 1;
    for ($inci = 0; $inci < $nb; $inci+=2) {
        $key = $explode[$inci];
        $value = $explode[$inci + 1];
        $retour[$key] = $value;
    }
    return $retour;
}

/**
 * Récupère le champ de valeur dans la table des records en fonction du type et de la race
 * @param $type type de records, chaine de caractère
 * @param $race id de la race
 * @param $val
 * @param $perso_id
 * @return $resultat Champ voulue
 */
function recup_record($type, $race, $val = 'none', $perso_id = '') {
    $regval = "";
    if ($val != 'none') {
        $regval = "AND valeur REGEXP '" . $val . "'";
    }
    if ($type != "Dur&eacute;e_courante" && $type != "Dur&eacute;e_max") {
        $sql = "SELECT valeur AS valeur, perso_id AS persos_id
			FROM ewo.record
				INNER JOIN ewo.persos ON record.perso_id=persos.id
					WHERE (record.type='$type' AND persos.race_id=$race $regval)";
    } else {
        $sql = "SELECT valeur AS valeur, perso_id AS perso_id
						FROM ewo.record
							WHERE (record.type='$type' AND record.perso_id=$perso_id $regval)";
    }

    $resultat = mysql_query($sql) or die(mysql_error());

    return $resultat = mysql_fetch_array($resultat);
}

/**
 * Récupération de tous les record
 * @return $resultat
 */
function recup_all_record() {
    $sql = "SELECT *
			FROM ewo.record";

    $resultat = mysql_query($sql) or die(mysql_error());
    return $resultat;
}

/**
 * Met à jour la table des records en fonction du type
 * @param $type type de records, chaine de caractère
 * @param $perso_id id du perso concerné
 * @param $valeur valeur a mettre sous forme de tableau sérialisé
 * @return $resultat
 */
function maj_record($type, $perso_id, $valeur) {
    $sql = "SELECT race_id
                FROM persos
                    WHERE id = $perso_id";

    $reponse = mysql_query($sql) or die(mysql_error());
    $reponse = mysql_fetch_array($reponse);
    $race = $reponse['race_id'];

    $tabval = unseritab($valeur);
    $val = 'none';
    if (isset($tabval['plan'])) {
        $val = 'plan\\\\|' . $tabval['plan'];
    }
    $regval = "";
    if ($val != 'none') {
        $regval = "AND valeur REGEXP '" . $val . "'";
    }

    $record = recup_record($type, $race, $val, $perso_id);
    if ($record) {
        if ($type != "Dur&eacute;e_courante" && $type != "Dur&eacute;e_max") {
            $sql = "UPDATE ewo.record
					INNER JOIN ewo.persos ON record.perso_id=persos.id
					SET record.perso_id=$perso_id, record.valeur='$valeur'
						WHERE record.type='$type' AND persos.race_id=$race $regval";
        } else {
            $sql = "UPDATE ewo.record
							SET record.valeur='$valeur'
								WHERE record.type='$type' AND record.perso_id=$perso_id $regval";
        }
    } else {
        $sql = "INSERT INTO ewo.record (id, type, perso_id, valeur)
				VALUES ('','$type','$perso_id','$valeur')";
    }
    $resultat = mysql_query($sql) or die(mysql_error());
}

?>
