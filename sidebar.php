<div class='side_txt'>

<p><span onclick="afficherlayer('menu_liste');" class='side_lien underline curspointer' title='Menu'>Menu de Ewo</span></p>
<p><a class='side_lien' href='http://www.ewo-le-monde.com/inscription/' title='Inscription sur EWO'>S'inscrire sur Ewo !</a></p>
<p><a class='side_lien' href='http://www.ewo-le-monde.com/background/' title='Présentation du jeu'>Présentation d'Ewo ?</a></p>
<p></p>
<p><a class='side_lien' href='http://wiki.ewo-le-monde.com/doku.php'>Guide du jeu</a></p>
<p></p>
<p><a class='side_lien' href='http://blog.ewo-le-monde.com'>Blog d'Ewo</a></p>
<hr class='escargot_hr'>
Nombre d'inscrits :
	<?php
		echo statistique_joueur_inscrit();
	?>
	<p>Nombre de Personnages:</p>
	<?php
		echo " - Anges : ".statistique_perso_inscrit(3)."<br />";
		echo " - Démons : ".statistique_perso_inscrit(4)."<br />";
		echo " - Humains : ".statistique_perso_inscrit(1)."";
		echo "<p>Personnages sur Althian (plans non comptés) : </p>";
		echo " - Anges vivants : ".statistique_persos_vivant(3)."<br />";
		echo " - Démons vivants : ".statistique_persos_vivant(4)."<br />";
		echo " - Humains vivants : ".statistique_persos_vivant(1)."<br />";
	?>
</div>
