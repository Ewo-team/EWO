<?php
/**
 * Légion - Index affichant la liste des Légions
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package Légion
 */

//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('admin;anim',1);
/*-----------------------------*/
?>

<h2>Liste des Légions affili&eacute;es &agrave; votre race ou mauve</h2>

<?php
include("../faction/fonctions.php");


$admin = $_SESSION['utilisateur']['droits'][1];

if (isset($_POST['Supprimer']))
{
	$faction_id	= mysql_real_escape_string($_POST['faction_id']);
	$retour 	= del_faction($faction_id);
	$_POST['Supprimer'] = NULL;
}

if (isset($_POST['creation']))
{
$nom = ltrim(htmlspecialchars(mysql_real_escape_string($_POST['nom'])));
$verif_nom_existe = mysql_query("SELECT nom FROM factions WHERE nom = '$nom'") or die (mysql_error());
	if (mysql_fetch_row($verif_nom_existe))
	{
		echo htmlentities("Ce nom de Légion existe déjà.");
	}
	else{
if ($nom!='' && ctype_alpha($nom[0]))
{
$description_faction = ltrim(htmlspecialchars(mysql_real_escape_string($_POST['description_faction'])));
if ($description_faction!='')
{
$faction_id = creer_faction($nom, $description_faction, mysql_real_escape_string($_POST['type']), mysql_real_escape_string($_POST['race']));
if($faction_id)
{
if (isset($_POST['nom_chef']) && ltrim($_POST['nom_chef'])!='')
{
add_chief($faction_id, mysql_real_escape_string($_POST['nom_chef']));
}
for($inc=1; $inc<=9; $inc++)
  {
	if (isset($_POST['membre'.$inc]) && ltrim($_POST['membre'.$inc])!='')
		{
		add_mem($faction_id, ltrim(mysql_real_escape_string($_POST['membre'.$inc])));
		}
	}
if ($_POST['type']==3)
{
	race_faction($faction_id, 2);
}
echo "<div align='center'>Légion ".$_POST['nom']." cr&eacute;&eacute;e avec succ&egrave;s<br/><div>";
}
else "Echec lors de la cr&eacute;ation de la Légion";
}
else echo "Vous n'avez pas indiqu&eacute; de description";
}
else echo "Vous n'avez pas indiqu&eacute; le nom";
}
}

$utilisateur_id = $_SESSION['utilisateur']['id'];
// Recherche de l'id du perso animateur
$anim_race_id	= $_SESSION['persos']['anim']['race'];
if($admin)//admin
	$sql_id	= "
SELECT races.nom AS nom
	FROM races
WHERE races.grade_id = 0";

else //anim
	$sql_id = "
SELECT races.nom AS nom
	FROM races
WHERE races.race_id = $anim_race_id AND races.grade_id = 0";


$res_id		= mysql_query ($sql_id) or die (mysql_error().'<br />'.$sql_id);
$anim_ 	= mysql_fetch_array ($res_id);
$anim_race	= $anim_['nom'];

$anim_id	= $_SESSION['persos']['anim']['id'];


if (!isset($_GET['alpha'])){
	$alpha = 'A';
}else{
	$alpha = mysql_real_escape_string($_GET['alpha']);
}
?>
<div align='center' id='contact'>
<?php
// Liste alpha des lettres
for ($i='A';$i!='AA';$i++){
	if($admin)
		$count = "SELECT COUNT(nom) AS nombre FROM factions WHERE nom REGEXP '^".$i."'";
	else
		$count = "SELECT COUNT(nom) AS nombre FROM factions WHERE race REGEXP '$anim_race_id|2' AND nom REGEXP '^".$i."'";
	$resultat = mysql_query ($count) or die (mysql_error());
	$counter = mysql_fetch_array ($resultat);
	if($counter['nombre'])
		echo "<a href='?alpha=$i'>$i (".$counter['nombre'].")</a> | ";
}

if($admin)
	$faction = "SELECT * FROM factions WHERE nom LIKE '".$alpha."%' ORDER BY nom ASC";
else
	$faction = "SELECT * FROM factions WHERE race REGEXP '$anim_race_id|2' AND nom LIKE '".$alpha."%' ORDER BY nom ASC";							

?>
<hr/><form method="post">
<ul>
<?php
	$resultat = mysql_query ($faction) or die (mysql_error());
	while ($faction = mysql_fetch_array ($resultat)){

		echo "<li>Id : ".$faction['id']." Nom : <a href='editer_faction.php?id=".$faction['id']."&perso_id=".$anim_id."'>".$faction['nom']."</a> | 
			  <input type=\"hidden\" name=\"faction_id\" value=\"".$faction['id']."\" />
			  <input type=\"hidden\" name=\"faction_race\" value=\"".$faction['race']."\" />
			  <input type=\"submit\" name=\"Supprimer\" value=\"Supprimer\" /> |</li>";
	}
?>

</ul>
</form>
<hr/>
<p>Entrez directement l'id de la Légion &agrave; voir :</p> 
<form name='option' action="editer_faction.php" method="post">
	<b>Id Légion :</b><br />
  <input name="faction_id" type="text" value='' />
  <input name="perso_id" type="hidden" value='<?php echo $anim_id; ?>' />
  <input type="submit" value="Editer" class="bouton" />
</form>
<hr/>

<p><b>Cr&eacute;er une Légion :</b></p> 

<form method="post">
<table>
  <tr>
  <td>Nom :</td><td><input name="nom" type="text" value='' /></td>
  </tr>
  <tr>
  <td>Description : </td><td><textarea name="description_faction" rows="4" cols="25"/></textarea><br/></td>
  </tr>
  <tr>
  <td>Type de Légion : </td><td><select name="type">
		<option name="Autre" value='0' selected='selected'>Autre</option>
		<option name="Justice" value='1'>Justice</option>
		<option name="Defense" value='2'>D&eacute;fense'</option>
		<option name="Traitre" value='3'>Traitre</option>
		<option name="Loyaliste" value='4'>Loyaliste</option>
		</select></td>
  </tr>
  <tr>
  <td colspan='2'>Les Légions de type "Traitre" seront automatiquement Mauves.</td>
  </tr>
  <tr>
  <td>Race : </td><td><select name="race">
<option name="race"value='<?php echo $anim_race_id; ?>' selected='selected'><?php echo $anim_race;?></option>
<option name="race"value='2'>Mauve</option> 
</select>
</td>
  </tr>
  <tr>
  <td colspan='2'><br/></td>
  </tr>
  <tr>
  <td colspan='2'><i>Si une ou plusieurs des personnes suivantes ont d&eacute;j&agrave; une Légion, elles ne seront pas d&eacute;blasonn&eacute;es</i></td>
  </tr>
  </tr>
  <tr>
  <td>Id/Nom du chef de Légion : </td><td><input name="nom_chef" type="text" value='' /></td>
  </tr>
  <?php
  for ($inc=1; $inc<=9; $inc++)
  {
  echo "</tr>
  <tr>
  <td>Id/Nom membre : </td><td><input name='membre".$inc."' type='text' value='' /></td>
  </tr>";
  }
  
  ?>
  <tr><td align="center" colspan='2'><input type="submit" name="creation" value="Cr&eacute;er" class="bouton" /></td></tr>
</table>
</form>
</div>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
