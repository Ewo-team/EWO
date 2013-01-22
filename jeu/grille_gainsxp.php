<?php

/*
 * Activation
 */
	// Activations simle
	$gain['activation']['val'] = 1;
	
	// Activation G4
	$gain['activation_g4']['val'] = 3;
	
	// Activation G5
	$gain['activation_g5']['val'] = 6;	

	//Activation dans un autre plan
	$gain['activation_plan']['param'] = 'plan';
	$gain['activation_plan']['val'] = 7;
	$gain['activation_plan']['plan'] = 5;

/*
 * Esquive de la cible
 */
	// Esquive d'attaque
	$gain['esquiver_frappe']['param'] = 'esquiver';
	$gain['esquiver_frappe']['val'] = 0;
	$gain['esquiver_frappe']['esquiver'] = 2;
	$gain['esquiver_frappe']['cap_min'] = 1;
	//ok
	
	// Esquive de sort ciblé
	$gain['esquiver_sort_unique']['param'] = 'esquiver';
	$gain['esquiver_sort_unique']['val'] = 0;
	$gain['esquiver_sort_unique']['esquiver'] = 2;
	$gain['esquiver_sort_unique']['cap_min'] = 1;	
	//ok
	
	// Esquive de sort de zone
	$gain['esquiver_sort_zone']['param'] = 'esquiver';
	$gain['esquiver_sort_zone']['val'] = 0;
	$gain['esquiver_sort_zone']['esquiver'] = 0;
	$gain['esquiver_sort_zone']['cap_min'] = 0;	
	//ok	

	
/*
 * Technomagie
 */
	// Sort technomagique raté
	$gain['sort_rate']['val'] = 3;
	$gain['sort_rate']['modulation_pa'] = true;

	// Sort ciblé réussi (T3)
	$gain['sort_unique_t3']['param'] = 'frappe_sort';
	$gain['sort_unique_t3']['val'] = 7;
	$gain['sort_unique_t3']['frappe_sort'] = -1;
	$gain['sort_unique_t3']['cap_min'] = 4;	
	$gain['sort_unique_t3']['modulation_pa'] = true;	
	
	// Sort ciblé réussi (T4)
	$gain['sort_unique_t4']['param'] = 'frappe_sort';
	$gain['sort_unique_t4']['val'] = 7;
	$gain['sort_unique_t4']['frappe_sort'] = -1;
	$gain['sort_unique_t4']['cap_min'] = 4;		
	$gain['sort_unique_t4']['modulation_pa'] = true;		
	
	// Sort ciblé esquive (T3)
	$gain['sort_unique_esquive_t3']['param'] = 'frappe_sort';
	$gain['sort_unique_esquive_t3']['val'] = -2;
	$gain['sort_unique_esquive_t3']['cap_min'] = 1;	
	$gain['sort_unique_esquive_t3']['modulation_pa'] = true;	
	
	// Sort ciblé esquive (T4)
	$gain['sort_unique_esquive_t4']['param'] = 'frappe_sort';
	$gain['sort_unique_esquive_t4']['val'] = -2;
	$gain['sort_unique_esquive_t4']['cap_min'] = 3;			
	$gain['sort_unique_esquive_t4']['modulation_pa'] = true;			
		
	// Sort de zone réussi (T3)
	$gain['sort_zone_t3']['param'] = 'frappe_sort';
	$gain['sort_zone_t3']['val'] = 7;
	$gain['sort_zone_t3']['frappe_sort'] = 1;
	$gain['sort_zone_t3']['cap_min'] = 5;	
	$gain['sort_zone_t3']['modulation_pa'] = true;
	
	// Sort de zone réussi (T4)
	$gain['sort_zone_t4']['param'] = 'frappe_sort';
	$gain['sort_zone_t4']['val'] = 7;
	$gain['sort_zone_t4']['frappe_sort'] = 1;
	$gain['sort_zone_t4']['cap_min'] = 6;		
	$gain['sort_zone_t4']['modulation_pa'] = true;	
		
	// Sort sur sois-même réussi
	$gain['sort_surlanceur_reussi']['val'] = 4;
	$gain['sort_surlanceur_reussi']['modulation_pa'] = true;
	
	// Sort sur sois-même esquivé
	$gain['sort_surlanceur_esquive']['val'] = 2;	
	$gain['sort_surlanceur_esquive']['modulation_pa'] = true;	
		
/*
 * Attaque
 */
	// Attaque d'un T1
	$gain['attaque']['param'] = 'attaque';
	$gain['attaque']['val'] = 7;
	$gain['attaque']['attaque'] = -1;
	$gain['attaque']['cap_min'] = 2;		
	$gain['attaque']['modulation_pa'] = true;		
	
	// Attaque esquivée
	$gain['attaque_esquive']['param'] = 'attaque';
	$gain['attaque_esquive']['val'] = 3;
	$gain['attaque_esquive']['attaque'] = -1;	
	$gain['attaque_esquive']['modulation_pa'] = true;
	
	// Attaque esquivée d'un T4 sur un T1
	$gain['attaque_esquive_t4']['param'] = 'attaque';
	$gain['attaque_esquive_t4']['val'] = 4;
	$gain['attaque_esquive_t4']['attaque'] = -1;	
	$gain['attaque_esquive_t4']['modulation_pa'] = true;
	
	// Attaquer sa famille
	$gain['frappe_famille']['param'] = 'attaque';
	$gain['frappe_famille']['val'] = 5;
	$gain['frappe_famille']['attaque'] = -1;		
	
	// Attaque recu
	$gain['attaque_recu']['param'] = 'attaque';
	$gain['attaque_recu']['val'] = 1;
	$gain['attaque_recu']['attaque'] = 1;	
	$gain['attaque_recu']['modulation_pa'] = false;
	$gain['attaque_recu']['cap_min'] = 1;	
	
	$gain['attaque_recu_g4']['val'] = 2;
	$gain['attaque_recu_g4']['attaque'] = 1;	
	$gain['attaque_recu_g4']['modulation_pa'] = false;
	$gain['attaque_recu_g4']['cap_min'] = 2;	
	
	$gain['attaque_recu_g5']['val'] = 3;
	$gain['attaque_recu_g5']['attaque'] = 1;	
	$gain['attaque_recu_g5']['modulation_pa'] = false;
	$gain['attaque_recu_g5']['cap_min'] = 3;	

/*
 * Plans ailés
 */
	$gain['attaque_plan_allie']['param'] = 'attaque';
	$gain['attaque_plan_allie']['val'] = 6;
	$gain['attaque_plan_allie']['attaque'] = -1;
	$gain['attaque_plan_allie']['modulation_pa'] = true;
	
	$gain['magie_plan_allie']['val'] = 5;
	$gain['magie_plan_allie']['modulation_pa'] = true;
/*
 * Kill
 */ 
	// Tuer T1 par frappe avec T1
	$gain['tueur_t3_tue_t3']['param'] = 'attaque';
	$gain['tueur_t3_tue_t3']['val'] = 20;
	$gain['tueur_t3_tue_t3']['attaque'] = 5;	

	// Tuer T4 par frappe avec T1
	$gain['tueur_t3_tue_t4']['param'] = 'attaque';
	$gain['tueur_t3_tue_t4']['val'] = 15;
	$gain['tueur_t3_tue_t4']['attaque'] = 5;	

	// Tuer T3 par frappe avec T4
	$gain['tueur_t4_tue_t4']['param'] = 'attaque';
	$gain['tueur_t4_tue_t4']['val'] = 20;
	$gain['tueur_t4_tue_t4']['attaque'] = 5;	

	// Tuer T3 par frappe avec T4
	$gain['tueur_t4_tue_t3']['param'] = 'attaque';
	$gain['tueur_t4_tue_t3']['val'] = 25;
	$gain['tueur_t4_tue_t3']['attaque'] = 5;		
	
	// Tuer par un sort
	$gain['tueur_sort']['param'] = 'attaque';
	$gain['tueur_sort']['val'] = 5;
	$gain['tueur_sort']['attaque'] = 5;	
	$gain['tueur_sort']['cap_max'] = 20;	
	
	// Tuer sa famille
	$gain['tueur_famille']['param'] = 'attaque';
	$gain['tueur_famille']['val'] = 5;
	$gain['tueur_famille']['attaque'] = -1;	
	
	// Tuer un tricheur
	$gain['tueur_cafard']['val'] = 5;
	
/*
 * Perte/gain killé
 */ 
	// T3 tué
	$gain['tue_t3']['param'] = 'kill';
	$gain['tue_t3']['val'] = -25;
	$gain['tue_t3']['kill'] = 5;	
	$gain['tue_t3']['cap_min'] = false;
	
	// T4 tué
	$gain['tue_t4']['param'] = 'kill';
	$gain['tue_t4']['val'] = -15;
	$gain['tue_t4']['kill'] = 5;	
	$gain['tue_t4']['cap_min'] = false;

	
/*
 * Entrainement
 */ 
	// Entrainement à deux
	$gain['entrainement_defenseur']['val'] = 2;
	$gain['entrainement_defenseur']['modulation_pa'] = false;
	//Ok
	
	$gain['entrainement_attaquant']['val'] = 4;
	$gain['entrainement_attaquant']['modulation_pa'] = true;
	//OK
	
	// Auto-entrainement
	$gain['entrainement_solo']['val'] = 4;
	$gain['entrainement_solo']['modulation_pa'] = true;
	//Ok
	
	// Entrainement famille
	$gain['entrainement_famille_attaquant']['val'] = 2.5; // pour un T4, 2.5 * 1.5 = 3.75, donc 4xp les 3/4 du temps
	$gain['entrainement_famille_attaquant']['modulation_pa'] = true;
	
/*
 * Réparation
 */ 
	// Réparation d'un batiment
	$gain['repare_batiment']['param'] = 'fullpv';
// Bloque le gain à 1... ?
	$gain['repare_batiment']['borne_min'] = 5;
	$gain['repare_batiment']['borne_max'] = 7;
	$gain['repare_batiment']['fullpv'] = 0; 
	$gain['repare_batiment']['modulation_pa'] = true;
//	$gain['repare_batiment']['cap_min'] = 0;
// Le cap fait chier, c'est lui qui est retenu.
// Full pv ou pas la réparation pex quand même. Voire ça pex encore plus (?).
// jeu/actions.php ligne 29 ??
// jeu/fonctions.php ligne 757 ??
	
/*
 * Destruction
 */ 
	$gain['destruction_t1']['val'] = 10;
	$gain['destruction_t2']['val'] = 25;
	$gain['destruction_t3']['val'] = 50;
	$gain['destruction_t4']['val'] = 100;
	
	$gain['destruction_porte_aile']['val'] = 100;	
	$gain['destruction_porte_mauve']['val'] = 50;	
	
	 // Detruit un objet quelconque
	$gain['destruction_objet']['borne_min'] = 4;
	$gain['destruction_objet']['borne_max'] = 6;
	
/*
 * Frappe objets
 */ 
	// Frappe sur une porte (toc toc toc)
	$gain['frappe_porte']['borne_min'] = 5;
	$gain['frappe_porte']['borne_max'] = 7;
	$gain['frappe_porte']['modulation_pa'] = true;
	
	// Frappe sur un bouclier
	$gain['frappe_bouclier']['borne_min'] = 5;
	$gain['frappe_bouclier']['borne_max'] = 7;	
	$gain['frappe_bouclier']['modulation_pa'] = true;	
	
	// Frappe sur un objet
	$gain['frappe_objet']['borne_min'] = 4;
	$gain['frappe_objet']['borne_max'] = 6;	
	$gain['frappe_objet']['modulation_pa'] = true;	
	
/*
 * Autres gains
 */ 
	
	// Suicide réussi
	$gain['suicide']['param'] = 'attaque';
	$gain['suicide']['val'] = -25;
	$gain['suicide']['attaque'] = -10;		
	$gain['suicide']['cap_min'] = false;
 
	// Sprint
	$gain['sprint']['borne_min'] = 4;
	$gain['sprint']['borne_max'] = 5;

	// retour de vacance
	$gain['vacance']['val'] = 7;
	$gain['vacance']['param'] = 'vacance'; 
	
	// désaffiliation
	$gain['desaffiliation']['param'] = 'attaque';
	$gain['desaffiliation']['val'] = -25;
	$gain['desaffiliation']['attaque'] = -10;		
	
	// Triche
	$gain['triche']['val'] = 0;
	
	// CAP minimum par défaut
	$gain['cap_min']['val'] = 1;	


