<?php
/**
 * Affiche la liste des partenaires ayant participé a Ewo
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package partenaires
 */
//-- Header --
$root_url = "..";
$header['title'] = "Partenaires";
$header['desc'] = "Partenaires du projet Ewo ainsi que les scripts open source utilisés.";
include($root_url."/template/header_new.php");
//------------
?>

<h2>E.W.O. - Partenaires du projet</h2>

			<div class='cadre'>
			<b>- Développement de EWO -</b>
			<ul>
				<li>Chef de projet</li>
				<li>
					<ul>
						<li>- Kazuya</li>
					</ul>
				</li>
				<li>Gestionnaire de projet</li>
				<li>
					<ul>
						<li>- Aigleblanc</li>
					</ul>
				</li>
				<li>Programmeurs actifs</li>	
				<li>
					<ul>
						<li>- Aigleblanc</li>
						<li>- Anarion</li>
						<li>- Kazuya</li>
						<li>- Salelodenouye</li>
					</ul>
				</li>	
				<li>Programmeurs non-actifs</li>	
				<li>
					<ul>
						<li>- Le barge</li>
						<li>- Schepi</li>
					</ul>
				</li>	
				<li>Designers actifs</li>	
				<li>
					<ul>
						<li>- Aigleblanc</li>
						<li>- Francis-</li>
					</ul>
				</li>
				<li>Rêgles</li>	
				<li>
					<ul>
						<li>- Aigleblanc </li>
						<li>- Aniol</li>
						<li>- Deyron</li>
						<li>- Kazuya</li>
						<li>- Mika</li>
					</ul>
				</li>
			</ul>
			</div>
			
			<hr class='demon_hr' />
			
			<div class='cadre2'>			
			<b>- Association EWO 1901 -</b>
			
			<ul>
			<li>Président</li>
				<li>
					<ul>
						<li>- ????</li>
					</ul>
				</li>
			<li>Vice-Président</li>
				<li>
					<ul>
						<li>- ????</li>
					</ul>
				</li>		
			<li>Comptable</li>
				<li>
					<ul>
						<li>- ????</li>
					</ul>
				</li>
			<li>Secrétaire</li>
				<li>
					<ul>
						<li>- ????</li>
					</ul>
				</li>			
			<li>Membres Actifs</li>
				<li>
					<ul>
						<li>- ????</li>
					</ul>
				</li>
				<li>Membres d'honneur</li>
				<li>
					<ul>
						<li>- ????</li>
					</ul>
				</li>				
			<li>Membres</li>
				<li>
					<ul>
						<li>- ????</li>
					</ul>
				</li>
			</ul>
			</div>
			
			<hr class='ange_hr' />
			
			<b>- Script Open source utilisés dans EWO -</b>
			
			<ul>
				<li>- Forum : <a href="http://www.phpbb.com/" alt='PhpBB'>PhpBB 3</a></li>
				<li>- Lib Ajax : <a href="http://script.aculo.us/" alt='Scripaculous'>Scriptaculous</a></li>
				<li>- Editeur wysiwyg : </li>
			</ul>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
