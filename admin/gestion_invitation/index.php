<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
?>
<div align='center' id='contact'>
<h2>Gestion des Invitations</i></h2>
<p>[ <a href=''>Retour</a> ]</p>

<form name='invitations' enctype="multipart/form-data" action="ajout_invitation.php" method="POST">
<table>
<tr>
	<td>
		Nombre d'invitations à créer : <input type='text' size='2' name='nb_num' />
	</td>
</tr>
<tr>
	<td  colspan="2">
		<input type="submit" value="Ajouter" class="bouton" />
	</td>
</tr>
</table>
</form>

<p><hr width='60%' /></p>

<p><b>Invitations</b></p>

<form name='num' action='checked.php' method='post'>
<table>
<?php

	$invitations = "SELECT*FROM invitations ORDER BY distribue";							
	$result = mysql_query ($invitations) or die (mysql_error());
	$n=1;
	while ($invite = mysql_fetch_array ($result)){
		if ($invite['distribue'] == 1){$color = '#95EFA1';}else{$color = '';}
		echo "<tr style='background-color:".$color."'><td> $n </td><td>".$invite['numero']."</td> <td> Créé le ".$invite['date']."</td> <td><input name='id[]' type='checkbox' value='".$invite['id']."' /></td></tr>";
		$n++;
	}

?>
<tr>
<td colspan='4' align='center'><input type='submit' value='Marquer comme utilisé' class='bouton' /></td>
</tr>
</table>
</form>
</div>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
