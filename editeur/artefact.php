<p>-- Artefacts --</p>
<?php
$cats = "SELECT*FROM categorie_artefact";																									
$resultat = mysql_query ($cats) or die (mysql_error());
$i = 0;
while ($cat = mysql_fetch_array ($resultat)){
	echo "<img src='../images/editeur/forward.png' /> <a href='#' onClick=\"$('#".$cat['nom'].$i."').toggle();\">".$cat['nom']."</a>";
	echo "<ul>";
	echo "<div id='".$cat['nom'].$i."' style='display:none'>";
	$artes = "SELECT*FROM case_artefact WHERE categorie_id = ".$cat['id']."";																									
	$resultats = mysql_query ($artes) or die (mysql_error());
	while ($arte = mysql_fetch_array ($resultats)){
		?>
			<li><a onmouseover="this.style.cursor='pointer'" onClick="artefacts('<?php echo $i; ?>','<?php echo $arte['pv_max']; ?>','<?php echo $arte['id']; ?>')"><img id="artefact_img_<?php echo $i; ?>" src="../images/<?php echo $arte['image']; ?>" /></a> <span id="artefact_name_<?php echo $i; ?>"><?php echo $arte['nom']; ?></span></li>
		<?php
		$i++;
	}
	$i++;
	?>
	</div>
	</ul>
	<?php
}
?>
