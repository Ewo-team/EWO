<div class='side_txt'><p><b>Le dernier flash info:</b><br/>&nbsp;&nbsp;
<a class='side_lien' href='./?world'>Voir</a></p>
<p style="margin-top: 30px;"><b>Vos personnages:</b><ul style="list-style-type:none;padding-left:15px;">
<?php
if(isset($_SESSION['persos']['nom'])){
	foreach($_SESSION['persos']['nom'] as $index => $perso){
		echo "<li><a class='side_lien' href='./?id=".$_SESSION['persos']['id'][$index]."'>".$perso."</a></li>";
	}
}
?></ul></div>
