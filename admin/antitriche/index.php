<?php
	require_once('session_store.php.inc');
	require_once('views/menu.php.inc');
	
	
	
	if(!$at	= get())
		echo 'Tu n\'as rien à faire ici petit con';// TODO gérer l'erreur
		
	if(isset($_GET['slide'])){
		if(isset($_GET['action'])){
			if(allowed($_GET['action'],$at)){
				if(file_exists('js/'.$_GET['action'].'.js')){
					echo '
				<script type="text/javascript" src="js/'.$_GET['action'].'.js"></script>
					';
				}
				
				include_once('views/'.$_GET['action'].'.php.inc');
				view_log($at);
				echo content($at);
				
				die();
			}
			else
				echo 'error';
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
	<head>
		<title>Anti-triche</title>
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="style.css" />
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="js/jquery.autocomplete.css" />
		
		<script type="text/javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.min.js"></script>
		<script type="text/javascript" src="js/lib/jquery.bgiframe.min.js"></script>
		<?php
			if(isset($_GET['action']) && allowed($_GET['action'],$at) && file_exists('js/'.$_GET['action'].'.js')){
				echo '
			<script type="text/javascript" src="js/'.$_GET['action'].'.js"></script>
				';
			}
		?>
		
	</head>
	<body>
		<header>
			<nav>
				<?php
					echo menu($at);
				?>
				<div class='spacer'></div>
			</nav>
		</header>
		<section id='main'>
			<?php
				if(isset($_GET['action'])){
					if(allowed($_GET['action'],$at)){
						include_once('views/'.$_GET['action'].'.php.inc');
						view_log($at);
						echo content($at);
					}
					else
						echo 'error';
				}
			?>
		</section>
		<footer>
		</footer>
	</body>
</html>
<?php
	store($at);
?>
