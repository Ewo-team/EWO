<?php

function headersvg($titre, $largeur, $hauteur, $fond = 'fond_terre') {

	header("Content-type: image/svg+xml");

	echo '<?xml version="1.0" standalone="no"?>
	<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
	"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
	<svg width="'.$largeur.'" height="'.$hauteur.'" version="1.1"
	xmlns="http://www.w3.org/2000/svg">
	<title>'.$titre.'</title>
    <style type="text/css" >
      <![CDATA[

        .fond_terre {
           fill:   rgb(170, 221, 170);
        }
		
        .humain {
           fill:   rgb(0,200,0);
        }		
		
        .roi {
           fill:   rgb(0,200,0);
        }	

		.paria {
           fill:   rgb(200,0,200);
        }
		
        .aa {
           fill:   rgb(0,0,200);
        }

        .ange {
           fill:   rgb(0,0,200);
        }

        .sd {
           fill:   rgb(200,0,0);
        }

        .demon {
           fill:   rgb(200,0,0);
        }

        .noir {
           fill:   rgb(0,0,0);
        }
		
        .axe {
           fill:   rgb(0,0,0);
        }		

        .bouclier {
           fill:   rgb(0,100,100);
        }		

      ]]>
    </style>	
	';
	rectangle(0,0,$hauteur,$largeur,$fond, false);
}

function footersvg() {
	echo '</svg>';
}

function rectangle($x, $y, $hauteur, $largeur, $classe = null, $centre = false, $arrondi = 0) {
	$pos_x = $x - ($largeur / 2);
	$pos_y = $y - ($hauteur / 2);
	if($centre) {
		echo '<rect class="'.$classe.'" x="'.$pos_x.'" y="'.$pos_y.'" width="'.$largeur.'" height="'.$hauteur.'" rx="'.$arrondi.'" />';
	} else {
		echo '<rect class="'.$classe.'" x="'.$x.'" y="'.$y.'" width="'.$largeur.'" height="'.$hauteur.'" rx="'.$arrondi.'" />';
	}
}

function carre($x, $y, $taille, $classe = null, $centre = false, $arrondi = 0) {
	rectangle($x, $y, $taille, $taille, $classe, $centre);
}

function ellipse($x, $y, $hauteur, $largeur, $classe = null) {
	$rx = $largeur / 2;
	$ry = $hauteur / 2;
	echo '<ellipse class="'.$classe.'" cx="'.$x.'" cy="'.$y.'" rx="'.$rx.'" ry="'.$ry.'" />';
}

function cercle($x, $y, $rayon, $classe = null) {
	$r = $rayon / 2;
	echo '<circle class="'.$classe.'" cx="'.$x.'" cy="'.$y.'" r="'.$r.'" />';
}

function ligne($x_depart, $y_depart, $x_arrive, $y_arrive, $classe = null) {
	echo '<line class="'.$classe.'" x1="'.$x_depart.'" y1="'.$y_depart.'" x2="'.$x_arrive.'" y2="'.$y_arrive.'" />';
}

function polygone_ouvert($tableau, $classe = null) {
	$points = implode(",",$tableau);
	echo '<polyline class="'.$classe.'" points="'.$points.'" />';
}

function polygone($tableau, $classe = null) {
	$points = implode(",",$tableau);
	echo '<polygon class="'.$classe.'" points="'.$points.'" />';
}

function bouclier($x,$y,$taille,$nom) {
	carre($x,$y,$taille,'bouclier');
}

function AxeHorizontale($position, $longueur, $texte) {
	//echo '<line class="axe" x1="0" y1="'.$position.'" x2="'.$longueur.'" y2="'.$position.'" />';
	//echo '<rect class="axe" x="0" y="'.$position.'" x2="'.$longueur.'" y2="'.$position.'" />';
	rectangle(0,$position, 1, $longueur, 'axe');
}

function AxeVerticale($position, $hauteur, $texte) {
	//echo '<line class="axe" x1="'.$position.'" y1="0" x2="'.$position.'" y2="'.$hauteur.'" />';
	rectangle($position, 0, $hauteur, 1, 'axe');
}

?>