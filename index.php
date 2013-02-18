<?php
//-- Header --
$pagetype = 'accueil';
$css_files = 'accueil';
include __DIR__ . '/conf/master.php';
include(SERVER_ROOT."/template/header_new.php");
//------------

$js->addScript('konami/konami');
$js->addScript('konami/katawa');



?>
<div id='divK'
	style="display:none;position:fixed;top:0;left:0;right:0px; bottom:0px;width:100%;height:100%;z-index:100000000000;background-color:rgba(255,255,255,0.2);">
	<!--
	<embed src="http://images.4channel.org/f/src/katawa_crash_beta_8-36.swf" 
	id="flashK" width="70%" height="70%" style="margin-left:15%;margin-top:10%;box-shadow: 0px 0px 200px black;border:1px solid black;"/>
	-->
</div>

    <div id="zone_sup">
        <div>
            <img src="images/site/logo.png" class="logo" alt="Logo d'EWO">
            <h1>Eternal War One</h1>
            <p><b>EWO</b> - <b>E</b>ternal <b>W</b>ar <b>O</b>ne de son petit nom - est un <b>jeu multijoueurs</b> où de curieux personnages vivent des aventures mais aussi, et surtout, surtout des mésaventures. En plus, ça les fait marrer.</p>
            <p>Dans un monde représenté par un damier servant de champ de batailles, des <b>Anges</b>, des <b>Démons</b> et des <b>Humains</b> écrivent l'Histoire aussi intrigante que loufoque d'<b>EWO</b> !</p>
            <p>Prenez quelques Anges que vous mélangez à des Démons, rajoutez quelques Humains et secouez le tout : vous obtiendrez des <b>batailles épiques</b>, des <b>histoires fantastiques</b>, des <b>dénouements comiques</b> et des <b>escargots alcooliques</b> ! Rien que ça. Alors rejoignez-les dans <b>le Monde d'EWO</b> !</p>        
            </div>
    </div>
	<div id="zone_mid_right">
		<b>Message du jour</b>
		<blockquote cite="<?php echo SERVER_URL; ?>/persos/event/id=131"><p>Zut! Je voulais lui faire<br />le coup du<br />&quot;Touché!&quot;<br />mais il est tombé comme<br />une larve. Dommage...</p></blockquote>
		<a href="<?php echo SERVER_URL; ?>/persos/event/?id=131">StratiX</a>
	</div>	
    <div id="zone_mid_left">
        <div id="carousel"><ul>
        <?php
        //$annonces = annonce_mixtes(15);
        
		include(SERVER_ROOT . '/lib/syndexport/ewo.php');
		
		$annonces = getAnnonces(15);
		
        for($i = 0; $i < 5; $i++)
        {
            $ligne = current($annonces);
			
			if(!empty($ligne['auteur'])) {
				echo "<li><div><b>".$ligne['titre']."</b>
					<p>".$ligne['corps']."</p>
					<p>par ".$ligne['auteur']." (<a href='".$ligne['lien']."'>Suite</a>)</p></div>
				</li>";    			
			} else {
				echo "<li><div><b>".$ligne['titre']."</b>
					<p>".$ligne['corps']."</p>
					<p><a href='".$ligne['lien']."'>Suite</a></p></div>
				</li>";    			
			}

            
            next($annonces);
        }         
        ?> </ul></div>
    </div>
    <div id="zone_inf"><ul>
        <?php
        
        for($i = 0; $i < 15; $i++)
        {
            $ligne = current($annonces);

            echo "<li><a href='".$ligne['lien']."'>".$ligne['titre']."</a></li>";    
            
            next($annonces);
        }  
        
        ?>
            </ul>
    </div>
<br />
<?php
	/*annonce_blog(1);
	annonce_forum(2,3);*/
	//annonce_mixtes(5);


//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
