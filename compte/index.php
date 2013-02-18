<?php

namespace compte;
use conf\Helpers as Helpers;

/**
 * Compte, Index
 *
 * 	Affiche la page principal du compte
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 */
//-- Header --

require_once __DIR__ . '/../conf/master.php';

include(SERVER_ROOT . "/template/header_new.php");
//------------

$id_utilisateur = $_SESSION['utilisateur']['id'];

$compte = new Compte($id_utilisateur);

$template_list = template_list();

$liste['template'] = array();
foreach ($template_list as $t) {
    $liste['template'][$t] = $t;
}

$liste['redirection'] = array('Page accueil' => '1',
    'Liste des personnages' => '2',
    'Forum' => '3');

$liste['rose'] = array('Rose des vents' => '0',
    'Flèches du damier' => '1');

$default_value = '-- Choix --';
?>

<div id='inscription' align="center">
    <h2>Modification de vos options d'utilisateur</h2>

    <!-- Debut du coin -->
    <div>
                    <!-- conteneur -->

                    <table border="0">
                        <td width='450' align='center'>
                            <table border="0" width="100%">
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <th scope="row" align="right">E-Mail : </th>
                                    <td align="center"><form name='mail' action="update.php?action=email" method="post">
                                        <input name="email" type="text" maxlength="64" value='<?php echo $compte->email; ?>' /> </td>
                                    <td><input type="submit" value="Modifier" class="bouton" /></form></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    <th scope="row" align="right" width='150'>Mot de passe : </th>
                                    <td align="center"><form name='pass' action="update.php?action=pass" method="post">
                                        <input name="pass_modif" type="password" maxlength="64" /></td>
                                    <td><input type="submit" value="Modifier" class="bouton" /></form></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2"><i>Utilisez un mot de passe d'un minimum de 9 caractères pour plus de sécurité.</i></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                <form name='template' action="update.php?action=template" method="post">
                                    <th scope="row" align="right">Thème : </th>
                                    <td>
                                        <?php echo Helpers::getSelectOption($liste['template'], 'template', $compte->template, $default_value); ?>
                                    </td>
                                    <td><input type="submit" value="Modifier" class="bouton" /></td>
                                </form>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2"><i>Apparence site et page de jeu.</i></td>
                                </tr>                        
                                <tr>
                                <form name='grille' action="update.php?action=grille" method="post">
                                    <th scope="row" align="right">Grille :</th>
                                    <td>
                                        <?php
                                        $grille = $compte->grille;
                                        ?>
                                        <input type="checkbox" name="grille" value='ok' <?php if ($grille == true) {
                                            echo " checked";
                                        } ?> />
                                    </td>
                                    <td><input type="submit" value="Modifier" class="bouton" /></td>
                                </form>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2"><i>Afficher la grille sur le damier.</i></td>
                                </tr>                          
                                <tr>
                                <form name='grille' action="update.php?action=rose" method="post">
                                    <th scope="row" align="right">Déplacement :</th>
                                    <td>
                                        <?php echo Helpers::getSelectOption($liste['rose'], 'rose', $compte->rose, $default_value); ?>
                                    </td>
                                    <td><input type="submit" value="Modifier" class="bouton" /></td>
                                </form>
                                </tr>    
                                <tr>
                                    <td></td>
                                    <td colspan="2"><i>Rose des vents, ou depuis le damier.</i></td>
                                </tr>                          
                                <tr>
                                <form name='redirec' action="update.php?action=redirection" method="post">
                                    <th scope="row" align="right">Redirection : </th>
                                    <td>
                                        <?php echo Helpers::getSelectOption($liste['redirection'], 'redirection', $compte->redirection, $default_value); ?>
                                    </td>
                                    <td><input type="submit" value="Modifier" class="bouton" /></td>
                                </form>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2"><i>Redirection après connexion.</i></td>
                                </tr>                          
                                <tr>
                                <form name='vacances' action="update.php?action=vacances" method="post">
                                    <th scope="row" align="right">Vacances : </th>
                                        <?php echo $compte->getVacancesButton(); ?>
                                </form>
                                </tr>	
                                <tr>
                                    <td></td>
                                    <td colspan="2"><i>effectif 48 heures après, à heure pile xxh00.</i></td>
                                </tr>                          
                                <tr>
                                    <td colspan="3" align="center"></td>
                                </tr>
                            </table>
                            </form>
                        </td>
                        <td width='150' align='center'>
                            <img src='../images/site/inscription.png'>
                        </td>
                    </table>

                    <!-- fin conteneur -->
    </div>
    <!-- Fin du coin -->

</div>

<?php
//-- Footer --
include(SERVER_ROOT . "/template/footer_new.php");
//------------
?>
