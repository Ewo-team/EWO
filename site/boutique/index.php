<?php

namespace site\boutique;

/**
 * Index de la boutique de ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package site\boutique
 */
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

$header['title'] = "Boutique goodies";
$header['desc'] = "Faites un tour par ici pour vous faire plaisir et ressembler à un Humain, Ange, Démon ou même boire un café dans une tasse escargot !";
include(SERVER_ROOT . "/template/header_new.php");
//------------
?>
<br />
<iframe height="1550" width="800" src="http://eternal-war-one.spreadshirt.fr/" name="Spreadshop" id="Spreadshop" frameborder="0"></iframe>
<?php
//-- Footer --
include(SERVER_ROOT . "/template/footer_new.php");
//------------
?>
