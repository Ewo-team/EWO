<?php
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
include($root_url."/event/fonctions.php");

/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/
?>
<link href="<?php echo($root_url."/css/event.css");?>" rel="stylesheet" type="text/css">

 
<h2>Liste des meurtres</h2>

<div align='center'>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
  			<div id="pageEvent">
          
		      <div align="center">
		
		
  			   </div>
           <div id="spacer">	</div>  
        
       
        
            <?php
                //recup id perso           
            		if (isset($_GET['id'])){ $utilisateur_id = $_GET['id'];
                }else{$utilisateur_id = $_SESSION['utilisateur']['id'];} 
                
                //appel des fonctions d'affichage des mort
                $resultat = get_mort_event($utilisateur_id );
              
                while ($event = mysql_fetch_array ($resultat)){    
                  $id         = $event['id'];
                  $id_perso   = $event['perso_id'];						//id du mort
                  $nom        = $event['nom'];                //nom du mort    
                  $event_type	= $event['evenement_type_id'];
                  $date_event	= $event['date'];
                  $champs     = unserievent($event['champs']);    
                  $idAttaquant = $champs['Attaquant'];   //id de l'attaquant
                  
                  echo nom_perso($idAttaquant,true)." a tué ".$nom."</br>";
                  
                  $url = icone_persos($id_perso);
                }
            ?>
        
				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
</div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
