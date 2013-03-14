<?php

require __DIR__ . '/../../conf/master.php';

$css_files = 'creation';

include(SERVER_ROOT . "/persos/fonctions.php");

include(SERVER_ROOT . "/template/header_new.php");
/* -- Connexion basic requise -- */
ControleAcces('utilisateur', 1);

/* ----------------------------- */

include_once(SERVER_ROOT . "/persos/creation/controle_persos.php");

extract(controleCreationPerso($utilisateur_id));

$sql = "SELECT * FROM `classes` ORDER BY `classes`.`camps` ASC, `classes`.`position` ASC";

$dao = \persos\PersosDAO::getInstance();

$dao->query($sql);

$tableau = $dao->fetchAll_assoc();

        $liste[1] = array();
        $liste[3] = array();
        $liste[4] = array();
        
        $table[1] = array();        
        $table[3] = array();
        $table[4] = array();      

        for ($i = 0; $i < count($tableau); $i++) {
            $kcamp = $tableau[$i]['Camps'];
            $table[$kcamp][0][] = $tableau[$i]['Titre'];
            $key = $tableau[$i]['Titre'] . ', ' . $tableau[$i]['Sub'];
            $value = $tableau[$i]['Id'];
            
            $liste[$kcamp][$key] = $value;
            
            $table[$kcamp][1][] = $tableau[$i]['Sub'];
            $table[$kcamp][2][] = $tableau[$i]['Description'];
        }

$js->AddScript('creation');
$js->AddScript('formstep');
?>

<div id="page_persos">  
    <form action="ajout_perso.php" method="post" name="personnage" id="creation_perso">
        <h2>Informations n&eacute;cessaires &agrave; la cr&eacute;ation de votre/vos personnage(s)</h2>
                <p>
                    <?php echo '<h3>' . $texte . '</h3>';
                        if (isset($_SESSION['erreur']['perso'])) {
                            echo $_SESSION['erreur']['perso'];
                        }
                    ?> <br />
                </p>        
<?php if ($peutCreer) { //DEBUT PEUTCREER  ?>
            <!-- Debut du coin -->
            <!-- conteneur -->
            <fieldset data-validation="choix_du_gp">

                <h3>Choix du gameplay</h3>
                <p><i>Par gameplay, on entend le système de jeu. Que vous choisissiez de jouer Humain ou Ailé, il existe deux possibilités</i></p>

                <table>
                    <tr>
                        <td>
                            <h3>Trois personnages distincts</h3>
                            <img src="<?php echo SERVER_URL; ?>/images/races/T3.png" width="150" height="100">
                            <div class="presentation">
                                <p>Ce gameplay vous permet de jouer trois cases du camp de votre choix. Des Démons, des Anges, ou des Héros de l’Humanité. Vous les jouez de manière distincte et séparée – ils ne peuvent pas interagir entre eux – et donc doivent se trouver éloignés les uns des autres que ce soit dans leur RP ou sur le damier. On nomme ce gameplay “T1”.</p>
                            </div>	
                            <input type="button" class="choixgameplay" name="T3" value="Chuis trop un roxxor!" <?php if (!$creationT3) echo 'disabled'; ?>/>
                        </td>	
                        <td>				
                            <h3>4 personnages en coordination et deux personnage indépendant</h3>
                            <img src="<?php echo SERVER_URL; ?>/images/races/T41.png" width="150" height="100">
                            <div class="presentation">
                                <p>Ce gameplay vous permet de jouer 4 cases du camp de votre choix. Des Diablotins, des Chérubins, ou des Humains. De constitution plus faible que les Anges, Démons et Héros, ils ont cependant l’avantage de pouvoir être joués ensemble sur le damier. Attention, ils mourront facilement, mais pourront aussi bien se montrer d’une redoutable efficacité sur le terrain. Ne comptez pas les jouer chacun dans leur coin, vous ne survivrez pas. On appelle ce Gameplay le “T4”. Notez qu'en plus de ces quatre personnages, vous avez droit à deux personnages de type T1, indépendants.</p>
                            </div>
                            <input type="button" class="choixgameplay" name="T4" value="L'union fait la Force!" <?php if (!$creationT4) echo 'disabled'; ?>/>
                        </td>                        
                    </tr>
                </table>
                <input type="hidden" id="gameplay" name="gameplay" value="" required />
            </fieldset>

    <?php if ($camp == null) { ?>
                <fieldset data-validation="choix_du_camp">


                    <h3>Choix du camp</h3>
                    <div class="presentation">
                        <p>Eternal War One est un jeu. Tout rapprochement entre les créatures d’EWO et les écrits historiques, 
                            bibliques, ou religieux quelconques est à proscrire. Ne laissez libre court qu’à votre imagination, et 
                            surtout ne la bridez pas. Si les inspirations bibliques des combats entre Anges, Démons et Humains 
                            sont évidentes, il ne faut surtout pas croire que EWO se veut fidèle aux croyances populaires.</p>

                        <p>La vision du monde manichéenne telle que décrite – par exemple – dans la Bible ne peut en aucun cas
                            être appliquée à EWO. Les Anges ne représentent pas le Bien, les Démons ne représentent pas le mal car chaque
                            camp souhaite dominer le monde, et seule leur nature les différencie véritablement.</p>
                    </div>

                    <table>


                        <tr align="center">
                            <td class="hover_ange"><h3>Ange</h3></td>
                            <td class="hover_humain"><h3>Humain</h3></td>
                            <td class="hover_demon"><h3>Démon</h3></td>
                        </tr>		
                        <tr align="center">
                            <td class="hover_ange"><img src="<?php echo SERVER_URL; ?>/images/races/ange.png" alt="Race Ange" width="165" height="220"></td>
                            <td class="hover_humain"><img src="<?php echo SERVER_URL; ?>/images/races/humain.png" alt="Race Humain" width="165" height="220"></td>
                            <td class="hover_demon"><img src="<?php echo SERVER_URL; ?>/images/races/demon.png" alt="Race Demon" width="165" height="220"></td>
                        </tr>
                        <tr align="center">
                            <td class="hover_ange">
                                <input type="button" class="choixrace" name="ange" value="Combattre le mal par le mal, pour les Anges c'est normal." />
                                <div id="description_ange" class="presentation">
                                    <p>Créature agréable à l’œil, l’Ange est souvent porteur de l’auréole et arbore des ailes blanches. Il est souvent représenté avec un halo bleuté autour de lui, que l’on attribue à sa connexion à Célestia. Si les Anges ont jadis fait croire à l’Humanité qu’ils étaient les envoyés de Dix-Yeux sur Althian et qu’ils représentaient le Bien, cette description est tout sauf exacte. Les Humains s’en sont, depuis, rendu-compte.</p>

                                    <p>Les Anges sont généralement fourbes et manipulateurs. Derrière leur apparence avenante se cache souvent cruauté et désir de conquête. Ils sont discrets et agissent, bien entendu, pour le bien de leurs propres intérêts de manière subtile. Là où le Démon préfère la force brute, l’Ange adopte généralement la traitrise et le vice. Encore que l’on trouve de tout suivant les caractères. </p>				
                                </div>	                                                    
                            </td>
                            <td class="hover_humain">
                                <input type="button" class="choixrace" name="humain" value=" Les Humains sont faibles ? : on veux des preuves!" />
                                <div id="description_humain" class="presentation">
                                    <p>Les Humains sont dépourvus d’ailes et de tout autre don magique. Ils maîtrisent en revanche la technologie et sont pour la plupart surentraînés au combat, si bien qu’ils peuvent – depuis peu – tenir tête aux hordes d’Ailés qui envahissent Althian.</p>

                                    <p>La liberté est leur crédo. La guerre, leur art le plus abouti. On trouve une variété infinie de caractères différents dans l’Humanité, qui peut faire le charme de cette race. Mais, par nature, et libres de leur destin, les Humains sont généralement querelleurs et désorganisés car incapables de choisir un dirigeant. Leur liberté de choix et leurs guerres internes pourraient coûter son monde à cette race qui attend encore et toujours le héros qui saura l’unifier.</p>

                                    <p>Pourtant, depuis le retour des Ailés sur Althian, la race Humaine semble au moins s'accorder sur un point : bouter dehors tous ces envahisseurs. Un semblant de cohésion se forme alors car les Hommes n'ont pas oublié que, jadis, unis, ils étaient parvenus à se débarrasser de la menace Ailée. </p>				
                                </div>                                                    
                            </td>
                            <td class="hover_demon">
                                <input type="button" class="choixrace" name="demon" value="Démon ? Alors va, et ravage tout sur ton passage." />
                                <div id="description_demon" class="presentation">
                                    <p>D’apparence généralement plus effrayante que celle des Anges, les Démons arborent des cornes et des ailes aussi noires que la nuit la plus profonde. Ils sont généralement entourés d’un halo rougeoyant, rappelant le feu couvant d’un volcan. Ils sont malins et aiment répandre la mort et la souffrance, et ils le font ouvertement la plupart du temps.</p>

                                    <p>Bien sûr, certains savent se montrer subtiles, et les individus sont aussi variés que les Anges, même si l’engeance démoniaque a une tendance plus prononcée pour la violence brute et le crime gratuit.</p>
                                </div>	                                                    
                            </td>
                        </tr>
                    </table>
                </fieldset>
    <?php } ?>


    
            <input type="hidden" id="race" name="race" value="<?php echo $camp; ?>" />

            <fieldset>


            <label for="nom1">Nom du personnage :
                <input id='nom1' name="nom1" type="text" value="" maxlengh='64' />
            </label>

            <label for="sexe1">Sexe : 
				<select id='sexe1' name='sexe1' title='Choisissez le sexe du personnage'>
					<option value='1'>Homme</option>
					<option value='2'>Femme</option>
					<option value='3'>Autre</option>
				</select>
			</label>

			<label for="classe1">Classe : 
				<?php if($camp == null || $camp == "humain") { echo \conf\Helpers::getSelectOption($liste[1], "choixclasse1humain", null, "Sélectionnez votre classe"); } ?>        
				<?php if($camp == null || $camp == "ange") { echo \conf\Helpers::getSelectOption($liste[3], "choixclasse1ange", null, "Sélectionnez votre classe"); } ?>
				<?php if($camp == null || $camp == "demon") { echo \conf\Helpers::getSelectOption($liste[4], "choixclasse1demon", null, "Sélectionnez votre classe"); } ?>
			</label>
            <div id="perso2" class="perso_sup">
                <label for="nom2">Nom du personnage :
                    <input name="nom2" type="text" value="" maxlengh='64' />
                </label>

				<label for="sexe2">Sexe : 					
                <select name='sexe2' title='Choisissez le sexe du personnage'>
                    <option value='1'>Homme</option>
                    <option value='2'>Femme</option>
                    <option value='3'>Autre</option>
                </select>
				</label>

				<label for="classe2">Classe : 
                <?php if($camp == null || $camp == "humain") { echo \conf\Helpers::getSelectOption($liste[1], "choixclasse2humain", null, "Sélectionnez votre classe"); } ?>        
                <?php if($camp == null || $camp == "ange") { echo \conf\Helpers::getSelectOption($liste[3], "choixclasse2ange", null, "Sélectionnez votre classe"); } ?>
                <?php if($camp == null || $camp == "demon") { echo \conf\Helpers::getSelectOption($liste[4], "choixclasse2demon", null, "Sélectionnez votre classe"); } ?>                    
				</label>
            </div>
                
            <div id="perso3" class="perso_sup">
                <label for="nom3">Nom du personnage :
                    <input name="nom3" type="text" value="" maxlengh='64' />
                </label>
				

				<label for="sexe3">Sexe : 
                <select name='sexe3' title='Choisissez le sexe du personnage'>
                    <option value='1'>Homme</option>
                    <option value='2'>Femme</option>
                    <option value='3'>Autre</option>
                </select>
				</label>

				<label for="classe3">Classe : 
                <?php if($camp == null || $camp == "humain") { echo \conf\Helpers::getSelectOption($liste[1], "choixclasse3humain", null, "Sélectionnez votre classe"); } ?>        
                <?php if($camp == null || $camp == "ange") { echo \conf\Helpers::getSelectOption($liste[3], "choixclasse3ange", null, "Sélectionnez votre classe"); } ?>
                <?php if($camp == null || $camp == "demon") { echo \conf\Helpers::getSelectOption($liste[4], "choixclasse3demon", null, "Sélectionnez votre classe"); } ?>                    
				</label>
            </div>
                
            <div id="perso4" class="perso_sup">
                <label for="nom4">Nom du personnage :
                    <input name="nom4" type="text" value="" maxlengh='64' />
                </label>

				<label for="sexe4">Sexe : 
                <select name='sexe4' title='Choisissez le sexe du personnage'>
                    <option value='1'>Homme</option>
                    <option value='2'>Femme</option>
                    <option value='3'>Autre</option>
                </select>
				</label>

				<label for="classe4">Classe : 
                <?php if($camp == null || $camp == "humain") { echo \conf\Helpers::getSelectOption($liste[1], "choixclasse4humain", null, "Sélectionnez votre classe"); } ?>        
                <?php if($camp == null || $camp == "ange") { echo \conf\Helpers::getSelectOption($liste[3], "choixclasse4ange", null, "Sélectionnez votre classe"); } ?>
                <?php if($camp == null || $camp == "demon") { echo \conf\Helpers::getSelectOption($liste[4], "choixclasse4demon", null, "Sélectionnez votre classe"); } ?>                    
				</label>
            </div>                

            <input type="submit" class="submit" name="Submit" value="Donnez vie à vos personnage" />

    <?php 

		$camp_lst['humain'] = 1;
		$camp_lst['ange'] = 3;
		$camp_lst['demon'] = 4;
	
        foreach ($table as $id => $camp_id) {
		
				$display = ($camp == null || $camp_lst[$camp] != $id) ? ' style="display: none;"' : '';
		
                echo '<table id="classe' . $id . '" '.$display.'>';
                foreach ($camp_id as $key => $ligne) {
                    echo '<tr>';
                    foreach ($ligne as $colonne) {
                        if ($key == 0) {
                            echo '<th>', $colonne, '</th>';
                        } else {
                            echo '<td>', $colonne, '</td>';
                        }
                    }
                    echo '</tr>';
                }
                echo '</table>';
            }
    
   ?>                       
            </fieldset>                                            
        </form>
        <!-- fin conteneur -->
    </div>

    <!-- Fin du coin -->

    </div>

    <?php
} // FIN PEUTCREER
if (isset($_SESSION['erreur']['perso'])) {
    $_SESSION['erreur']['perso'] = '';
}
//-- Footer --
include(SERVER_ROOT . "/template/footer_new.php");
//------------
?>
