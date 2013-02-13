<?php

$nbEventParPage= 25;
$listIdTypeEventPublic = "[5,8,9,10,11,15,16,17]";
//1 : mouvement
//2 : esquive
//3 : esquive_magique
//4 : sprint
//5 : suicide
//6 : entrainement
//7 : transaction
//8 : mort
//9 : meurtre
//10: grade_down
//11: faction_eject
//12: perso
//13: attaque
//14: sort
//15: grade_up
//16: faction_in
//17: faction_out 
//18: Destruction
  

function get_list_typeevent($idPerso){
  if(!isset($_SESSION['persos']['current_id'])){
	$_SESSION['persos']['current_id']=0;
	}
  //retourne la liste des types d'event a afficher
  $current_userid = $_SESSION['persos']['current_id'];
  $list_idTypeEvent = "9999";

  if ($current_userid == $idPerso) { 
    //on affiche tout les events visible uniquement par le perso courant
    $list_idTypeEvent = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21";
  }elseif ($idPerso==-1) { 
    //on affiche les event de tout les persos
    $list_idTypeEvent = "4,5,6,7,9,10,11,12,13,14,15,16,17,18,19,20,21";
  }elseif($idPerso=="mort"){ 
    //on affiche les events public visible par le perso courant
    $list_idTypeEvent = "8";	  
  }else{ 
    //on affiche les events public visible par le perso courant 
    $list_idTypeEvent = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21";
  }
  return $list_idTypeEvent;
}


function affiche_event_prive($idPerso){
  //fonction boolean 
  if(!isset($_SESSION['persos']['current_id'])){
	$_SESSION['persos']['current_id']=0;
	}
  $current_persoid = $_SESSION['persos']['current_id'];
  
  $typeEvent = "public";
  if (id_utilisateur($current_persoid)==id_utilisateur($idPerso)){
    $typeEvent = "prive";
  }else{
    $typeEvent = "public";
  }
  
  return $typeEvent;
}


  
function get_SQL_event($idPerso, $nbJours){
  
  if(!isset($_SESSION['persos']['current_id'])){
	$_SESSION['persos']['current_id']=0;
	}
  $current_persoid = $_SESSION['persos']['current_id'];
  $where_perso = " "; 
  if (nom_perso($idPerso)!=""){
    $where_perso = " (evenement.perso_id = ".$idPerso.") AND ";
  }
  
  $list_idTypeEvent = get_list_typeevent($idPerso);
  
  //SQL de selection des events
  $sql = "SELECT evenement.id, evenement.evenement_type_id, evenement.perso_id , evenement.date, evenement.champs, persos.nom
          				FROM evenement 
          					INNER JOIN persos 
          						ON persos.id = evenement.perso_id 
          							WHERE ".$where_perso." (evenement.date>=(NOW()-".$nbJours."))    
											     AND (evenement.evenement_type_id IN(".$list_idTypeEvent."))
												      ORDER By evenement.date desc, evenement.id ASC";      
  return $sql;
}  
  
  
function Affiche_liste_event($idPerso, $nbEvent, $nbJours){
  //recuperation et execution de la requete de selection des events
  $sql = get_SQL_event($idPerso, $nbJours)." LIMIT ".$nbEvent;						
  $resultat = mysql_query ($sql) or die (mysql_error());    
  
  while ($event = mysql_fetch_array ($resultat)){  
    affiche_event($event);    
	}
}

function serievent($array){
	$retour = '';
	foreach($array as $key => $value){
		$retour=$retour.$key.'|'.mysql_real_escape_string($value).'|';
		}
	return $retour;
}

function unserievent($seriarray){
	$explode = explode('|', $seriarray);
	$retour = array();
	$nb = count($explode)-1;
	for($inci=0; $inci<$nb;$inci+=2){
		$key = $explode[$inci];
		$value = $explode[$inci+1];
		$retour[$key]=$value;
		}
	return $retour;
}

function affiche_event($event){
    $id         = $event['id'];
    $id_perso   = $event['perso_id'];						
    $nom        = $event['nom'];
    $event_type	= $event['evenement_type_id'];
    $date_event	= $event['date'];
    $champs     = unserievent($event['champs']);
    $url = icone_persos($id_perso);
  
    $typeEvent = affiche_event_prive($id_perso);
     
  	switch($event_type){
  		case 1:  //Deplacement-------------------------------------------------------------------------------------------------
        
?>
<tr id='event_mouvement'>
  <td>
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'/>
    <?php echo "</td><td>le ".$date_event."</td><td>".$nom.get_phrase_event($event_type) ?>
    
    <?php							
      if ($typeEvent == "prive"){
        echo "<td> x:".$champs['x']." y:".$champs['y'];} 
    ?>
    
    </td>
</tr>
<?php																
  			break;
  		case 13://'attaque'-----------------------------------------------------------------------------------------------------   
		if(!$champs['meurtre']){
		?>
<tr   id='event_attaque'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
      echo "</td><td>le ".$date_event;                
      if (isset($champs['cible'])){
		if($champs['cible_type']=='persos'){
			if ($champs['att']>$champs['def']){
					  $reussite = 1;}else{$reussite = 0;}		
			} else $reussite = 1;
        echo "</td><td >".nom_perso($champs['attaquant'],true).get_phrase_event($event_type,$reussite);
        echo nom_cible($champs['cible'],$champs['cible_type'],true)."</td>";
      } 
      //affichage eléments prive				
			if ($typeEvent == "prive"){
		if($champs['cible_type']=='persos'){
			if ($reussite){
					  echo"<td> score : <b>".$champs['att']."</b>/".$champs['def']."<br/>dégats : ".$champs['degats'];
			}else{
			  echo"<td> score : ".$champs['att']."/<b>".$champs['def']."</b><br/>dégats : ".$champs['degats'];
			}
		}
        if (isset($champs['exp_def'])){ echo "<br/> exp : ".$champs['exp_def'];}
        if (isset($champs['exp_att'])){ echo "<br/> exp : ".$champs['exp_att'];}
                     
      }
    ?></td>
</tr>
<?php	
		}
        break;
  		case 2://'esquive'----------------------------------------------------------------------------------------------------
// Ne sert à rien. L'attaque est esquivée si les dégâts sont nuls.

  			break;
  		case 14://'sort':-----------------------------------------------------------------------------------------------------
?>
<tr   id='event_sort'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
      echo "</td><td>le ".$date_event;                
	if($champs['attaquant']==$id_perso){
			if ($champs['reussite']){
				  $reussite = 1;}else{$reussite = 0;}	
      echo "</td><td >".nom_perso($champs['attaquant'],true).get_phrase_event($event_type,$reussite).nom_action($champs['sort'])."<br/>";
      //création de la liste des victimes
      $liste_vict = "";
      if ($champs['l_perso_vict']!=""){
        $perso_vict = explode(",",$champs['l_perso_vict']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					$liste_vict = $liste_vict.nom_perso($perso_vict[$inci],true).", ";
				} 
      }
      if ($champs['l_os_vict']!=""){
        $perso_vict = explode(",",$champs['l_os_vict']);
        for($inci=0; $inci<count($perso_vict)-1;$inci++){
					$liste_vict = $liste_vict.nom_objet_simple($perso_vict[$inci]).", ";
				} 
      }
      if ($champs['l_oc_vict']!=""){
        $perso_vict = explode(",",$champs['l_oc_vict']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					//echo $inci."idperso : ".$perso_vict[$inci]."<br/>";
          $liste_vict = $liste_vict.nom_objet_complexe($perso_vict[$inci]).", ";
				} 
      }
      if ($champs['l_p_vict']!=""){
        $perso_vict = explode(",",$champs['l_p_vict']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					$liste_vict = $liste_vict.nom_porte($perso_vict[$inci]).", ";
				} 
      }
      if ($champs['l_b_vict']!=""){
        $perso_vict = explode(",",$champs['l_b_vict']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					$liste_vict = $liste_vict.nom_bouclier($perso_vict[$inci]).", ";
				} 
      }
      if ($liste_vict != ""){echo $liste_vict." ont été touché.<br/>";}
      
      //création de la liste des destruction
      $liste_destruction="";      
      if ($champs['l_perso_mort']!=""){
        $perso_vict = explode(",",$champs['l_perso_vict']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					$liste_destruction = $liste_destruction.nom_perso($perso_vict[$inci]).", ";
				} 
      }
      if ($champs['l_os_det']!=""){
        $perso_vict = explode(",",$champs['l_os_det']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					$liste_destruction = $liste_destruction.nom_objet_simple($perso_vict[$inci]).", ";
				} 
      }
      if ($champs['l_oc_det']!=""){
        $perso_vict = explode(",",$champs['l_oc_det']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					$liste_destruction = $liste_destruction.nom_objet_complexe($perso_vict[$inci]).", ";
				} 
      }
      if ($champs['l_p_det']!=""){
        $perso_vict = explode(",",$champs['l_p_det']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					$liste_destruction = $liste_destruction.nom_porte($perso_vict[$inci]).", ";
				} 
      }
      if ($champs['l_b_det']!=""){
        $perso_vict = explode(",",$champs['l_b_det']);
        for($inci=0; $inci<count($perso_vict);$inci++){
					$liste_destruction = $liste_destruction.nom_bouclier($perso_vict[$inci]).", ";
				} 
      }
      if ($liste_destruction != ""){echo $liste_destruction." ont été détruit.<br/>";}
      
      
      //affichage eléments prive				
			if ($typeEvent == "prive"){  
        if ($reussite){
				  echo"</td><td> effets bientot disponibles.";        
        }else{echo"</td><td>";}
        if (isset($champs['exp_def'])){ echo "<br/> exp : ".$champs['exp_def'];}
        if (isset($champs['exp_att'])){ echo "<br/> exp : ".$champs['exp_att'];}
                     
      }
	  }
	  else {
		echo "</td>";
		echo "<td>";
		echo "Bientot disponible</td>";
		echo "<td>";
		echo "Bientot disponible</td>";
			}
    ?></td>
</tr>
<?php			
  			break;
  		case 3://'esquive_magique':		-----------------------------------------------------------------------------------------	
?>
<tr   id='event_esquive_sort'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php    
      echo "</td><td>le ".$date_event." </td><td>".$nom.get_phrase_event($event_type).$champs['sort']." lancé par ".nom_perso($champs['attaquant'],true);
      echo"<td> ";  
   
			if ($typeEvent == "prive"){
        if (isset($champs['exp'])){ echo "</td><td>exp : ".$champs['exp'];}
      }
    ?></td>
</tr>
<?php
  			break;
  		case 4://'sprint':------------------------------------------------------------------------------------------------------
?>
<tr   id='event_sprint'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
	        echo "</td><td>le ".$date_event."</td><td> ".$nom."</td><td > ".get_phrase_event($event_type);
	                
    ?></td>
</tr>
<?php

  			break;
  		case 5://'suicide':-------------------------------------------------------------------------------------------------------
?>
<tr   id='event_suicide'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
      echo "</td><td>le ".$date_event."</td><td> ".$nom;
      if (isset($champs['Attaquant'])){
        echo "</td><td > s'est fait suicider par ".nom_perso($champs['Attaquant'],true);
      }else{
        echo "</td><td > ".get_phrase_event($event_type); //MODIF ajouter reussite
      }
    ?></td>
</tr>
<?php	
  			break;
  		case 6://'entrainement':--------------------------------------------------------------------------------------------------
			
?>
<tr   id='event_entrainement'>
  <td>
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?>
    ' alt='avatar'></td>
<?php 
  echo "<td>le ".$date_event."</td><td> ".$nom."</td><td > ".get_phrase_event($event_type);
  if (isset($champs['cible'])){
    echo " avec ".nom_perso($champs['cible'],true)."</td>";
  }else{
    echo " tout seul.</td>";
  }	  
	
	if ($typeEvent == "prive"){
      echo "</td><td> Il a gagné : ".$champs['px_pi'];              
  }          
    ?></td>
</tr>
<?php				
  			break;
  		case 7://'transaction' :----------------------------------------------------------------------------------------------------			
?>
<tr   id='event_transaction'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
      echo "le ".$date_event." ".$nom.get_phrase_event($event_type);
				
			if ($typeEvent == "prive"){
	        //echo "le ".$date_event." ".$nom;
       }	                
    ?></td>
</tr>
<?php				
			
  			break;
  		case 8://'mort':-------------------------------------------------------------------------------------------------------------
?>
<tr   id='event_mort'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
  </td>
<?php  
	        echo "<td>le ".$date_event."</td><td> ".$nom.get_phrase_event($event_type).nom_perso($champs['attaquant'],true);
          if ($typeEvent == "prive"){
	          echo "</td><td>causes de la mort :".$champs['type_mort']." score : <b>".$champs['att']."</b>/".$champs['def']."<br/>effets : ".$champs['liste_effet'];
          }	                                 
    ?></td>
</tr>
<?php
  			break;
  		case 9://'meurtre':---------------------------------------------------------------------------------------------------------
?>
<tr   id='event_meurtre'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
  </td>
<?php  
	        echo "<td>le ".$date_event."</td><td> ".$nom."</td><td > ".nom_perso($id_perso,true).get_phrase_event($event_type).$champs['liste_victime'];
    ?></td>
</tr>
<?php
				
  			break;
		case 18://'destruction':---------------------------------------------------------------------------------------------------------
?>
<tr   id='event_destruction'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
					echo "</td><td>le ".$date_event."</td><td> ".$nom."</td><td > ";
    ?></td>
</tr>
<?php				
  			break;
  		case 15://'grade_up':-------------------------------------------------------------------------------------------------------			
?>
<tr   id='event_grade_up'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
	        echo "</td><td>le ".$date_event."</td><td> ".$nom."</td><td > ".get_phrase_event($event_type).$champs['grade'].".";
    ?></td>
</tr>
<?php			
  			break;
  		case 10://'grade_down':------------------------------------------------------------------------------------------------------
?>
<tr   id='event_grade_down'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
	        echo "</td><td>le ".$date_event."</td><td> ".$nom."</td><td > ".get_phrase_event($event_type).$champs['grade']."."; 
    ?></td>
</tr>
<?php
  			break;
  		case 16://'faction_in':------------------------------------------------------------------------------------------------------
?>
<tr   id='event_faction_in'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
	        echo "le ".$date_event." ".$nom;
    ?></td>
</tr>
<?php
  			break;
  		case 17://'faction_out':----------------------------------------------------------------------------------------------------
?>
<tr   id='event_faction_out'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
	        echo "le ".$date_event." ".$nom;
    ?></td>
</tr>
<?php
  			break;
  		case 11://'faction_eject':--------------------------------------------------------------------------------------------------
?>
<tr   id='event_faction_eject'>
  <td >
    <td >
      <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
	        echo "le ".$date_event." ".$nom;
      ?></td>
</tr>
<?php
  			break;
  		case 12://'perso':-------------------------------------------------------------------------------------------------------------
			if ($typeEvent == "prive"){
?>
<tr   id='event_perso'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
	        echo "le ".$date_event." ".$nom;
    ?></td>
</tr>
<?php
				
			}else{
?>
<tr   id='event_perso'>
  <td >
    <img src='<?php echo SERVER_URL; ?>/images/<?php echo $url; ?> ' alt='avatar'>
<?php  
	        echo "le ".$date_event." ".$nom;
    ?></td>
</tr>
<?php
			}
  	break;
  }
  	
}
function getNbEvents($idPerso, $nbJours){
    
  //SQL de selection des events
  $sql = get_SQL_event($idPerso, $nbJours);						
  $resultat = mysql_query ($sql) or die (mysql_error());    
  
  $inc=0;	
  while ($event = mysql_fetch_array ($resultat)){
    $inc++;
  }
  return $inc;
}

function get_mort_event($idPerso="", $depuisJour="", $nbJours=0, $asc="DESC", $where_race="", $where_grade="", $limit='LIMIT 0,50'){
  //SQL de selection des morts
	$where_perso="";
	if($idPerso!=""){
		$where_perso = " (evenement.perso_id = ".$idPerso.") AND ";
		}
	if($asc=="ASC"){
		$asc="ASC";
		}else $asc= "DESC";
		
	$list_idTypeEvent = "8";
	$nbJours*=24*3600;
	if($depuisJour==""){
	$time=date('d-m-Y');
	
	}else{
		$time=$depuisJour;
		}
		
	$Jour=strtotime($time)+24*3600;
	$JourProf=$Jour-$nbJours;

	$Jour = date('YmdHis', $Jour);
	$JourProf = date('YmdHis', $JourProf);
	$sql = "SELECT evenement.id, evenement.evenement_type_id, evenement.perso_id , evenement.date, evenement.champs, persos.nom
          				FROM evenement 
          					INNER JOIN persos 
          						ON persos.id = evenement.perso_id 
          							WHERE ($where_perso (evenement.evenement_type_id =$list_idTypeEvent) AND
											(evenement.date>=($JourProf)) AND (evenement.date<($Jour)) $where_race $where_grade)
												      ORDER By evenement.date $asc, evenement.id ASC $limit";      

  $resultat = mysql_query ($sql) or die (mysql_error());    
  
  return $resultat;
}  

function get_phrase_event($id_event,$reussite = 1){
    $phrase_event = null;
    
    //1://'mouvement'
    $tab_phrase_event[1][1][0] = " s'est déplacé";
    $tab_phrase_event[1][1][1] = " a fait un pas";    
		//2://'esquive':
		$tab_phrase_event[2][1][0] = " a esquivé une attaque portée par ";
    $tab_phrase_event[2][1][1] = " a esquivé l'agression de ";
    $tab_phrase_event[2][1][2] = " a esquivé une charge de ";
    $tab_phrase_event[2][0][0] = " a pris un coup de ";
    $tab_phrase_event[2][0][1] = " a été agressé par ";
    $tab_phrase_event[2][0][2] = " a subi une charge de ";
		//3://'esquive_magique':
		$tab_phrase_event[3][1][0] = " a esquivé le sort ";
    $tab_phrase_event[3][1][1] = " a detourné le sort ";        
		//4://'sprint':
		$tab_phrase_event[4][1][0] = " pousse un petit sprint.";
    $tab_phrase_event[4][1][1] = " est chaud pour un jogging.";
    $tab_phrase_event[4][1][2] = " accélère.";    
		//5://'suicide':
		$tab_phrase_event[5][1][0] = " a réussi sa tentative de suicide.";
    $tab_phrase_event[5][1][1] = " a trouvé un raccourci.";
    $tab_phrase_event[5][0][0] = " a misérablement raté sa tentative de suicide.";
    $tab_phrase_event[5][0][1] = " a lamentablement échoué dans sa tentative de suicide.";
    $tab_phrase_event[5][0][1] = " agonise après l'échec de sa tentative de suicide.";     
		//6://'entrainement':
		$tab_phrase_event[6][1][0] = " s'entraine ";
    $tab_phrase_event[6][1][1] = " fait des exercices ";
		//7://'transaction' :
		$tab_phrase_event[7][1][0] = " a ramassé ";
    $tab_phrase_event[7][1][1] = " a trouvé ";    
		//8://'mort':
		$tab_phrase_event[8][1][0] = " est mort face à ";
    $tab_phrase_event[8][1][1] = " est abbatu par ";
    $tab_phrase_event[8][1][2] = " est désincarné par ";
    $tab_phrase_event[8][1][3] = " est exécuté par ";
		//9://'meurtre':
		$tab_phrase_event[9][1][0] = " a tué ";
    $tab_phrase_event[9][1][1] = " a désincarné ";
    $tab_phrase_event[9][1][2] = " a exécuté ";
    $tab_phrase_event[9][1][3] = " a pacifié ";
		//10://'grade_down':
		$tab_phrase_event[10][1][0] = " est destitué(e) au grade ";
    $tab_phrase_event[10][1][1] = " est rabaissé(e) au grade ";
    $tab_phrase_event[10][1][2] = " est dégradé à l'échelon ";
		//11://'faction_eject':
		$tab_phrase_event[11][1][0] = " est exclu(e) de la faction ";
    $tab_phrase_event[11][1][1] = " est radié(e) de la faction ";
    $tab_phrase_event[11][1][2] = " est rejeté(e) de la faction ";
		//12://'perso':  Je ne sais pas a quoi sert cet event c'est un comble^^
		$tab_phrase_event[12][1][0] = "erreur_event";
    $tab_phrase_event[12][0][0] = "erreur_event";    
		//13://'attaque':
		$tab_phrase_event[13][1][0] = " a frappé ";
    $tab_phrase_event[13][1][1] = " a molesté ";
    $tab_phrase_event[13][1][2] = " a blessé ";
    $tab_phrase_event[13][1][3] = " a cogné ";
    $tab_phrase_event[13][0][0] = " a manqué son  crochet sur ";
    $tab_phrase_event[13][0][1] = " a essayé de molester ";
    $tab_phrase_event[13][0][2] = " a chatouillé ";
    //14://'sort':    
    $tab_phrase_event[14][1][0] = " a lancé le sort ";
    $tab_phrase_event[14][1][1] = " a utilisé la magie ";
    $tab_phrase_event[14][0][0] = " a raté son attaque magique  ";
    $tab_phrase_event[14][0][1] = " a manqué son sort ";
		//15://'grade_up':
		$tab_phrase_event[15][1][0] = "erreur_event";
    $tab_phrase_event[15][1][1] = "erreur_event";
		//16://'faction_in':
		$tab_phrase_event[16][1][0] = "erreur_event";
    $tab_phrase_event[16][1][1] = "erreur_event";
		//17://'faction_out':
		$tab_phrase_event[17][1][0] = "erreur_event";
    $tab_phrase_event[17][1][1] = "erreur_event";
		//18://'destruction':
		$tab_phrase_event[18][1][0] = "erreur_event";
    $tab_phrase_event[18][1][1] = "erreur_event";
    
	  $id_temp = rand(0,count($tab_phrase_event[$id_event])-1);
    $phrase_event = $tab_phrase_event[$id_event][$reussite][$id_temp];
      
		return $phrase_event;
}

function getEventsScheme($type){
		/**
		 * Les types d'évènements existants sont :
		 * enum(
		 * 'mouvement',		'attaque',	'esquive',		'sort',	'esquive_magique',
		 * 'sprint',		'suicide',	'entrainement',	'transaction',	'mort',
		 * 'meurtre',		'grade_up',	'grade_down',	'faction_in',
		 * 'faction_out',	'faction_eject',			'perso')
		 */
		$eventScheme = null;
		switch($type){
			case 'mouvement':
				$schemeID    = 1;
				$eventScheme = array('field','x','y');
				break;
			case 'attaque':
			  $schemeID    = 13;
				$eventScheme = array('exp','att','def','MatriculeVictime','degats','cible');
				break;
			case 'esquive':
				$schemeID    = 2;
				$eventScheme = array('exp','att','def','MatriculeAttaquant','degats');
				break;
			case 'sort':
			  $schemeID    = 14;
				$eventScheme = array('exp','reussite','effet_cible','liste_effet_cible','liste_Victime','liste_Esquive','effet_lanceur','liste_effet_lanceur');
				break;
			case 'esquive_magique':
				$schemeID    = 3;
				$eventScheme = array('exp','Attaquant','reussite','esquive','effet_cible','liste_effet_cible','effet_lanceur','liste_effet_lanceur','sort');
				break;
			case 'sprint':
				$schemeID    = 4;
				$eventScheme = array();
				break;
			case 'suicide':
				$schemeID    = 5;
				$eventScheme = array('Attaquant','px_pi');
				break;
			case 'entrainement':
				$schemeID    = 6;
				$eventScheme = array('cible','px_pi');
				break;
			case 'transaction' :
				$schemeID    = 7;
				$eventScheme = array('objet');
				break;
			case 'mort':
				$schemeID    = 8;
				$eventScheme = array('Attaquant');
				break;
			case 'meurtre':
				$schemeID    = 9;
				$eventScheme = array('nombre_mort','liste_victime');
				break;
			case 'destruction':
				$schemeID    = 18;
				$eventScheme = array('nombre_objet','liste_objet');
				break;
			case 'grade_up':
				$schemeID    = 15;
				$eventScheme = array('grade');
				break;
			case 'grade_down':
				$schemeID    = 10;
				$eventScheme = array('grade');
				break;
			case 'faction_in':
				$schemeID    = 16;
				$eventScheme = array();
				break;
			case 'faction_out':
				$schemeID    = 17;
				$eventScheme = array();
				break;
			case 'faction_eject':
				$schemeID    = 11;
				$eventScheme = array();
				break;
			case 'perso':
				$schemeID    = 12;
				$eventScheme = array();
				break;
		}
		if($eventScheme !== null){
			// On valorise l'id du schéma
			$this->typeEvent['id'] = $schemeID;
			// Allez hop on renvoit le schéma 
			return $eventScheme;
		}
		// Type d'évènement inconnu...
		return null;
	}
	
	
/*
	switch($id_event){
			case 1://'mouvement':
        $phrase_event = $phrase_mouvement[];
				break;
			case 13://'attaque':
			
				break;
			case 2://'esquive':
			
				break;
			case 14://'sort':
			
				break;
			case 3://'esquive_magique':
			
				break;
			case 4://'sprint':
			
				break;
			case 5://'suicide':
			
				break;
			case 6://'entrainement':
			
				break;
			case 7://'transaction' :
			
				break;
			case 8://'mort':
			
				break;
			case 9://'meurtre':
			
				break;
			case 18://'destruction':
			
				break;
			case 15://'grade_up':
			
				break;
			case 10://'grade_down':
			
				break;
			case 16://'faction_in':
			
				break;
			case 17://'faction_out':
			
				break;
			case 11://'faction_eject':
			
				break;
			case 12://'perso':  Je ne sais pas a quoi sert cet event c'est un comble^^
			
				break;
		}
		
*/
	
?>
