<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

$js->addLib('ckeditor/ckeditor');
$js->addLib('ckeditor/sample');
$js->addScript('wysiwyg');
?>

<link href="./../../js/ckeditor/sample.css" rel="stylesheet" type="text/css" />


<h2>Newsletter</h2>
<!-- Debut du coin -->
<div class="upperleft" id='coin_100'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
				<div id='contact' align='center'>
					<form name='new' action="send_newsletter.php" method="post">
						<table>
							<tr>
								<td>Titre : </td> 
								<td><input id='titre' name="titre" type="text" size="60" value=''/></td>
							</tr>	

							<tr>
								<td></td>
								<td><textarea rows="7" name="text" id="text" cols="100"></textarea></td>
							</tr>
							<tr>	
								<td></td>
								<td><input type="submit" value="Envoyer" class='bouton'></td>
							</tr>
						</table>
					</form>
				</div>	
			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
