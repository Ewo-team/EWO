<?php

namespace site;

/**
 * Patchnote
 *
 * Embed un script irc
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package site\chat
 */

//-- Header --
require_once __DIR__ . '/../conf/master.php';

$header['title'] = "Notes de versions";
include(SERVER_ROOT."/template/header_new.php");
//------------
?>

<div align='center'>
<h1>Notes de version</h1>
<ul>
	<li>18 mars 2013: GPS Get Better : Amélioration de l'interface de la mini-map (dit GPS)</li>
	<li>13 mars 2013: Version Final</li>
</ul>
</div>

<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
