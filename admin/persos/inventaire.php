<div class='separation'></div>
<!-- Debut contour -->
<div class="block conteneur">
<div class='draghandle conteneur_titre'>Inventaire</div>
<div id="layer-block-6" style='display:block'>
<!-- conteneur -->
				<?php
				$_SESSION['inventaire']['pos_x'] = $position['pos_x'];
				$_SESSION['inventaire']['pos_y'] = $position['pos_y'];
				$_SESSION['inventaire']['carte_id'] = $plan['id'];
				$_SESSION['inventaire']['perso_id'] = $id;
				
				//Test de la fonction dropPos
				// $echec = 0;
				// for($inc=1;$inc<=1000;$inc++){
				
				// $retour = dropPos ($cenPos);
				// if($retour!=NULL && ($retour['pos_x']!=-1 || $retour['pos_y']!=1)){
					// $echec++;
					// }
				// }
				// echo "Il y a eu $echec echec(s) sur 1000 drop<br/>";
				//fin test
				
				function afficher_bulle_inventaire($nom, $poid, $cout, $pv, $statut, $description, $id, $id_inventaire){
				global $cenPos;					
				echo "<div class='damier_bulle formulaire' id='$id_inventaire-bulle' style='z-index:99999;top:0px;'>
								<div class='bubulle'>
								<img src='../images/damier_vide.png' />
									<div class='infobulle' style='z-index:9999;'>
										<table border='0px' CELLPADDING='0' CELLSPACING='0'>
											<tr>
												<td colspan='3' class='haut_bulle'></td>
											</tr>
											<tr>
												<td class='gauche_bulle'>[<span class='curspointer' ondblclick=\"drop_artefact('".$id_inventaire."');\">Lacher</span>]</td>
												<td class='middle_bulle'><img class='img_bulle' src='../images/damier_vide.png' /></td>
												<td class='droit_bulle'></td>	
											</tr>
											<tr>
												<td colspan='3' class='centre_bulle'>
												<b>Nom : </b> $nom <br />
												<b>Poid : </b> $poid Kg<br />
												<b>Cout : </b> $cout Ewok<br />
												<b>Pv : </b> $pv pv<br />
												<b>Statut : </b> $statut<br />
												<b>Description : </b><br />
												$description 
												</td>
											</tr>	
											<tr>
												<td colspan='3' class='bas_bulle'></td>	
											</tr>
										</table>
									</div>
								</div>
							</div>";
			}
	
					$inventaires = "SELECT I.statut AS stat, A.nom as nom, A.image as img, A.poid as poid, A.cout as cout, A.description AS description , I.statut as statut, A.id as id, I.pv as pv, I.id as id_inventaire 
														FROM inventaire I 
															JOIN case_artefact A 
																ON I.case_artefact_id = A.id 
																	WHERE I.perso_id = '".$perso_id."' ORDER BY A.nom ASC";
					$resultat = mysql_query ($inventaires) or die (mysql_error());
					$p=1;
					$cout = 0;
					$poid = 0;
					echo "<div id='inventaire'>";
					while ($objet = mysql_fetch_array ($resultat)){
						echo "<div style='position:relative;display:inline-block' id='".$objet['id_inventaire']."-artefact' class='invent_icone' >
						<span id='".$objet['id_inventaire']."-artefact-img'><img src='../images/".$objet['img']."' alt='".$objet['nom']."' title='".$objet['nom']."' /></span>
						<span id='".$objet['id_inventaire']."-artefact-bulle'>";
						afficher_bulle_inventaire($objet['nom'], $objet['poid'], $objet['cout'], $objet['pv'], $objet['statut'], $objet['description'], $objet['id'], $objet['id_inventaire']);
						echo "</span></div>";
						$cout += $objet['cout'];
						
					}
					echo "</div>";
					echo "<div class='clear'></div>";
					echo "<p>Valeur totale : <span id='val_total'>".$cout."</span> Pewo</p>";
					echo "<p>Poids transporté : <span id='poid_total'>".poid_courant($perso_id)."</span>/".poid_portable($perso_id)." Kg</p>";
				?>
<!-- fin conteneur -->
</div>
</div>
<!-- Fin contour -->	
<div class='separation'></div>

