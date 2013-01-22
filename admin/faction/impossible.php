<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
?>
<div align='center' id='contact'>Action non autoris&eacute;e, vous n'avez pas les droits suffisants.</div>
<?php
//-- Footer --
include("../../template/footer.php");
//------------
?>
