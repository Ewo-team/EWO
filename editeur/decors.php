<p>-- Decors --</p>
<?php
$cats = "SELECT*FROM categorie_terrain";																									
$resultat = mysql_query ($cats) or die (mysql_error());
$i = 0;
while ($cat = mysql_fetch_array ($resultat)){
	echo "<img src='../images/editeur/forward.png' /> <a href='#' onClick=\"$('#".$cat['nom'].$i."').toggle();\">".$cat['nom']."</a>";
	echo "<ul>";
	echo "<div id='".$cat['nom'].$i."' style='display:none'>";
	$decors = "SELECT*FROM case_terrain WHERE categorie_id = ".$cat['id']."";																									
	$resultats = mysql_query ($decors) or die (mysql_error());
	while ($decor = mysql_fetch_array ($resultats)){
		?>
			<li><a onClick="decors('<?php echo $i; ?>','<?php echo $decor['couleur']; ?>','<?php echo $decor['mouv']; ?>','<?php echo $decor['id']; ?>')"><img id="img_<?php echo $i; ?>" src="../images/<?php echo $decor['image']; ?>" /></a> <span id="name_<?php echo $i; ?>"><?php echo $decor['nom']; ?></span></li>
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
