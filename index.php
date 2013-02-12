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
            <img src="images/site/logo.png" style="height: 175px; width: 254px; padding-right: 30px; float: left;">
            <h1>Eternal War One</h1>
            <p><b>EWO</b> - <b>E</b>ternal <b>W</b>ar <b>O</b>ne de son petit nom - est un <b>jeu multijoueurs</b> où de curieux personnages vivent des aventures mais aussi, et surtout, surtout des mésaventures. En plus, ça les fait marrer.</p>
            <p>Dans un monde représenté par un damier servant de champ de batailles, des <b>Anges</b>, des <b>Démons</b> et des <b>Humains</b> écrivent l'Histoire aussi intrigante que loufoque d'<b>EWO</b> !</p>
            <p>Prenez quelques Anges que vous mélangez à des Démons, rajoutez quelques Humains et secouez le tout : vous obtiendrez des <b>batailles épiques</b>, des <b>histoires fantastiques</b>, des <b>dénouements comiques</b> et des <b>escargots alcooliques</b> ! Rien que ça. Alors rejoignez-les dans <b>le Monde d'EWO</b> !</p>        
            </div>
    </div>
	<div id="zone_mid_right">
		<b>Message du jour</b>
		<blockquote cite="<?php echo SERVER_URL; ?>/persos/event/liste_events.php?id=131"><p>Zut! Je voulais lui faire<br />le coup du<br />&quot;Touché!&quot;<br />mais il est tombé comme<br />une larve. Dommage...</p></blockquote>
		<a href="<?php echo SERVER_URL; ?>/persos/event/liste_events.php?id=131">StratiX</a>
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
        ?> </ul></div><!--
        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam consectetur nulla vel tortor consectetur ultricies euismod metus blandit. Pellentesque molestie tempus felis id iaculis. Curabitur condimentum, elit ut laoreet lacinia, velit sapien imperdiet elit, eget cursus leo tortor quis ante. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Quisque in massa urna. Praesent feugiat, felis nec tincidunt vulputate, lacus est posuere eros, id mattis quam ipsum nec felis. Sed sapien elit, cursus et lacinia vitae, mattis eu libero. Duis mauris dolor, egestas gravida tincidunt eu, bibendum non elit. Phasellus magna velit, sollicitudin sed tristique eu, pulvinar ac neque. Aliquam accumsan nibh non quam pellentesque at iaculis sem porttitor.</div>

        <div class="ui-helper-hidden">Cras sit amet risus nec nulla suscipit ultrices id eu tellus. Proin hendrerit ultricies metus, at pretium eros ultrices quis. Duis imperdiet, odio ut auctor suscipit, neque urna semper sapien, sit amet suscipit elit tellus nec neque. Pellentesque facilisis posuere tortor, vel ornare lacus porttitor vitae. Nullam aliquam, orci malesuada tincidunt lacinia, eros metus pharetra erat, vel dapibus nulla elit in enim. Sed sit amet venenatis nulla. Ut eu euismod mi.</div>

        <div class="ui-helper-hidden">Morbi posuere venenatis mi eu convallis. Proin sit amet risus diam, sit amet suscipit odio. In a lacus sit amet nulla tempus interdum. Aenean dapibus luctus felis, vitae volutpat est condimentum sit amet. Aenean tincidunt placerat eros et tristique. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Morbi vitae neque nunc, quis semper lectus. Sed venenatis augue mi, dapibus feugiat nisi. Duis ut nunc non turpis mattis lobortis.</div>

        <div class="ui-helper-hidden">Pellentesque in leo elit. Donec sapien turpis, tincidunt a porttitor at, lacinia volutpat arcu. Cras nisi est, ultrices eu facilisis a, ultrices ultricies metus. Suspendisse potenti. Etiam scelerisque lectus eu lorem facilisis eget rutrum ipsum pulvinar. Mauris id nulla lacus, a vulputate quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean at venenatis lectus. Proin a tincidunt ipsum. Proin pretium malesuada nisi.</div>

        <div class="ui-helper-hidden">Praesent vel ante id augue vulputate eleifend. Nullam mattis sem eu libero accumsan ac ultrices nisi fringilla. Mauris consectetur mattis mauris at auctor. Sed sed convallis odio. Aenean viverra nisi eget sem mollis sed vestibulum dolor vestibulum. Proin tincidunt ligula sollicitudin odio aliquet vitae convallis metus pellentesque. Vivamus sit amet urna ac eros elementum ullamcorper non quis nisl. Integer tempor dolor ipsum. Quisque viverra, nisl sit amet lacinia blandit, justo ante elementum nibh, vel interdum nunc diam nec nisi.</div> -->
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
        <!--<ul>
            <li>1er février : aux termes d'un accord avec les éditeurs de presse français, Google s'engage à financer à hauteur de 60 millions d'euros un fonds d'aide à « la transition de la presse vers le monde du numérique ».</li>
            <li>28 janvier : Béatrix (photo), reine des Pays-Bas, annonce qu’elle abdiquera le 30 avril 2013, à l’occasion du Koninginnedag, en faveur de son fils aîné Willem-Alexander.</li>
            <li>28 janvier : les forces françaises de l’opération Serval et l’armée malienne prennent la ville de Tombouctou, qui était sous contrôle islamiste depuis dix mois.</li>
            <li>27 janvier : en Bulgarie, un référendum pour le développement de l'énergie nucléaire recueille près de 62 % de votes favorables mais avec seulement 20 % de participation, il est invalidé.</li>
            <li>27 janvier : le skipper français François Gabart remporte le Vendée Globe dans le temps record de 78 jours, 2 heures et 16 minutes.</li>
        </ul>-->
    </div>

<!--
<div id="head_index">


<!--

-->
<!-- conteneur -->
<!-- 
<div id="gallery">  
     <a href="#" class="show">  
     <img src="/images/site/bd21.png" alt="Flowing Rock" alt="" title="" width="580" height="360" rel="<h3>Ewo</h3>Présentation du jeu"/></a>  
     </a>  
       
     <a href="#">  
         <img src="/images/site/ewo_wait.png" alt="Grass Blades" alt="" title="" width="580" height="360" rel="<h3>Ewo</h3>Présentation du jeu"/>  
     </a>  

   
     <div class="caption"><div class="content"></div></div>  
</div>
--> 
<!-- <div class="clear"></div>  -->
<br />
<?php
	/*annonce_blog(1);
	annonce_forum(2,3);*/
	//annonce_mixtes(5);


//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
