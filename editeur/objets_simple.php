<p>-- Objets --</p>
<?php
$cats = "SELECT*FROM categorie_objet_simple";																									
$resultat = mysql_query ($cats) or die (mysql_error());
$i = 0;
while ($cat = mysql_fetch_array ($resultat)){
	echo "<img src='../images/editeur/forward.png' /> <a href='#' onClick=\"$('#".$cat['nom'].$i."').toggle();\">".$cat['nom']."</a>";
	echo "<ul>";
	echo "<div id='".$cat['nom'].$i."' style='display:none'>";
	$decors = "SELECT*FROM case_objet_simple WHERE categorie_id = ".$cat['id']."";																									
	$resultats = mysql_query ($decors) or die (mysql_error());
	while ($decor = mysql_fetch_array ($resultats)){
		?>
			<li><a onClick="objets('<?php echo $i; ?>','<?php echo $decor['pv_max']; ?>','<?php echo $decor['id']; ?>')"><img id="objet_img_<?php echo $i; ?>" src="../images/<?php echo $decor['image']; ?>" /></a> <span id="objet_name_<?php echo $i; ?>"><?php echo $decor['nom']; ?></span></li>
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

