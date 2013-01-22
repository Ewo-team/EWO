<?php
// Fichier de test pour l'automatisation de la fabrication de plans

session_start();
//header ("Content-type: image/png");

include("../conf/connect.conf.php");
// Paramètres de connexion à la base de données
mysql_connect($_SERVEUR,$_USER,$_PASS);
mysql_select_db($_BDD);


function cmp($terrain1, $terrain2){

    if ($terrain1['couleur'] == $terrain2['couleur']) {
        return 0;
    }
    return ($terrain1['couleur'] < $terrain2['couleur']) ? -1 : 1;

}

// Fonction renvoyant la teinte en fonction d'un tableau RVB
function RVB_to_T($tab_rvb){
$Tmax=360;

$R = $tab_rvb['red'];
$V = $tab_rvb['green'];
$B = $tab_rvb['blue'];

$M=max($R, $V, $B);
$m=min($R, $V, $B);
$delta = $M - $m;

switch($M){
	case $R: $T0=($V-$B)/$delta;
		break;

	case $V: $T0=($B-$R)/$delta + 2;
		break;

	case $B: $T0=($R-$V)/$delta + 4;
		break;

	}
return floor(fmod(1/6*$T0*$Tmax, $Tmax));
}


//Fonction placant le terrain pour une carte donnée :
function place_terrain($carte_id, $width, $height, $T, $terrain){

$inc=0;
while(isset ($terrain[$inc]['id'])){
	if($terrain[$inc]['Tmin']<=$T && $terrain[$inc]['Tmax']>=$T){
		$terrain_id = $terrain[$inc]['id'];
		break;
		}
	$inc++;
	}


$sql = "INSERT INTO `damier_terrain` (`id`, `carte_id`, `terrain_id`, `pos_x`, `pos_y`) VALUES ('', `$carte_id`,`$terrain_id`,`$width`,`$height` )";
$result = mysql_query($sql) or die(mysql_error());
}

/*
//Si pas de liste préconcue :
//Listage des types de terrains possible
// et association à leur couleur RVB
// on obtient une couleur

//Creation de l'objet image, suppose que l'image est en jpg
// Pour le png utiliser imagecreatefrompng.
$image = imagecreatefromjpeg('./decors/motifs/pattern_grass.jpg');
//Moyennage des couleurs pour en obtenir une seule
imagetruecolortopalette($image, true, 1);
//recuperation de l'index de la couleur
$color_index = imagecolorat($image, 0, 0);
//Mise en tableau des valeurs
$terrain[1]['img'] = './decors/motifs/pattern_grass.jpg';
//Recupération des composante de la couleur
//il s'agit d'un tableau de valeur
$terrain[1]['rvb'] = imagecolorsforindex($image, $color_index);
//destruction de l'image qui n'est plus utile.
imagedestroy($image);

$image = imagecreatefromjpeg('./decors/motifs/grass2.jpg');
imagetruecolortopalette($image, true, 1);
$color_index = imagecolorat($image, 0, 0);
$terrain[2]['img'] = './decors/motifs/grass2.jpg';
$terrain[2]['rvb'] = imagecolorsforindex($image, $color_index);

imagedestroy($image);
// etc ...

print_r($terrain);
//Calcul de la teinte correspondante

for($inc=1; $inc<=$nb_terrain; $inc++){
$terrain[$inc]['couleur'] = RVB_to_T($terrain[$inc]['rvb']);

//Mise en bdd du terrain
}*/

//Si la liste existe déjà on commence directement ici
//Recuperation de la liste des terrains depuis la bdd
$sql = "SELECT * FROM case_terrain";
$result = mysql_query($sql) or die(mysql_error());
$inc=1;
while($terrain[$inc]=mysql_fetch_array ($result)){
$inc++;
}
//Tri par teinte
print_r($terrain);
uasort($terrain, 'cmp');
print_r($terrain);

$incmax=$inc-1; // Nombre d'elements de la liste

//Association d'une zone par teinte
for($inc=1; $inc<=$incmax; $inc++){
	if ($inc == 1){
	$terrain[$inc]['Tmax']=($terrain[$inc]['couleur']+$terrain[$inc+1]['couleur'])/2;
	$terrain[$inc]['Tmin']=max(0,(($terrain[$incmax]['couleur']-360)+$terrain[$inc]['couleur'])/2);
	}
	elseif ($inc == $incmax){
	$terrain[$inc]['Tmin']=($terrain[$inc-1]['couleur']+$terrain[$inc]['couleur'])/2;
	$terrain[$inc]['Tmax']=min(360,(($terrain[1]['couleur']+360)+$terrain[$inc]['couleur'])/2);
	}else{
	$terrain[$inc]['Tmin']=($terrain[$inc-1]['couleur']+$terrain[$inc]['couleur'])/2;
	$terrain[$inc]['Tmax']=($terrain[$inc]['couleur']+$terrain[$inc+1]['couleur'])/2;
	}
}
//Ajout du terrain pour boucler toutes les zones
if($terrain[1]['Tmin']==0){
	$terrain[0]=$terrain[$incmax];
	$terrain[0]['Tmin'] = $terrain[$incmax]['Tmax'];
	$terrain[0]['Tmax'] = 360;
	}else{
		$terrain[0]=$terrain[1];
		$terrain[0]['Tmax'] = $terrain[$incmax]['Tmin'];
		$terrain[0]['Tmin'] = 0;
		}

//Recherche de la correspondance entre chaque pixel d'une image et un terrain
// Association du pixel au terrain
$image = imagecreatefromjpeg('./cartes/terre.jpg');
$size = getimagesize('./cartes/terre.jpg');
$x_max = $size['0'];
$y_max = $size['1']; 
for($height=0; $height<=$y_max; $height++){
	for($width=0; $width<=$x_max; $height++){
		$color_index = imagecolorat($image, $width, $height);		
		$T = RVB_to_T(imagecolorsforindex($image, $color_index));
		place_terrain($carte_id, $width, $height, $T, $terrain);
		}


}

imagedestroy($image);
?>
