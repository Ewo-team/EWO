<?php

namespace site\partenaires;

/**
 * Affiche la liste des partenaires ayant participé a Ewo
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package site\partenaires
 */

require_once __DIR__ . '/../../conf/master.php';

$header['title'] = "Partenaires";
$header['desc'] = "Partenaires du projet Ewo ainsi que les scripts open source utilisés.";
include(SERVER_ROOT . "/template/header_new.php");
//------------
?>
    <h1>E.W.O. - Partenaires du projet</h1>



    <p>Au cours de son (long) développement, le projet « EWO » a mis à contribution de nombreuses personnes. Aux origines du projet, l’équipe était bien différente de ce qu’elle est aujourd’hui. Certains ont abandonné par manque de temps, d’autres par manque de motivation ou d’intérêt. Mais tous ont apporté un peu ou beaucoup au jeu que nous connaissons aujourd’hui.</p>

    <p>EWO est un jeu entièrement gratuit, entièrement conçu bénévolement. Alors si nous n’avons rien à leur offrir, nous tenons néanmoins à remercier tous les partenaires du projet ci-dessous. Nous espérons n’avoir oublié personne mais – si c’est le cas – n’hésitez pas à le faire remarquer.</p>


    <h2>Administrateur en Chef, et Chef de Projet : Kazuya</h2>

[i][b]Recherche appliquée et développement[/b][/i]

[u]Programmeurs actifs et chefs de développement:[/u]

- Ganesh
- Elestel
- Kazuya

[u]Programmeurs passés :[/u]

- Nybbas
- Aigleblanc
- Anarion
- Le barge
- Schepi
- Salelodenouye

[u]Contributeurs programmeurs:[/u]

- Erase
- Switch
- Rewolution

[i][b]Enluminures et développement Role Play[/b][/i]

[u]Enlumineurs actifs et chefs de rédaction :[/u]

- Kazuya
- Selvaria

[u]Enlumineurs passés :[/u]

- Deyron
- Anarion
- LoO

[u]Enlumineurs contributeurs :[/u]

- Ganesh
- Elestel
- Jefferson
- Miyuri
- Bohors
- Garba
- Bellamy
- Contrôle
- Silice
- Raoul
- Xizor
- Aigleblanc
- …

[i][b]Design et arts appliqués[/b][/i]

[u]Designers actifs :[/u]

- Aucun pour l’heure. Intéressés, s’adresser à l’administration.

[u]Designers passés :[/u]

- Aigleblanc
- Francis-

[u]Contributeurs designers :[/u]

- Ganesh
- Elestel
[i]
[b]Initiateurs du projet et/ou contributeurs annexes ou passés[/b][/i]

- Aniol
- MiKa
- Salelodenouye    
    
    
    <div class='cadre upperleft'>
        <b>- Développement de EWO -</b>
        <ul>
            <li>Chef de projet</li>
            <li>
                <ul>
                    <li>- Kazuya</li>
                </ul>
            </li>
            <li>Gestionnaire de projet</li>
            <li>
                <ul>
                    <li>- Aigleblanc</li>
                </ul>
            </li>
            <li>Programmeurs actifs</li>	
            <li>
                <ul>
                    <li>- Aigleblanc</li>
                    <li>- Anarion</li>
                    <li>- Kazuya</li>
                    <li>- Salelodenouye</li>
                </ul>
            </li>	
            <li>Programmeurs non-actifs</li>	
            <li>
                <ul>
                    <li>- Le barge</li>
                    <li>- Schepi</li>
                </ul>
            </li>	
            <li>Designers actifs</li>	
            <li>
                <ul>
                    <li>- Aigleblanc</li>
                    <li>- Francis-</li>
                </ul>
            </li>
            <li>Rêgles</li>	
            <li>
                <ul>
                    <li>- Aigleblanc </li>
                    <li>- Aniol</li>
                    <li>- Deyron</li>
                    <li>- Kazuya</li>
                    <li>- Mika</li>
                </ul>
            </li>
        </ul>
    </div>

    <hr class='demon_hr' />

    <div class='cadre2 upperleft'>			
        <b>- Association EWO 1901 -</b>

        <ul>
            <li>Président</li>
            <li>
                <ul>
                    <li>- ????</li>
                </ul>
            </li>
            <li>Vice-Président</li>
            <li>
                <ul>
                    <li>- ????</li>
                </ul>
            </li>		
            <li>Comptable</li>
            <li>
                <ul>
                    <li>- ????</li>
                </ul>
            </li>
            <li>Secrétaire</li>
            <li>
                <ul>
                    <li>- ????</li>
                </ul>
            </li>			
            <li>Membres Actifs</li>
            <li>
                <ul>
                    <li>- ????</li>
                </ul>
            </li>
            <li>Membres d'honneur</li>
            <li>
                <ul>
                    <li>- ????</li>
                </ul>
            </li>				
            <li>Membres</li>
            <li>
                <ul>
                    <li>- ????</li>
                </ul>
            </li>
        </ul>
    </div>

    <hr class='ange_hr' />

    <div class="upperleft">
        <b>- Script Open source utilisés dans EWO -</b>

        <ul>
            <li>- Forum : <a href="http://www.phpbb.com/" alt='PhpBB'>PhpBB 3</a></li>
            <li>- Lib Ajax : <a></a></li>
            <li>- Editeur wysiwyg : </li>
        </ul>
    </div>


<?php
//-- Footer --
include(SERVER_ROOT . "/template/footer_new.php");
//------------
?>
