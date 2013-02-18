<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
include("fonctions.php");
include("../../ia/ia_constants.php");
/*-- Connexion admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

/*
 @TODO Deprecié
 */


include($root_url."/persos/fonctions.php");
include($root_url."/jeu/fonctions.php");

if(!isset($_SESSION['persos']['current_id'])){
	$titre = "Vous n'avez pas de personnage selection&eacute;'";
	$text = "Vous devez passer par la page de jeu pour g&eacute;rer les pnj.";
	$lien = "./../..";
	gestion_erreur($titre, $text, $lien, 1);
}

// D�termine le personnage dont il est question.
$perso_id = $_SESSION['persos']['current_id'];


if (isset($_GET['act'])){
	$act=$_GET['act'];

}else{
	$act="null";
}

switch($act){
	case "switchact":
	  $sql="UPDATE ia_triggers  SET est_actif=".$_GET['tgt']." WHERE matricule=".$_GET['id'];
	  mysql_query($sql) or die(mysql_error()."[$sql]");
	break;
	case "addpnjs":
	
		$lstpnj=$_POST['pnjnames'];
		$liste=explode(",",$lstpnj);
		$pi=$_POST['pnjpis'];
		foreach($liste as $name){
			$name=trim($name);
			if(!create_pnj($name,$pi,$perso_id,$_POST['iatype'],$_POST['iaevol'])){
				echo("Erreur pendant la cr&eacute;ation de $name<br/>");
			}
		}
		echo("Cr&eacute;ation termin&eacute;e!<br/>");

	break;
	case 'rmorder':
	  $id=$_GET['orderid'];
	  $sql="DELETE FROM ia_orders WHERE id_ordre=$id";
	  $res=mysql_query($sql) or die(mysql_error()."[$sql]".__FILE__."@".__LINE__);
	  echo "Ordre supprim&eacute;!<br/>";
	break;
	case 'giverealorder':
	  $matricule=$_GET['id'];
	  $type_dordre=$_POST['ordertype'];
	  $priorite=$_POST['priority'];
	  $donne_par=$perso_id;
	  $ordre_transmissible=$_POST['transmissible']?"1":"0";
	  $ordre_eternel=$_POST['eternal']?"1":"0";
	  $t=time();
	  $gouzigouza=$_POST['target'];
	  $truc=explode(';',$gouzigouza);
	  $x=0;$y=0;$z=0;
	  $matcible=0;
	  if(count($truc)>1){
	    $x=$truc[0];
	    $y=$truc[1];
	    $z=$truc[2];
	  }else{
	    $matcible=$truc[0];
	  }
	  $sql="INSERT INTO ia_orders (matricule,type_dordre,donne_par,ordre_transmissible,priorite,eternel,cible_mat,date_priseordre,cible_x,cible_y,cible_z) VALUES("
		."$matricule,$type_dordre,$perso_id,$ordre_transmissible,$priorite,$ordre_eternel,$matcible,$t,$x,$y,$z)";
	  $res=mysql_query($sql) or die(mysql_error()."[$sql]".__FILE__."@".__LINE__);
	  
	  // reste a interpreter la cible
	case "order":
	?><h1>Liste des ordres du pnj</h1>
	<table border=0 cellpading=2>
	<tr>
	  <th>Age en jours</th>
	  <th>Type d'ordre</th>
	  <th>Cible</th>
	  <th>Priorité</th>
	  <th>Est transmissible</th>
	  <th>Est éternel</th>
	</tr>
<?php
	  $id=$_GET['id'];
	  $sql="SELECT * FROM ia_orders WHERE matricule=$id";
	  $Rres=mysql_query($sql) or die(mysql_error()."[$sql]".__FILE__."@".__LINE__);

	  while(($tbl=mysql_fetch_assoc($Rres))){
	    $age=(time()-$tbl['date_priseordre'])/3600;
	    $type=$ia_ordername[$tbl['type_dordre']];
	    $iaid=$tbl['id_ordre'];
	    $matcible=$tbl['cible_mat'];
	    if($matcible==0){
	      $cible_pos_x=$tbl['cible_x'];
	      $cible_pos_y=$tbl['cible_y'];
	      $cible_pos_z=$tbl['cible_z'];
	      $cible="(X=$cible_pos_x;Y=$cible_pos_y;Z=$cible_pos_z)";
	    }else{
	      	  $sql="SELECT nom FROM persos WHERE id=$matcible";
		  $res=mysql_query($sql) or die(mysql_error()."[$sql]".__FILE__."@".__LINE__);
		  $tbl2=mysql_fetch_assoc($res);
		  $nom=$tbl2['nom'];
		  $cible="$nom ($matcible)";
	    }
	    $priorite=$tbl['priorite'];
	    $est_transmissible=($tbl['ordre_transmissible']==1?"x":"");
	    $est_eternel=($tbl['eternel']==1?"x":"");
	    echo "<tr>"
		 ."<td>$age</td>"
		 ."<td>$type</td>"
		 ."<td>$cible</td>"
		 ."<td>$priorite</td>"
		 ."<td>$est_transmissible</td>"
		 ."<td>$est_eternel</td>"
		 ."<td><a href='main.php?act=rmorder&orderid=$iaid'>Supprimer</a></td>"
		 ."</tr>";
	  }

?>
</table><hr/>
	  <?php echo "<form method=post action='main.php?act=giverealorder&id=$id'>"; ?>
	  <select name='ordertype'>
	  <?php
	  foreach($ia_ordername as $val=>$name){
	    echo "<option value='$val'>$name</option>";
	  }

	  ?></select>
	  Matricule cible, ou position en X;Y;Z: <input type=text name='target'/> <br/>
	  Prioritit&eacute; (1= la plus basse): <input type=text name='priority' value='20'/> <br/>
	  <input type=checkbox name='eternal'/> Eternel<br/>
	  <input type=checkbox name='transmissible'/> Transmissible aux IA alli&eacute;es proches<br/>
	  <input type=submit />
	  </form>
<?php




	break;
	case "discarn":
	      echo "Pas implémenté!";
	break;
	case "summon":
     	      echo "Pas implémenté!";
	break;
	case "rmpnjs":
	      echo "pas implémenté!";
	break;

}

	?>

	<h1>Liste de vos PNJ</h1><hr/>
	<table border=0 CELLPADDING=2>
	<?php
	  $idu = $_SESSION['utilisateur']['id'];
	  $sqlreq="SELECT P.id AS id, P.nom AS nom,C.px AS px, I.est_actif AS actif, I.state AS state FROM persos P, ia_triggers I, caracs C WHERE C.perso_id = P.id AND P.id = I.matricule AND P.utilisateur_id=$idu";
	  $res=mysql_query($sqlreq) or die(mysql_error()."[$sqlreq]");
	  while($rw=mysql_fetch_assoc($res)){
	  $sqlreq="SELECT * from ia_orders WHERE matricule=".$rw['id']." ORDER BY priorite DESC";
	  $ures=mysql_query($sqlreq) or die(mysql_error()."[$sqlreq]");
	    $ures=mysql_fetch_assoc($ures);
	    if($ures['cible_mat']>0){
	      $sqlreq="SELECT * from damier_persos WHERE perso_id=".$ures['cible_mat'];
	      $ures=mysql_query($sqlreq) or die(mysql_error()."[$sqlreq]");
	      $ures=mysql_fetch_assoc($ures);
	      $tx=$ures['pos_x'];
	      $ty=$ures['pos_y'];
	      $tz=$ures['carte_id'];	      
	    }else{
	      $tx=$ures['cible_x'];
	      $ty=$ures['cible_y'];
	      $tz=$ures['cible_z'];
	    }
	      $sqlreq="SELECT * from damier_persos WHERE perso_id=".$rw['id'];
	      $ures=mysql_query($sqlreq) or die(mysql_error()."[$sqlreq]");
	      $ures=mysql_fetch_assoc($ures);
	      $cx=$ures['pos_x'];
	      $cy=$ures['pos_y'];
	      $cz=$ures['carte_id'];	      

	    $msgtravel="($cx,$cy,$cz)->($tx,$ty,$tz)";
	    echo "<tr>"
	      ."<td>".$rw["nom"]."</td>"
	      ."<td>".$ia_state[$rw["state"]]."</td>"
	      ."<td>".$rw["px"]." px</td>"
	      ."<td><a href='main.php?act=switchact&id=".$rw["id"]."&tgt=".(1-intval($rw["actif"])). "'>".
		      (($rw["actif"]==1)?"D&eacute;sactiver":"Activer")." l'IA</a></td>"
	      ."<td><a href='main.php?act=rmpnjs&id=".$rw["id"]. "'>Supprimer</a></td>"
	      ."<td><a href='main.php?act=order&id=".$rw["id"]. "'>Donner un ordre</a></td>"
// 	      ."<td><a href='main.php?act=discarn&id=".$rw["id"]. "'>D&eacute;sincarner</a></td>"
// 	      ."<td><a href='main.php?act=summon&id=".$rw["id"]. "'>Invoquer</a></td>"
 	      ."<td>$msgtravel</a></td>"
	      ."</tr>";
	  }
	?>
	</table>
	


	<h1>Cr&eacute;ation de peuneujeus:</h1><hr/>
	Entrez la liste des noms des PNJs s&eacute;par&eacute;s par des virgules: ils apparaitrons autour de vous.
	<form action='main.php?act=addpnjs' method='post'>
	<input type=text name='pnjnames' size='62'/> <hr/>
	Entrez la quantit&eacute; de PI qu'ils disposent
	<input type=text name='pnjpis'value="2400"/><hr/>
	Type d'IA : <select name='iatype'>
	<?php
	    foreach($ia_behaviours as $iaindex => $nom){
	      echo ( "<option value=\"$iaindex\">$nom</option>");
	    }
	?>
	</select> (le type d'ia n'est pas pris en compte si le joueur a un maitre &agrave; penser)<hr/>
	Archetipe d'&eacute;volution du pnj : <select name='iaevol'>
	<?php
	     $sqlr="SELECT id_archetype,Nom FROM ia_evolution_archetypes";
	     $rep=mysql_query($sqlr) or die(mysql_error()."[$sqlr]".__FILE__."@".__LINE__);
	      while(($q=mysql_fetch_assoc($rep))){
		print_r($q);
		$archi=$q['id_archetype'];
		$archname=$q['Nom'];
		echo ( "<option value=\"$archi\">$archname</option>");
	      }
	    
	?></select>
	Maitre &agrave; penser (matricule/nom): (Valeure spéciale: MOI : vous ; LUI : le premier pnj de la liste. Par d&eacute;faut : aucun)
	<input type=text name='master'/><hr/>
	<input type='submit'/>
	</form>


	<?php

include($root_url."/template/footer_new.php");
?>

