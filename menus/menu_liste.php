<?php
/**
 * Affiche le menu dans le header de EWo
 *
 * Ne s'affiche uniquement que si la var $template_on existe.
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package menus
 */
 

 
if (isset($template_on)){
    

    if(isset($template_vanilla)) {
	include('menu_new.php');
        // @TODO changer pour la VF
        
        ?>
        <div id="nav">
            <div id="top_bar">
                <ul id="menuDeroulant">
                <?php
                        foreach($menu as $nom => $sousmenu) {

                                $html_id = (isset($sousmenu[0]['id'])) ? ' id="'.$sousmenu[0]['id'].'"' : '';
                                $html_class = (isset($sousmenu[0]['class'])) ? ' class="'.$sousmenu[0]['class'].'"' : '';
                                        ?>
                                        <li <?php echo $html_id; echo $html_class ?>>

                                                <a href="<?php echo $sousmenu[0]['url']; ?>"><?php echo $sousmenu[0]['nom']; ?></a>

                                                <?php 
                                                if(count($sousmenu) > 1) {
                                                    ?><ul><?php
                                                    foreach($sousmenu as $k => $item) {
                                                            if($k != 0) {

                                                                    $html_id = (isset($item['id'])) ? ' id="'.$item['id'].'"' : '';
                                                                    $html_class = (isset($item['class'])) ? '  class="'.$item['class'].'"' : '';
                                                                    echo '<li '.$html_id.$html_class.'><a href="'.$item['url'].'">'.$item['nom'].'</a></li>';

                                                            }
                                                    }
                                                    ?></ul><?php
                                                }

                                                ?>
                                        </li>				
                                        <?php
                        }
                ?>
                </ul>
            </div>
        </div>
        <?php
    } else {
        include('menu.php');
	?>
	<div id="topbar">
		<div id="topbar-content">
			<ul id="menuDeroulant">
				<?php 
				foreach($menu as $nom => $sousmenu) {
					if(count($sousmenu) > 0) {
						$taille = 'petit';
						if(isset($sousmenu[0]['taille'])) {
							$taille = $sousmenu[0]['taille'];
						}
						
						$style = '';
						if(isset($sousmenu[0]['style'])) {
							$style = $sousmenu[0]['style'];
						}					
						?>
						<li class='<?php echo $taille; ?>'>
							<a class='<?php echo $style; ?>' href="<?php echo $sousmenu[0]['url']; ?>"><?php echo $sousmenu[0]['nom']; ?></a>
							<ul class="sousMenu" id="menu_liste">
							<?php foreach($sousmenu as $k => $item) {
								if($k != 0) {
									echo '<li class="petit"><a href="'.$item['url'].'">'.$item['nom'].'</a></li>';
								}
							}
							?>
							</ul>
						</li>				
						<?php
					}
				}
					?>
					
			</ul>
		</div>
		<div id="topbar-ombre"></div>
	</div>	
	<?php 
    }
}
?>
