<?php

/**
 * Affichage d'une légion en particulier
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */
$root_url = '../';

include_once('class/LegionConfig.php.inc');
include_once('class/ManagerFactory.php.inc');
include_once('class/NameManager.php.inc');
include_once('class/DemandeUtilisateur.php.inc');
include_once('fonctions.php.inc');

use legions\ManagerFactory as ManagerFactory;
use legions\LegionConfig as LegionConfig;
use legions\NameManager as NameManager;
use legions\DemandeUtilisateur as DemandeUtilisateur;

$txt = '<strong>Cette l&eacute; n\'existe pas</strong>';

if (isset($_GET['id'])) {

    if (isset($_GET['mat']) AND in_array($_GET['mat'], $_SESSION['persos']['id']) AND
            isset($_GET['a']) && $_GET['a'] == 'l')
        annulerDemande($_GET['mat']);


    //Les classes utiles
    $factory = new ManagerFactory();
    $legions = $factory->get(LegionConfig::all);
    $names = new NameManager();

    //La liste des demandes
    $demandes = new DemandeUtilisateur($_SESSION['utilisateur']['id']);
    $freePerso = array(); //Tableau pour savoir si quelqu'un peut postuler
    $postul = array(); //Tableau qui liste les persos ayant postuler pour la faction actuelle

    foreach ($_SESSION['persos']['id'] as $k => $id) {
        $freePerso[$id] = $_SESSION['persos']['faction']['id'][$k] == 0;
    }
    foreach ($demandes->getDemandes() as $demande) {
        $freePerso[$demande->getPersoId()] = false;
        $postul[] = $demande->getPersoId();
    }

    try {
        $legion = $legions->getLegions($_GET['id']);
        $race = LegionConfig::raceFromIdBdd($legion->getRace());

        //On enlève le message d'erreur
        $txt = '';

        //S'il y a une demande

        if (isset($_POST['perso']) and in_array($_POST['perso'], $_SESSION['persos']['id'])) {

            $id = $_POST['perso'];
            if ($freePerso[$id]) {
                $demandes->addDemande($id, $_GET['id']);
                $freePerso[$id] = false;
                $txt .= '
					<p style="text-align:center">
						<strong>Votre demande a  &eacute;t&eacute; envoy&eacute;e</strong>
					</p>';
            }
            else
                $txt .= '
					<p style="text-align:center">
						<strong>Vous avez d&eacute;j&agrave; postul&eacute; pour une autre l&eacute;gion</strong>
					</p>';
        }

        $txt .= '<h2>' . $legion->getNom() . '</h2>

			<div class="legion">
				<table class="legionPresTable" style="width:90%;margin:auto;">
					<tr>
						<td class="legionTxt_' . LegionConfig::$typeShortName[$race] . '">
							l&eacutegion ' . LegionConfig::$typeAdjName[$race] . '
						</td>
						<td width="33%" class="tdLegionPres">Type : <strong>' . $names->getTypeName($legion->getType()) . '</strong></td>
						<td width="33%" class="tdLegionPres">Alignement : <strong>' . $names->getAlignementName($legion->getAlignement()) . '</strong></td>
					</tr>
				</table>
				<hr class="legionHr"/><br />

                                ';
                                 if ($legion->getLogoUrl() != '') {
                                    $txt .= '<img src="'. $legion->getLogoUrl(). '" alt="image legion" class="legionLogo"/>';
                                }
                                else if (file_exists('blasons/' . $legion->getId() . '.png')) {
                                    $txt .= '<img src="blasons/' . $legion->getId() . '.png" alt="image legion" class="legionLogo"/>';
                                }
                                   $txt .=  '
                                <p>
                                    '.nl2br(stripslashes($legion->getDescr())) . '
                                    		
                <ul>
                	';
                    for($l = 1;$l <= 5;++$l){
                    	$link = 'getLink'.$l;
                    	if(strlen($legion->$link()) > 0)
                    		$txt .= '<li><a href="'.$legion->$link().')">'.$legion->$link().'</a></li>';
                    }
                	$txt .= '
                </ul>
				</p>
				<hr class="legionHr"/><br />
				<table class="legionPresTable"  style="width:90%;margin:auto;">
					<tr>
						<td width="33%">Cr&eacute;e le : <strong>' . $legion->getCreationDate() . '</strong></td>
					';
        $p = '';

        /**
         * Affichage des persos pouvant postuler
         */
        foreach ($_SESSION['persos']['id'] as $k => $v) {
            if ($freePerso[$v] && $legion->getRace() == $_SESSION['persos']['camp'][$k]) {
                $s = '';
                if (isset($_GET['postule']) && $_GET['postule'] == $v)
                    $s = 'selected';
                $p .= '<option value="' . $_SESSION['persos']['id'][$k] . '" ' . $s . '>' . $_SESSION['persos']['nom'][$k] . '</option>';
            }
        }
        if ($p != '')
            $txt .= '
						<td>
							<form method="post" action="index.php?p=2&amp;id=' . $_GET['id'] . '">
								<label for="perso_id">
									Postuler
								</label> :
								<select name="perso" id="perso_id">
								' . $p . '
								</select>
								<input type="submit" value="valider" />
							</form>
						</td>
					';
        $txt .= '
					</tr>
				</table>
				';


        /**
         * Les persos qui ont postuler pour cette faction
         */
        $p = '';
        foreach ($postul as $v) {
            $k = array_keys($_SESSION['persos']['id'], $v);
            $k = $k[0];
            $p .= '
						<li>
							' . $_SESSION['persos']['nom'][$k] . ' :
							<a href="index.php?p=2&amp;id=' . $_GET['id'] . '&amp;mat=' . $_SESSION['persos']['id'][$k] . '&amp;a=l">annuler</a>
						</li>
					';
        }
        if ($p != '')
            $txt .='
				<hr class="legionHr"/><br />
				<p>
					Vos personnages ayant postul&eacute; pour cette l&eacute;gion :
					<ul>
					' . $p . '
					</ul>
				</p>
					';

        $txt .= '
			</div>';
        //var_dump($_SESSION['persos']);
    } catch (Exception $e) {

    }
}

echo $txt;
?>
