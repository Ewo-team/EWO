<?php

include_once($root_url.'/event/eventManager.php');
include_once($root_url.'/event/eventFormatter.php');
require_once($root_url.'/persos/fonctions.php');
/**
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

$err = '';

$msgs = array();

$sql = 'SELECT id, texte FROM evenements_texte';
$messages = mysql_query($sql) or die (mysql_error());
while($ligne = mysql_fetch_row($messages)) {
    $msgs[$ligne[0]] = $ligne[1];
}

$fields = array(
    'mat' => array(
        'fct'   => 'is_numeric',
        'sql'   => null,
        '%'     => false),
    'pa' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_pa',
        '%'     => true,
        'carac' => 'pa'),
    'pv' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_pv',
        '%'     => true,
        'carac' => 'pv'),
    'mouv' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_mouv',
        '%'     => true,
        'carac' => 'mouv'),
    'def' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_def',
        '%'     => false),
    'atq' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_att',
        '%'     => false),
    'recup_pv' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_recup_pv',
        '%'     => true,
        'carac' => 'recup_pv'),
    'force' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_force',
        '%'     => true,
        'carac' => 'force'),
    'perception' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_perception',
        '%'     => true,
        'carac' => 'perception'),
    'nv' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_niv_mag',
        '%'     => true,
        'carac' => 'magie'),
    'effet' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_effet',
        '%'     => false),
    'res_mag' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_res_mag',
        '%'     => true,
        'carac' => 'res_mag'),
    'esq_mag' => array(
        'fct'   => 'is_numeric',
        'sql'   => 'alter_esq_mag',
        '%'     => false),
    'eventId' => null,
    'event' => null,
    'duree' => array(
        'fct' => 'is_numeric',
        'sql' => null
    )
);



$post = true;
$percent = array();
foreach($fields as $field => $val){
    if(!isset($_POST[$field])){
        if($field != 'mat')
            $err = $field.' est manquant';
        $post = false;
        break;
    } 
    if($val == null)
        continue;
    $value = $_POST[$field];
    $lastIndex = strlen($value)-1;
    if(substr($value, $lastIndex) == '%'){
        if($val['%'] == null || !$val['%']){
            $err = $field.' n\'admet pas de %';
            $post = false;
            break;
        }
        $value = substr($value, 0, $lastIndex);
        $percent[$field] = $value;
    }
    else
       $percent[$field] = false;
    
    if($val['fct'] != null && !$val['fct']($value)){
        $err = $field.' n\'est pas bien format&eacute;';
        $post = false;
        break;
    }
}
/**
 * Il y a toutes les données qu'il faut pour ajouter le bouzin
 */
if($post){
    $query = 'SELECT `race_id`, `grade_id` FROM `persos` WHERE `id`='.$_POST['mat'];
    $result = mysql_query($query);
    $data = mysql_fetch_object($result);
    
    $caracs_max = caracs_base_max($_POST['mat'],$data->race_id, $data->grade_id);
    $caracs_max['pa'] += $caracs_max['pa_dec']/10;
    $caracs_max['recup_pv'] += 5;

    $names = '';
    $values = '';
    foreach($fields as $field => $val){
        if($val != null && $val['sql'] != null){
            $names .= ','.$val['sql'];
            if(isset($percent[$field]) && $percent[$field] != false){
                $round_dec = 0;
                if($field == 'pa')
                    $round_dec = 1;
                $values .= ','.round($caracs_max[$val['carac']] * $_POST[$field] / 100, $round_dec);     
            }
            else{
                $values .= ','.$_POST[$field];  
            }
        }
    }
    $query_alter = '
        INSERT INTO `caracs_alter_mag`
            (`perso_id`'.$names.', `nb_tour`, `cassable`)
            VALUES ('.$_POST['mat'].$values.', '.$_POST['duree'].', 0)';
    mysql_query($query_alter) or die(mysql_error());

    
    if(isset($_POST['eventId']) && $_POST['eventId'] != 0) {
        $id = (is_numeric($_POST['eventId'])) ? $_POST['eventId'] : 1;
    } else {
        $message = mysql_real_escape_string($_POST['event']);
        $sql = "INSERT INTO evenements_texte(id, texte) VALUES ('','$message')";
        mysql_query($sql);
        $id = mysql_insert_id();
    }

    $evman = new EventManager();

    $ev = $evman->createEvent('anima');
    $ev->setSource($_POST['mat'],eventFormatter::convertType('perso'));
    $ev->infos->addPublicInfo('m',$id);
}
?>

<h2>Bénir/maudir</h2>

<?php 
    if($err != ''){
        echo '<p style="text-align:center;"><strong>'.$err.'</strong></p>';
    }
?>

<form method="post" action="index.php?mat=<?php echo $_GET['mat']?>&amp;p=<?php echo $_GET['p']?>&amp;a=<?php echo $_GET['a']?>" id="form">
    <table class="table-noStyle" >
        <tr>
            <th colspan="4" style="text-align:center;">
                Rechercher
                <hr class="affiHr"/>
            </th>
        </tr>
        <tr>
            <td></td>
            <td>
                Nom
            </td>
            <td>
                : <input type="text" name="search" id="search" />
                <input type="hidden" name="mat" id="mat" value="null"/>
            </td>
            <td></td>
        </tr>
        <tr>
            <th colspan="4" style="text-align:center;">
                Valeurs
                <hr class="affiHr"/>
            </th>
        </tr>
        <tr>
            <td>PA *</td>
            <td>: <input type="text" name="pa" value="0"/></td>
            <td>PV *</td>
            <td>: <input type="text" name="pv" value="0"/></td>
        </tr>
        <tr>
            <td>Mouvement *</td>
            <td>: <input type="text" name="mouv" value="0"/></td>
            <td>Défense</td>
            <td>: <input type="text" name="def" value="0"/></td>
        </tr>
        <tr>
            <td>Attaque</td>
            <td>: <input type="text" name="atq" value="0"/></td>
            <td>Récupération pv</td>
            <td>: <input type="text" name="recup_pv" value="0"/></td>
        </tr>
        <tr>
            <td>Force *</td>
            <td>: <input type="text" name="force" value="0"/></td>
            <td>Perception *</td>
            <td>: <input type="text" name="perception" value="0"/></td>
        </tr>
        <tr>
            <td>Niveau magie *</td>
            <td>: <input type="text" name="nv" value="0"/></td>
            <td>Effet</td>
            <td>: <input type="text" name="effet" value="0"/></td>
        </tr>
        <tr>
            <td>Résistance magique *</td>
            <td>: <input type="text" name="res_mag" value="0"/></td>
            <td>Esquive magique</td>
            <td>: <input type="text" name="esq_mag" value="0"/></td>
        </tr>
        <tr>
            <th colspan="4" style="text-align:center;">
                <hr class="affiHr"/>
            </th>
        </tr>
        <tr>
            <td>Dur&eacute;e</td>
            <td>
                <select name="duree_ctrl" onchange="handleDuree()">
                    <option value="0">Nombre de tours</option>
                    <option value="-1">&Agrave; jamais</option>
                    <option value="-3">Jusqu'à la mort</option>
                    <option value="-2">Jusqu'à la prochaine raz</option>
                </select>
            </td>
            <td colspan="2">
                <input type="number" name="duree" value="1" />
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;">
                <hr class="affiHr"/>
            </td>
        </tr>
        <tr>
            <td>
                Message d'event
            </td>
            <td >
                <select name="eventId">
			<option value="0">Autre</option>
		<?php
			foreach($msgs as $id => $msg) {
				echo '<option value="'.$id.'">'.$msg.'</option>';
			}
		?>
		</select>
            </td>
            <td colspan="2" rowspan="2">
                <textarea name="event" style="width:100%; height:100%;"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;">
                <hr class="affiHr"/>
                <input type="submit" value="valider" />
            </td>
        </tr>
    </table>
</form>
<p>
* : ces valeurs peuvent être en %
</p>
<?php
    $js->addLib('jquery');
    $js->addLib('jquery-ui');
    $js->addLib('validation/jquery.validate.min');
    $js->addLib('validation/localization/messages_fr');
    $js->addScript('autocomplete');
?>
<script type="text/javascript">
    <!--
	var autocomplete_url =  "../ajax/affi.php?sup=<?php echo $_GET['mat'];?>&";

        function handleDuree(){
            var val = jQuery('select[name="duree_ctrl"]').val();
            if(val == 0){
                jQuery('input[name="duree"]').val(1).show();
            }
            else{
                jQuery('input[name="duree"]').val(val).hide();
            }
        }
    -->
</script>
<link rel="stylesheet" href="js/jquery-ui-1.8.17.custom.css" type="text/css" />
