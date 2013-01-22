<?php
/**
 * Description du module de légion
 *
 * @author Benjamin Herbomez <benjamin.herbomez@esial.com>
 *
 * Organisation :
 *  *   ajax :
 *      *   actions utilisables depuis ajax
 *      *   tout les échanges se font via Json
 *  *   class :
 *      *   liste des classes utlisées
 *      *   une partie des données est gérée via APC, il peut donc y avoir des problèmes de rafraichissement
 *  *   gestionLegion :
 *      *   toutes les fonctions utilisables à l'intérieur de la légion
 *      *   l'accès aux différentes fonctions est gérée au niveau suppérieur (fichier legion_inter.php.inc
 *  *   imgs
 *      *   les images utilisablées dans le module
 *  *   js :
 *      *   le js utilisé en interne.
 *      *   il faudra centraliser tout ça, mais en l'abscence de packaging clair du js ça risque de poser plus de problème que d'en résoudre
 *  *   config          : les actions utilisables
 *  *   doc             : le présent fichier
 *  *   fonctions       : les fonctions utiles
 *  *   index           : point d'entrée de tout le module
 *  *   legion          : affichage d'une légion en particulier
 *  *   legion_intern   : les actions pour les membres d'une légion
 *  *   legions         : affichage de la liste des légions
 *  *   liste           : le menu de gauche
 *  *   perso           : vue depuis un perso particulier
 *  *   style           : le css utilisé
 *
 * La majorité des actions utiles en dehors du module sont contenues dans les classes. Par exemple, la gestion des droits (pour l'affichage du GPS, de la ballist, ...)
 * Pour ajouter un outil en interne, il suffit d'éditer le tableau dans legion_intern et d'ajouter les bonnes constantes
 *      la gestion des droits d'accès à ces outils se fait aussi dans legion_intern
 */
?>
