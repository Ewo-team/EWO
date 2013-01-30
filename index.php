<?php
//-- Header --
$root_url = ".";
//$pagetype = 'accueil';
include 'conf/master.php';
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
</div><?php if(isset($template_vanilla)) { ?>
<div id="head_index">
<img style="display: inline;" src="images/site/logo.png">
<h2 style="display: inline;">Eternal War One</h2>
</div><?php } ?>

<!--
<p><b>EWO</b> - <b>E</b>ternal <b>W</b>ar <b>O</b>ne de son petit nom - est un <b>jeu multijoueurs</b> où de curieux personnages vivent des aventures mais aussi, et surtout, surtout des mésaventures. En plus, ça les fait marrer.</p>
<p>Dans un monde représenté par un damier servant de champ de batailles, des <b>Anges</b>, des <b>Démons</b> et des <b>Humains</b> écrivent l'Histoire aussi intrigante que loufoque d'<b>EWO</b> !</p>
<p>Prenez quelques Anges que vous mélangez à des Démons, rajoutez quelques Humains et secouez le tout : vous obtiendrez des <b>batailles épiques</b>, des <b>histoires fantastiques</b>, des <b>dénouements comiques</b> et des <b>escargots alcooliques</b> ! Rien que ça. Alors rejoignez-les dans <b>le Monde d'EWO</b> !</p>
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
	annonce_mixtes(5);
?>

<p class='centrage'>Pour prendre contact avec la team de codeurs : <a href="./contact/">Contact</a></p>				
<!-- fin conteneur -->

<hr class='demon_hr' />

</script>
<!--

-->
<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
