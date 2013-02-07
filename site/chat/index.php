<?php

namespace site\chat;

/**
 * IRC - Mibbit
 *
 * Embed un script irc
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package site\chat
 */

//-- Header --
require_once __DIR__ . '/../../conf/master.php';

$header['title'] = "Chat irc";
include(SERVER_ROOT."/template/header_new.php");
//------------
?>

<div align='center'>
<?php
if (isset($_SESSION['utilisateur']['id'])){
	$pseudo = $_SESSION['utilisateur']['nom'];
}else{
	$pseudo = "Ewotux";
	echo "<p>Pour changer de pseudo, taper la commande /nick 'votre pseudo'</p>";
}
?>
<iframe width="960" height="500" scrolling="no" frameborder="0"
 src="http://embed.mibbit.com/?server=irc.iiens.net&channel=%23ewohrp&nick=<?php echo $pseudo; ?>">
</iframe>
</div>

<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
