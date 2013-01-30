<?php
/**
 * Index de la boutique de ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package boutique
 */
//-- Header --
$root_url = "..";
$header['title'] = "Boutique goodies";
$header['desc'] = "Faites un tour par ici pour vous faire plaisir et ressembler à un Humain, Ange, Démon ou même boire un café dans une tasse escargot !";
include($root_url."/template/header_new.php");
//------------
?>
<br />
			<iframe height="1550" width="800" src="http://eternal-war-one.spreadshirt.fr/" name="Spreadshop" id="Spreadshop" frameborder="0"></iframe>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
