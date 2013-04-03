<?php

$pseudocss = true;

include 'header.php';

$file = $_SESSION['cartographe']['raw'];

include('raw/'.$file.'_map.php');

include('palette/'.$file.'.php');

include('raw/'.$file.'_sav.php');

$size = 60;

if(isset($_GET['page_x'])) {
    $page_x = $_GET['page_x'];
} else {
    $page_x = 0;
}

if(isset($_GET['page_y'])) {
    $page_y = $_GET['page_y'];
} else {
    $page_y = 0;
}

$x_offset = ($page_x * $size) + $size;
$y_offset = ($page_y * $size) + $size;

echo "Positions: $x_min - $x_max, $y_min - $y_max";
$x_max = min(($x_min + $x_offset), $x_max);
$y_max = min(($y_min + $y_offset), $y_max);

$y_min = max($y_min, $y_max - $size);
$x_min = max($x_min, $x_max - $size);

echo '<div id="head"><div class="liste_outils"><h3>Outil disponibles</h3><ul>';
foreach($css as $id => $outil) {
    echo '<li><div data-outilId="'.$id.'" class="damier_'.$outil['nom'].'"></li>';
}
echo '</ul>';

echo "Positions: X = $x_min - $x_max, Y = $y_min - $y_max";

echo '</div>';
reset($css);
$first = current($css);

echo '<div class="outil"><h3>Outil actuel</h3><div><span><img src="'.SERVER_URL.'/images/decors/motifs/'.$first['img'].'"></span>
    <input id="modeedition" type="checkbox">
    <input type="button" id="sauver" value="sauver"></div>
        <a href="#" class="autosave" data-x="'.$page_x.'" data-y="'.($page_y + 1).'">Nord</a><br>
        <a href="#" class="autosave" data-x="'.($page_x - 1).'" data-y="'.$page_y.'">Ouest</a> | 
        <a href="#" class="autosave" data-x="'.($page_x + 1).'" data-y="'.$page_y.'">Est</a><br>
        <a href="#" class="autosave" data-x="'.$page_x.'" data-y="'.($page_y - 1).'">Sud</a>
    </div></div>';

echo '<table cellspacing="0" cellpadding="0" border="0" width="'.(($y_max-$y_min)*45).'" height="'.(($x_max-$x_min)*39).'">';
for($y = $y_max-1; $y >= $y_min; $y--) {
    echo '<tr>';
    
    for($x = $x_min; $x < $x_max; $x++) {
        $couleur = $carte[$x][$y];
        
        //$nom = (isset($css[$couleur]['nom'])) ? $css[$couleur]['nom'] : '';
        
        /*if(isset($css[$couleur]['img'])) {
            $background = '';
            if(isset($css[$couleur]['back'])) {
                $background = 'background: url('.SERVER_URL.'/images/decors/motifs/'.$css[$couleur]['back'].');';
            }
            
            $img =  '<img src="'.SERVER_URL.'/images/decors/motifs/'.$css[$couleur]['img'].'" style="border: 1px #000 solid; '.$background.'">';       
        */
        if(isset($css[$couleur]['nom'])) {
            $class = $css[$couleur]['nom'];
            $texte = '';
        } else {
            $class = '';
            $texte = '<small>'.$couleur.'</small>';
            //$texte = '(vide)';
        }
        
        echo '<td class="damier_'.$class.' case" data-color="'.$couleur.'" data-classe="'.$class.'" data-x="'.$x.'" data-y="'.$y.'">'.$texte.'</td>';
    }
    echo '</tr>';
    
}
echo '</table>';

?>
<script type="text/javascript">

//outilId = "<?php echo $first['nom'] ?>";
edition = new Array();
    
$(function(){
    $(".case").on("click", function() {
        mode = $("#modeedition").is(':checked')
         if(mode == 1) {
		 
			var outilId = $(".outil span div").data("outilid");
			var outilClass = $(".outil span div").attr("class");
		 
            var x = $(this).data("x");
            var y = $(this).data("y");
            editeCase(x,y,outilId);
            $(this).removeAttr("class");
            $(this).addClass("case");
            $(this).addClass(outilClass);
        }

    });
    
    $(".liste_outils li").on("click", function() {
        //outilClass = $(this).getClass();
        $(".outil span").html($(this).html());
    })
    
    $("#sauver").on("click", function() {
        sauver();
    })    

    $(".autosave").on("click", function() {
        var x = $(this).data("x");
        var y = $(this).data("y");

        sauver();

        window.location.href = 'edition_carte.php?page_x=' + x + '&page_y=' + y;

    })
    
    function sauver() {
        var texte = JSON.stringify(edition);
        $.post("sauver_carte.php", { data: texte }, function(data) {
			if(data === "ok") {
				edition = new Array();
			}
		});
    }
    
    function editeCase(x,y,classe) {
		indice = edition.length
        edition[indice] = {pos: x + "_" + y, classe: classe};
    }
 
});

</script>