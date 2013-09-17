<?php
namespace jeu\carte;

use \conf\ConnecteurDAO as ConnecteurDAO;

class Carte {

	private $_id;
	private $_nom;
	private $_date;
	
	public $conn;

	private $_x_min;
	private $_x_max;
	private $_y_min;
	private $_y_max;
	
	private $_ratio_hor;
	private $_ratio_ver;
	private $_taille_hor;
	private $_taille_ver;
	
	private $_persos = array();
	private $_boucliers = array();
	private $_portes = array();
	private $_viseurs = array();
	
	private $_popup = array();
	
	private $_couleurs = array();
		
	public function __construct($id, ConnecteurDAO $conn, $ratio_horizontale, $ratio_verticale = null) {
		if(!isset($ratio_verticale)) {
			$ratio_verticale = $ratio_horizontale;
		}
		
		$info = $conn->SelectInfosCarte($id);
		$this->conn = $conn;
		
		$this->_x_min = $info['visible_x_min'];
		$this->_x_max = $info['visible_x_max'];
		$this->_y_min = $info['visible_y_min'];
		$this->_y_max = $info['visible_y_max'];		
		
		$this->_ratio_hor = $ratio_horizontale;
		$this->_ratio_ver = $ratio_verticale;
		
		$this->_taille_hor = ($this->_x_max - $this->_x_min) * $this->_ratio_hor;
		$this->_taille_ver = ($this->_y_max - $this->_y_min) * $this->_ratio_ver;
		
		$this->_id = $id;	
		$this->_nom = $info['nom'];	
		$this->_date = time();	
		
		$this->_couleurs['base'] = 'noir';
		$this->_couleurs['races'][1] = 'humain';
		$this->_couleurs['races'][2] = 'paria';
		$this->_couleurs['races'][3] = 'ange';
		$this->_couleurs['races'][4] = 'demon';		
		
	}
	
	public function Fond($fond = 'fond_terre', $img = null) {
		if(isset($img)) {
			return SVG::image(0,0,$this->_taille_ver ,$this->_taille_hor , $img);
		}
		return SVG::rectangle(0,0,$this->_taille_ver ,$this->_taille_hor , array('class' => $fond));	
	}
	
	public function Header() {
		header("Content-type: image/svg+xml");
	}
	
	public function Start() {
		return SVG::Header($this->_nom, $this->_taille_hor, $this->_taille_ver, $this->_date);
	}
	
	public function Footer() {
		return SVG::Footer();
	}
	
	public function coord_x($x) {
		return (($x-1 - $this->_x_min))*$this->_ratio_hor;
	}
	
	public function coord_y($y) {
		return (($this->_y_max - $this->_y_min)-($y - $this->_y_min))*$this->_ratio_ver;
	}	
	
	public function AxeHorizontale($position) {
		$retour = SVG::rectangle(0,$this->coord_y($position)-round($this->_ratio_ver / 2), 1, $this->_taille_hor, array('class' => 'axe'));
		$retour .= SVG::texte($this->coord_x($this->_x_min) + 15,$this->coord_y($position+2),'Y='.$position);
		$retour .= SVG::texte($this->coord_x($this->_x_max) - 40,$this->coord_y($position+2),'Y='.$position);
		return $retour;
	}
	
	public function AxeVerticale($position) {
		$retour = SVG::rectangle($this->coord_x($position)+round($this->_ratio_hor / 2), 0, $this->_taille_ver, 1, array('class' => 'axe'));
		$retour .= SVG::texte($this->coord_x($position+2),$this->coord_y($this->_y_min) - 5,'X='.$position);
		$retour .= SVG::texte($this->coord_x($position+2),$this->coord_y($this->_y_max) + 20,'X='.$position);
		return $retour;
	}	
	
	public function Boucliers() {
		$boucliers = $this->conn->SelectBoucliersFromDamier($this->_id, $this->_x_min, $this->_x_max, $this->_y_min, $this->_y_max);
		
		foreach($boucliers as $bouclier) {
			$this->addBouclier($bouclier['pos_x'], $bouclier['pos_y'], $bouclier['type_id'], $bouclier['id'], $bouclier['nom']);
		}
	}
	
	public function Portes() {
		$portes = $this->conn->SelectPortesFromDamier($this->_id, $this->_x_min, $this->_x_max, $this->_y_min, $this->_y_max);
				
		foreach($portes as $porte) {
			$this->addPorte($porte['pos_x'], $porte['pos_y'], $porte['id'], $porte['nom']);
		}
	}	
	
	public function Persos() {
		$persos = $this->conn->SelectPersosFromDamier($this->_id, $this->_x_min, $this->_x_max, $this->_y_min, $this->_y_max);
		
		foreach($persos as $perso) {
			
			$camp = $perso['camp_id'];
			
			if(isset($this->_couleurs['races'][$camp])) {
				$couleur = $this->_couleurs['races'][$camp];
			} else {
				$couleur = $this->_couleurs['base'];
			}
			$this->addPerso($perso['pos_x'], $perso['pos_y'], $couleur, $perso['grade_id'], $perso['perso_id']);
	
		}
	}
		
	public function Viseurs($persos) {
		$nb_perso =  $persos['inc'];

		for($inc=1 ; $inc<=$nb_perso ; $inc++) {
			$mat = $persos['id'][$inc];
			if(isset($this->_persos[$mat])) {
				$x = $this->_persos[$mat]['x'];
				$y = $this->_persos[$mat]['y'];
				$this->AddViseur($x, $y, $persos['nom'][$inc]);
			}
		}		
	}
	
	public function Compile() {
		
		$retour = '';
		$camp = null;
		
		// Persos
		foreach($this->_persos as $id => $perso) {
			if($camp && $camp != $perso['camp']) {
				$retour .= '</g><g class="'.$perso['camp'].'">';
			} elseif(!$camp) {
				$retour .= '<g class="'.$perso['camp'].'">';
			}
			$camp = $perso['camp'];
			$retour .= $this->PrintPerso($id,true);
			//$retour .= '</g>';
		}
		if($retour != '')
			$retour .= '</g>';
		
		// Boucliers
		$retour .= '<g class="bouclier">';
		foreach($this->_boucliers as $id => $bouclier) {
			$retour .= $this->PrintBouclier($id);
		}
		$retour .= '</g>';		
		
		// Portes
		$retour .= '<g class="porte">';
		foreach($this->_portes as $id => $porte) {
			$retour .= $this->PrintPorte($id);
		}
		$retour .= '</g>';

		// Viseurs
		$retour .= '<g class="viseurs">';
		foreach($this->_viseurs as $id => $viseur) {
			$retour .= $this->PrintViseur($id);
		}
		$retour .= '</g>';		

		// Popups
		$retour .= '<g class="noir">';
		foreach($this->_popup as $popup) {
			$retour .= $this->PrintPopup($popup);
		}
		$retour .= '</g>';	
		
		return $retour;
	}	
	
	public function AddViseur($x,$y,$info) {
		$this->_viseurs[] = array(
			'x' => $x,
			'y' => $y,
			'nom' => $info
		);	
		//echo "$x $y $info";
	}
	
	public function PrintViseur($id) {	
		$viseur = $this->_viseurs[$id];

		/*$max_width = $this->_ratio_ver*0.7;
		$max_height = $this->_ratio_hor*0.7;
		$max_circ = max(min($this->_ratio_ver*5, 20),3);*/
		
		$res = SVG::ligne($this->coord_x($viseur['x']+0.5) - 15,
								$this->coord_y($viseur['y']-0.5),
								$this->coord_x($viseur['x']+0.5) + 15, 
								$this->coord_y($viseur['y']-0.5));		

		$res .= SVG::ligne($this->coord_x($viseur['x']+0.5),
								$this->coord_y($viseur['y']-0.5) - 15,
								$this->coord_x($viseur['x']+0.5), 
								$this->coord_y($viseur['y']-0.5) + 15);									
		
		$res .= SVG::cercle($this->coord_x($viseur['x']+0.5), $this->coord_y($viseur['y']-0.5), 20, array('id' => md5($viseur['nom'])));

		if(($this->coord_y($viseur['y']-0.5) - 20) < 3) {
			$this->_popup[] = array($this->coord_x($viseur['x']+0.5), $this->coord_y($viseur['y']-0.5) + 30, md5($viseur['nom']), $viseur['nom']);
		} else {
			$this->_popup[] = array($this->coord_x($viseur['x']+0.5), $this->coord_y($viseur['y']-0.5) - 20, md5($viseur['nom']), $viseur['nom']);
		}
		
		return $res;
	}
	
	public function PrintPopup($popup) {
		return SVG::popup($popup[0], $popup[1], $popup[2], $popup[3]);
	}
	
	public function AddPerso($x,$y,$camp,$grade,$id) {
		$this->_persos[$id] = array(
			'x' => $x,
			'y' => $y,
			'grade' => $grade,
			'camp' => $camp
		);
	}
	
	private function PrintPerso($id) {
		$perso = $this->_persos[$id];

		return SVG::rectangle($this->coord_x($perso['x']), $this->coord_y($perso['y']), $this->_ratio_ver, $this->_ratio_hor, array('class' => 'g'.$perso['grade'], 'id' => 'perso_'.$perso['x'].'_'.$perso['y']));	

	}
	
	public function AddBouclier($x, $y, $taille, $id, $nom) {
		$this->_boucliers[$id] = array(
			'x' => $x,
			'y' => $y,
			'taille' => $taille,
			'nom' => $nom			
		);
	}
	
	public function PrintBouclier($id) {
		$bouclier = $this->_boucliers[$id];
		
		$this->_popup[] = array($this->coord_x($bouclier['x']+0.5), $this->coord_y($bouclier['y']-0.5) - 35, 'bouclier_'.$id, 'Bouclier');		
		
		return SVG::rectangle($this->coord_x($bouclier['x']),$this->coord_y($bouclier['y']),
								$this->_ratio_ver*$bouclier['taille'], $this->_ratio_hor*$bouclier['taille'], 
								//array('class' => 'bouclier', 'id' => 'bouclier_'.$id, 'onmouseover' => 'affiche_bouclier(this)'));
								array('class' => 'bouclier', 'id' => 'bouclier_'.$id));
	}		
	
	public function AddPorte($x, $y, $id, $nom) {
		$this->_portes[$id] = array(
			'x' => $x,
			'y' => $y,
			'nom' => $nom			
		);
	}
	
	public function PrintPorte($id) {
		$porte = $this->_portes[$id];
				
		$this->_popup[] = array($this->coord_x($porte['x']+0.5), $this->coord_y($porte['y']-0.5) - 20, 'porte_'.$id, 'Porte');					
				
		return SVG::rectangle($this->coord_x($porte['x']),$this->coord_y($porte['y']),
								$this->_ratio_ver*4, $this->_ratio_hor*4, 
								array('class' => 'porte', 'id' => 'porte_'.$id));
	}	

	public function serializer() {
		$this->conn = null;
		return serialize($this);
	}
	
	public static function deserializer($data, $conn) {
		$carte = unserialize($data);
		$carte->conn = $conn;
		return $carte;
	}
	
}

class SVG {

	static function rectangle($x, $y, $hauteur, $largeur, $param = null) {
		$arg = '';
		if(isset($param)) {
			foreach($param as $nom => $valeur) {
				$arg .= $nom.'="'.$valeur.'" ';
			}
		}
		return '<rect x="'.$x.'" y="'.$y.'" width="'.$largeur.'" height="'.$hauteur.'" '.$arg.'/>' . PHP_EOL;

	}

	static function carre($x, $y, $taille, $param) {
		return SVG::rectangle($x, $y, $taille, $taille, $param);
	}

	static function ellipse($x, $y, $hauteur, $largeur, $param = null) {
		$rx = $largeur / 2;
		$ry = $hauteur / 2;
		$arg = '';
		
		if(isset($param)) {
			foreach($param as $nom => $valeur) {
				$arg .= $nom.'="'.$valeur.'" ';
			}
		}		
		return '<ellipse cx="'.$x.'" cy="'.$y.'" rx="'.$rx.'" ry="'.$ry.'" '.$arg.'/>' . PHP_EOL;
	}

	static function cercle($x, $y, $rayon, $param = null) {
		$r = $rayon / 2;
		$arg = '';
		
		if(isset($param)) {
			foreach($param as $nom => $valeur) {
				$arg .= $nom.'="'.$valeur.'" ';
			}
		}		
		return '<circle cx="'.$x.'" cy="'.$y.'" r="'.$r.'" '.$arg.'/>' . PHP_EOL;
	}

	static function ligne($x_depart, $y_depart, $x_arrive, $y_arrive, $classe = null) {
		return '<line class="'.$classe.'" x1="'.$x_depart.'" y1="'.$y_depart.'" x2="'.$x_arrive.'" y2="'.$y_arrive.'" />' . PHP_EOL;
	}

	static function polygone_ouvert($tableau, $classe = null) {
		$points = implode(",",$tableau);
		return '<polyline class="'.$classe.'" points="'.$points.'" />' . PHP_EOL;
	}

	static function polygone($tableau, $classe = null) {
		$points = implode(",",$tableau);
		return '<polygon class="'.$classe.'" points="'.$points.'" />' . PHP_EOL;
	}

	static function texte($x,$y,$texte) {
		return '<text x="'.$x.'" y="'.$y.'">'.$texte.'</text>' . PHP_EOL;
	}
	
	static function popup($x,$y,$parent,$texte) {
	
		/*$largeur = strlen($texte) * 20;
		$hauteur = 20;
	
		$rect = SVG::rectangle($x, $y, $hauteur, $largeur, array('class' => 'blanc'));*/
	
		return '<text id="'.$parent.'_popup" x="'.$x.'" y="'.$y.'" font-size="20" fill="black" visibility="hidden">'.$texte.'
			<set attributeName="visibility" from="hidden" to="visible" begin="'.$parent.'.mouseover" end="'.$parent.'.mouseout"/>
		</text>';
	}
	
	static function TableauJavascript($tableau) {
		return '';
	}
	
	static function image($y, $x, $y_max, $x_max, $data) {
		return '<image x="'.$x.'" y="'.$y.'" width="'.$x_max.'" height="'.$y_max.'" preserveAspectRatio="none" xlink:href="data:image/png;base64,'.$data.'" />';		
	}
	
	static function Header($titre, $largeur, $hauteur, $date, $css = null) {

		$retour = '<?xml version="1.0" standalone="no"?>
		<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" 
		"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
		<svg width="'.$largeur.'" height="'.$hauteur.'" version="1.1"
		xmlns="http://www.w3.org/2000/svg"
		xmlns:xlink="http://www.w3.org/1999/xlink">
		<title>'.$titre.'</title>
		<script type="text/ecmascript"> 
		<![CDATA[
		
			top.getAllBouclier = getAllBouclier;
			top.getElem = getElem;
			top.getSvg = getSvg;		
		
			var Date = '.$date.';
			var TableBouclier = new Array();
			TableBouclier["bouclier_4"] = "Bouclier Humain : Terra";
			TableBouclier["bouclier_7"] = "Aero";
			TableBouclier["bouclier_9"] = "Ultima";
			TableBouclier["bouclier_11"] = "Bouclier T2";
			
			function getElem(methode){
				return eval(methode);
			}
			
			
			function getSvg(){
				return document;
			}
			
			function getAllBouclier(){
				return TableBouclier;
			}

		]]></script>
		';
		
		if(!isset($css)) {
			$retour .= '<style type="text/css" >
			  <![CDATA[

				.fond_terre {
				   fill:   rgb(170, 221, 170);
				}
				
				.fond_ciferis {
				   fill:   rgb(223, 170, 170);
				}		

				.fond_celestia {
				   fill:   rgb(170, 170, 221);
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

				.viseurs {
					stroke: rgb(200,0,0);
					fill-opacity: 0.05;
				}
				
				.blanc {
					fill:	rgb(255,255,255);
				}

			  ]]>
			</style>';	
		}
		
		return $retour;
	}

	static function Footer() {
		echo '</svg>';
	}	
	
	static function Javascript() {
		return '
		<text id="thingyouhoverover" x="50" y="35" font-size="14">Mouse over me!</text>
		
		<text id="thepopup" x="250" y="100" font-size="30" fill="black" visibility="hidden">Change me
			<set attributeName="visibility" from="hidden" to="visible" begin="thingyouhoverover.mouseover" end="thingyouhoverover.mouseout"/>
		</text>';	
	}
}
