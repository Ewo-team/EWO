<?php

session_start();

if(isset($_SESSION['cartographe']['raw'])) {
    $projet = $_SESSION['cartographe']['raw'];
    
    $palette = false;
    $carte = false;
    $export = false;
    $attribution = false;
    
    if(file_exists('raw/'.$projet.'_palette.php')) {
        $palette = true;
    }
    
    if(file_exists('raw/'.$projet.'_map.php') && file_exists('palette/'.$projet.'.php')) {
        $carte = true;
		$export = true;
    } 

    if(file_exists('map/'.$projet.'.php')) {
        $attribution = true;
    }         
    
} else {
    $projet = false;
    $palette = false;
    $carte = false;
    $export = false;
    $attribution = false;    
}

?><!doctype html>
<html lang="fr">
 <head>
  <meta charset="utf-8">
  <title>EWO Cartographe</title>
  <style>
   body { margin:1em auto; }
   a { color:crimson }
   a:visited { text-decoration:none }
   a:hover { color:mediumspringgreen }
   menu li { display: inline }
   .liste_images {
       float: right;
       position: relative; 
       width: 30%       
   }
   .liste_images li, .liste_outils li, .outil span {
        display: inline-block; 
        margin: 5px; 
        padding: 5px; 
        border: 1px red solid;       
   }
   
   .liste_images img {
		width: 30px;
		height: 24px;
   }
   .liste_outils {
		min-width: 640px;
		width: 80%;
		float: right;	   
   }
   
   .liste_outils li {
        background-clip: content-box;     
   }
   
   .case, td, tr, .outil span  {
       
       width: 45px;
       height: 39px;       
   }
   
   .liste_outils div {
       
       width: 30px;
       height: 26px;  
	   background-size: cover;	  
	   
   }  

   .outil {
		width: 20%;
		min-width: 160px;   
   }
   
	.liste_outils ul {
		overflow-y: auto;
		max-height: 160px;	
	}
   
   .case {
       padding: 0px;
   }
   
   #head {
		position: fixed;
		bottom: 0px;
		background-color: rgba(255,255,255,0.6);
   }
   
   body {
		padding-bottom: 190px;
		padding-top: 52px;
   }
   
   menu {
		position: fixed;
		top: 0px;	
		background-color: rgba(255,255,255,0.6);	
		padding-right: 30px;
   }

  </style>
  <?php if(isset($pseudocss)) { echo '<link rel="stylesheet" href="pseudocss.php?v='.  microtime().'" type="text/css">'; }?>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
 </head>
 <body>
     <menu>
         <ul>
             <li><a href="ressources.php">Ressources</a></li>
             <li><?php if($palette) { echo '<a href="edition_palette.php">Palette</a>'; } else { echo '<span class="desactive">Palette</span>'; }?></li>
             <li><?php if($carte) { echo '<a href="edition_carte.php">Carte</a>'; } else { echo '<span class="desactive">Carte</span>'; }?></li>
             <li><?php if($objets) { echo '<a href="objets.php">Objets</a>'; } else { echo '<span class="desactive">Objets</span>'; }?></li>             
             <li><?php if($export) { echo '<a href="export.php">Export</a>'; } else { echo '<span class="desactive">Export</span>'; }?></li>
             <li><?php if($attribution) { echo '<a href="attribution_map.php">Attribution</a>'; } else { echo '<span class="desactive">Attribution</span>'; }?></li>
             <li><?php if($projet) { echo $projet; } else { echo '<i>Pas de ressource choisie</i>'; }?></li>
         </ul>
     </menu>