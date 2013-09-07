<?php
namespace jeu\legion;

require('fonctions_traitre.php');
?>

<h2>E.W.O. - Tra&icirc;tres potentiels d&eacute;c&eacute;d&eacute;s dans les 46h pr&eacute;c&eacute;dentes</h2>

<?php
$time = time();
$time = date('d-m-Y', $time);
$time = strtotime($time);
$time_m = date('Y-m-d H:i:s', $time - 48 * 3600);

if(isset($_POST['submit'])){
    $victime_id=mysql_real_escape_string($_POST['victime_id']);
    if(is_numeric($victime_id))
    	nvTraitre($victime_id);
}
$pk = 0;
foreach($_SESSION['persos']['id'] as $k => $v){
	if($v == $_GET['mat'])
		$pk = $k;
}
$resultat = recup_pot_traitre($_GET['mat'], $_SESSION['persos']['camp'][$pk], $time_m);
?>
<table align='center' id="tab_classement" border="1px;" style="border-collapse: collapse;" width="90%">
    <tr>
        <th align="center" class='cla_td_titre large'>Nom (Mat.) du mort</th>
        <th align="center" class='cla_td_titre large'>Heure de la mort</th>
        <th align="center" class='cla_td_titre large'>Traitre !</th>
    </tr>
<?php
$n = 4;

while ($event = mysql_fetch_array($resultat)) {
    $perso_ok = false;
    $id_perso = $event['id_perso'];
    $id_victime = $event['mat_victime'];
    $sql = "SELECT perso_id
                    FROM damier_persos
                            WHERE perso_id='$id_victime'";

    $resultat_ = mysql_query($sql) or die(mysql_error());
    if (!mysql_fetch_array($resultat_)) {
        $perso_ok = true;
    }

    $sql = "SELECT faction_id
        FROM persos
                WHERE id='$id_victime' AND faction_id=0";

    $resultat_ = mysql_query($sql) or die(mysql_error());

    if (mysql_fetch_array($resultat_)) {
        $perso_ok = true;
    }else
        $perso_ok = false;

    if ($perso_ok) {
        $n++;
        $id_perso = $event['id_perso'];
        $nom = $event['nom_victime'];
        $date_event = $event['date'];
        $id_victime = $event['mat_victime'];
        $url = icone_persos($id_victime);

        $url_tueur = icone_persos($id_perso);

        if ($n % 2) {
            $color = 'row0';
        } else {
            $color = 'row1';
        }

        echo "<tr class='$color winner$n'>";
        echo "<td align='center'><img src='../../images/$url' alt='avatar'/><br/>" . $nom . " [<a href='". SERVER_URL ."/persos/event/?id=$id_victime'>$id_victime</a>]</td>";
        echo "<td align='center'>$date_event</td>";
        echo '<td align="center">
                <form name="traitre" action="index.php?p=',$_GET['p'],'&amp;mat=',$_GET['mat'],'&amp;s=',$_GET['s'],'"
                    method="post">
                <input type="hidden" name="victime_id" value="',$id_victime,'" />
                <input type="hidden" name="perso_id" value="',$perso_id,'" />';
        if (isset($faction_id))
            echo "<input type='hidden' name='faction_id' value='$faction_id' />";
        echo "<input type='submit' name='submit' value='Tra&icirc;tre !' class='bouton' />
							</form>
							</td>";
        echo "<tr>";
    }
}
?>
</table>
</div>
