<?php

namespace persos\eventManager;

class SPECIAL_EVENT {
	public static $TEXT = array(
		-2 => 'est mort en randonnée</b>',
		-1 => 'devrait regarder ou il/elle met les pieds</b>',
		1 => 'a pris un bain de lave</b>',
		2 => 'est mort(e) dans la lave</b>',
		3 => 'aime poser son pied dans le vide</b>',
		4 => 'a eu le temps de dire "Mon Dix-Yeux, c\'est plein d\'étoiles" avant de mourir</b>'
	);
	
	public static $INDEX = array(
		'degats_generique' => -1,
		'mort_generique' => -2,
		'lave' => 1,
		'mort_lave' => 2,
		'faille' => 3,
		'mort_faille' => 4
	);	
}