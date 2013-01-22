<?php

/*
	***********************
	artefact.php
	***********************
		"SELECT*FROM categorie_artefact"
		"SELECT*FROM case_artefact WHERE categorie_id = ".$cat['id'].""
	
	***********************
	bdd_artefact.php
	***********************
		"SELECT icone_artefact_id FROM damier_artefact WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'"
		"UPDATE damier_artefact SET icone_artefact_id = '".$_GET['id']."', pos_x = '".$_GET['x']."', pos_y = '".$_GET['y']."', 
			pv = '".$_GET['divers']."', carte_id = '".$_GET['carte_id']."' WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' 
			AND carte_id = '".$_GET['carte_id']."'"
		"INSERT INTO damier_artefact (id, icone_artefact_id, pos_x, pos_y, pv, carte_id) 
			VALUE ('',".$_GET['id'].",".$_GET['x'].",".$_GET['y'].",".$_GET['divers'].",".$_GET['carte_id'].")"
		"DELETE FROM damier_artefact WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'"
		
	***********************
	bdd_bouclier.php
	***********************
		"SELECT id FROM case_objet_complexe WHERE images='decors/objets_complexe/DBouclier_".$_POST['type_id']."'"
		"INSERT INTO damier_objet_complexe (id, case_objet_complexe_id, pos_x, pos_x_max, pos_y, pos_y_max, pv, carte_id) 
			VALUE ('', '".$obj_id."', '".$x_min."', '".$x_max."', '".$y_min."', '".$y_max."', '-1', '".$_POST['carte_id']."')"
		"INSERT INTO damier_bouclier (id, nom, nom_image, description, pos_x, pos_y, type_id, objet_lie, carte_id, pv, pv_max, deplacer, statut) 
			VALUE ('', '".$nom."', '".$img."', '".$description."', '".$_POST['posX']."', '".$_POST['posY']."', '".$_POST['type_id']."',  
			'".$id_DB."', '".$_POST['carte_id']."', '".$_POST['pv_max']."', '".$_POST['pv_max']."', '".$_POST['deplacer']."', '".$_POST['statut']."')"

	***********************
	bdd_carte.php
	***********************		
		"INSERT INTO cartes (id, nom, description, circ, infini, x_min,	y_min, x_max, y_max, visible_x_min, visible_x_max, visible_y_min, visible_y_max) 
			VALUE ('', 
				'".$nom."', 
				'".$description."', 
				'".$_POST['circ']."', 
				'".$_POST['infini']."', 				
				'".$_POST['x_min']."', 				
				'".$_POST['y_min']."', 				
				'".$_POST['x_max']."', 				
				'".$_POST['y_max']."', 				
				'".$_POST['visible_x_min']."', 				
				'".$_POST['visible_x_max']."', 				
				'".$_POST['visible_y_min']."', 
				'".$_POST['visible_y_max']."')"
	
	***********************
	bdd_categorie.php
	***********************		
		"INSERT INTO ".$statut." (id, nom, description) VALUE ('', '".$nom."', '".$description."')"

	***********************
	bdd_decor.php
	***********************		
		"SELECT terrain_id FROM damier_terrain WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'"
		"UPDATE damier_terrain SET carte_id = '".$_GET['carte_id']."', terrain_id = '".$_GET['id']."', pos_x = '".$_GET['x']."', pos_y = '".$_GET['y']."' 
			WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'"
		"INSERT INTO damier_terrain (id, carte_id, terrain_id, pos_x, pos_y) VALUE ('','".$_GET['carte_id']."','".$_GET['id']."','".$_GET['x']."','".$_GET['y']."')"
		"DELETE FROM damier_terrain WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'"

	***********************
	bdd_lock.php
	***********************
		"UPDATE damier_".$_GET['type']." SET statut = '1' WHERE id = '".$_GET['id']."'"
		"UPDATE damier_".$_GET['type']." SET statut = '0' WHERE id = '".$_GET['id']."'"
		"SELECT objet_lie FROM damier_".$_GET['type']." WHERE id=".$_GET['id']
	
	***********************
	bdd_objet.php
	***********************
		"SELECT case_objet_simple_id FROM damier_objet_simple WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'"
		"UPDATE damier_objet_simple SET case_objet_simple_id = '".$_GET['id']."', pos_x = '".$_GET['x']."', pos_y = '".$_GET['y']."', pv = '".$_GET['divers']."', 
			carte_id = '".$_GET['carte_id']."' WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'"
		"INSERT INTO damier_objet_simple (id, case_objet_simple_id, pos_x, pos_y, pv, carte_id) VALUE ('','".$_GET['id']."','".$_GET['x']."','".$_GET['y']."','".$_GET['divers']."','".$_GET['carte_id']."')"
		"DELETE FROM damier_objet_simple WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'"

	***********************
	bdd_objet_artefact.php
	***********************
		"INSERT INTO case_artefact (id, nom, description, image, pv_max, rarete, cout, poid, categorie_id, consom) VALUE (
			'', 
			'".$nom."', 
			'".$description."', 
			'".$_POST['image']."', 
			'".$_POST['pv_max']."', 				
			'".$_POST['rarete']."', 
			'".$_POST['cout']."', 
			'".$_POST['poid']."', 
			'".$_POST['categorie_id']."'
			'".$_POST['consom']."')"
		"INSERT INTO caracs_alter_artefact (case_artefact_id, alter_pa, alter_mouv,	alter_def, alter_att, alter_recup_pv, alter_force, alter_perception) VALUE ('".$id_artefact."',0,0,0,0,0,0,0)"

	***********************
	bdd_objet_complexe.php
	***********************		
		"INSERT INTO case_objet_complexe (id, nom, description, pv_max,	bloquant, reparable, images, taille_x, taille_y, categorie_id) VALUE (
			'', 
			'".$nom."', 
			'".$description."', 
			'".$_POST['pv_max']."', 
			'".$_POST['bloquant']."', 
			'".$_POST['reparable']."', 
			'".$_POST['image']."', 
			'".$taillex."', 
			'".$tailley."', 
			'".$_POST['categorie_id']."')"
	
	***********************
	bdd_objet_simple.php
	***********************		
		"INSERT INTO case_objet_simple (id, nom, description, bloquant, pv_max, poid, image, categorie_id) VALUE (
			'', 
			'".$nom."', 
			'".$description."', 
			'".$_POST['bloquant']."', 				
			'".$_POST['pv_max']."', 
			'".$_POST['poid']."', 
			'".$_POST['image']."', 
			'".$_POST['categorie_id']."')"

	***********************
	bdd_porte.php
	***********************		
		"SELECT id FROM case_objet_complexe WHERE images='decors/objets_complexe/D".$_POST['image_porte']."'"
		"INSERT INTO damier_objet_complexe (id, case_objet_complexe_id, pos_x, pos_x_max, pos_y, pos_y_max, pv, carte_id) 
			VALUE ('', '".$obj_id."', '".$x_min."', '".$x_max."', '".$y_min."', '".$y_max."', '-1', '".$_POST['carte_id']."')"
		"INSERT INTO damier_porte (id, nom, nom_image, description, pos_x, pos_y,porte_liee_id, objet_lie, spawn_id, carte_id, pv, pv_max, statut) VALUE ('', '".$nom."', '".$_POST['image_porte']."', '".$description."', '".$_POST['posX']."', 
			'".$_POST['posY']."','".$_POST['porte_liee']."', '$id_DB', '".$_POST['spawn_id']."', '".$_POST['carte_id']."', '".$_POST['pv_max']."', '".$_POST['pv_max']."',  '".$_POST['statut']."')"
		"UPDATE damier_porte SET porte_liee_id='$id_DB' WHERE id ='".$_POST['porte_liee']."'"

	***********************
	bdd_poser_objet_complexe.php
	***********************
		"SELECT nom, taille_x, taille_y, pv_max FROM case_objet_complexe WHERE id=".$_POST['objet_id'].""
		"INSERT INTO damier_objet_complexe (id, case_objet_complexe_id, pos_x, pos_x_max, pos_y, pos_y_max, pv, carte_id) 
			VALUE ('', '".$_POST['objet_id']."', '".$_POST['pos_x']."', '".$pos_x_max."', '".$_POST['pos_y']."', '".$pos_y_max."', 
			'".$pv."', '".$_POST['carte_id']."')"
	
	***********************
	bdd_spawn.php
	***********************
		"INSERT INTO damier_spawn (id, nom, description, pos_x, pos_y, pos_max_x, pos_max_y, carte_id) VALUE ('', '".$nom."', '".$description."', '".$_POST['posX']."', '".$_POST['posY']."', '".$_POST['posmaxX']."', '".$_POST['posmaxY']."', '".$_POST['carte_id']."')"

	***********************
	bdd_terrain.php
	***********************
		"INSERT INTO case_terrain (id, nom, image, couleur, mouv, categorie_id) VALUE ('', '".$nom."', '".$_POST['image']."', '".$_POST['couleur']."', '".$_POST['mouv']."', '".$_POST['categorie_id']."')"

	***********************
	bdd_update.php
	***********************		
		"UPDATE ".$_GET['table']." SET ".$_GET['champ']." = '".$txt."' WHERE id = '".$_GET['where']."'"
	
	***********************
	boulier.php
	***********************		
		"SELECT*FROM damier_bouclier"

	***********************
	carte.php
	***********************		
		"SELECT*FROM cartes"

	***********************
	categorie.php
	***********************
		"SELECT*FROM categorie_terrain"
		"SELECT*FROM categorie_objet_simple"
		"SELECT*FROM categorie_objet_complexe"
		"SELECT*FROM categorie_artefact"
	
	***********************
	damier.php
	***********************
		"SELECT * FROM cartes WHERE id='$carte_pos'"

	***********************
	decors.php
	***********************
		"SELECT*FROM categorie_terrain"
		"SELECT*FROM case_terrain WHERE categorie_id = ".$cat['id'].""

	***********************
	fonctions.php
	***********************		
		"SELECT id, nom, description FROM cartes"
		"SELECT * FROM damier_spawn"
		"SELECT id, nom, description FROM categorie_".$categorie.""
		"SELECT nom FROM categorie_".$categorie." WHERE id=".$id.""
		"SELECT nom, pos_x, pos_y FROM damier_spawn WHERE id=".$id.""
		"SELECT nom FROM case_objet_complexe WHERE id=".$id.""
		"SELECT id, nom, description FROM case_objet_complexe"
	
	***********************
	index.php
	***********************		


	***********************
	infos_damier_editeur.php
	***********************		
		"SELECT * FROM damier_terrain WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'"
		"SELECT * FROM case_terrain WHERE id=$id"
		"SELECT * FROM damier_artefact WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'"
		"SELECT * FROM case_artefact WHERE id=$id"
		"SELECT * FROM damier_objet_complexe WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'"
		"SELECT * FROM case_objet_complexe WHERE id=$id"
		"SELECT * FROM damier_objet_simple WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'"
		"SELECT * FROM case_objet_simple WHERE id=$id"
		"SELECT * FROM damier_porte WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'"
		"SELECT * FROM damier_bouclier WHERE $rchch_x AND $rchch_y AND carte_id='$carte_pos'"

	***********************
	jointure.php
	***********************
		"SELECT*FROM categorie_objet_simple"
		"SELECT*FROM categorie_objet_complexe"
		
	
	***********************
	menu.php
	***********************


	***********************
	objet.php
	***********************
		"SELECT*FROM case_objet_simple"

	***********************
	objet_artefact.php
	***********************		
		"SELECT*FROM case_artefact"
		"SELECT*FROM caracs_alter_artefact WHERE case_artefact_id = '".$porte['id']."'"
	
	***********************
	objet_complexe.php
	***********************		
		"SELECT*FROM case_objet_complexe WHERE nom!='Abysses'"

	***********************
	objet_decor.php
	***********************		
		"SELECT*FROM case_terrain"

	***********************
	objets_simple.php
	***********************
		"SELECT*FROM categorie_objet_simple"
		"SELECT*FROM case_objet_simple WHERE categorie_id = ".$cat['id'].""
	
	***********************
	outil.php
	***********************


	***********************
	porte.php
	***********************
		"SELECT*FROM damier_porte"

	***********************
	poser_objet_complexe.php
	***********************		
		"SELECT*FROM damier_objet_complexe"
	
	***********************
	spawn.php
	***********************		
		"SELECT*FROM damier_spawn"

	***********************
	tempon.php
	***********************		


	

*/

?>	