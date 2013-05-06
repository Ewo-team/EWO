<?php
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

include(SERVER_ROOT . "/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
?>

<h2>Statistique des navigateurs</h2>
<h3>(Uniquement sur les 1000 derniers enregistrements de l'AT)</h3>

<!-- Debut du coin -->
<div>

			
<div class='news' align='center'>

<table>

<?php
// Paramètres de connexion à la base de données

	$query = "SELECT n.descr as descr, count(*) as nb FROM at_log l 
	INNER JOIN at_log_connexion c ON (c.id = l.compte) 
	INNER JOIN at_navigateur n on (c.navigateur = n.id)
	WHERE l.date > DATE_ADD(NOW(), INTERVAL -1 MONTH)
	GROUP BY n.descr
	ORDER BY n.descr";									
	
$plateform = array();
$browser = array();
$version = array();

$merge = array();			
																			
$resultat = mysql_query ($query) or die (mysql_error());
while ($log = mysql_fetch_array ($resultat)){
	$data = parse_user_agent($log['descr']);

	//$k_plateform = md5($data['platform']);
	$k_plateform = $data['platform'];
	
	//$k_browser = md5($data['browser']);
	$k_browser = $data['browser'];
	
	//$k_version = md5($data['version'] . $data['browser']);
	$k_version = $data['browser'] . '-' . $data['version'];
	
	$k_merge = md5($log['descr']);


	//echo $log['descr'] . ' - ' . $log['nb'] . '<br>' . $data['platform'] . ' - ' . $data['browser'] . ' - ' . $data['version'] . " - $k_plateform - $k_browser - $k_version - $k_merge<br><br>";


	$cpt = (isset($plateform[$k_plateform]['nb'])) ? $plateform[$k_plateform]['nb']+$log['nb'] : $log['nb'];
	$plateform[$k_plateform] = array('data' => $data['platform'], 'nb' => $cpt);

	$cpt = (isset($browser[$k_browser]['nb'])) ? $browser[$k_browser]['nb']+$log['nb'] : $log['nb'];
	$browser[$k_browser] = array('data' => $data['browser'], 'nb' => $cpt);

	$cpt = (isset($version[$k_version]['nb'])) ? $version[$k_version]['nb']+$log['nb'] : $log['nb'];
	$version[$k_version] = array('data1' => $data['browser'], 'data2' => $data['version'], 'nb' => $cpt);

	$cpt = (isset($merge[$k_merge]['nb'])) ? $merge[$k_merge]['nb']+$log['nb'] : $log['nb'];
	$merge[$k_merge] = array('data' => $log['descr'], 'nb' => $cpt);		
} 

sort($plateform);
sort($browser);
sort($version);

echo '<table><legend>Plateformes</legend><tr><th>Plateforme</th><th>Nombre</th></tr>';
foreach ($plateform as $value) {
	echo '<tr>';
	echo '<td>'.$value['data'].'</td>';
	echo '<td>'.$value['nb'].'</td>';
	echo '</tr>';
}
echo '</table>';

echo '<table><legend>Navigateurs</legend><tr><th>Nabigateur</th><th>Nombre</th></tr>';
foreach ($browser as $value) {
	echo '<tr>';
	echo '<td>'.$value['data'].'</td>';
	echo '<td>'.$value['nb'].'</td>';
	echo '</tr>';
}
echo '</table>';

echo '<table><legend>Version des navigateurs</legend><tr><th>Navigateur</th><th>Version</th><th>Nombre</th></tr>';
foreach ($version as $value) {
	echo '<tr>';
	echo '<td>'.$value['data1'].'</td>';
	echo '<td>'.$value['data2'].'</td>';
	echo '<td>'.$value['nb'].'</td>';
	echo '</tr>';
}
echo '</table>';

echo '<table><legend>Environnements</legend><tr><th>Environnement</th><th>Nombre</th></tr>';
foreach ($merge as $value) {
	echo '<tr>';
	echo '<td>'.$value['data'].'</td>';
	echo '<td>'.$value['nb'].'</td>';
	echo '</tr>';
}
echo '</table>';

?>
</table>
</div>


</div>
<!-- Fin du coin -->

<?php
//-- Footer --
include(SERVER_ROOT . "/template/footer_new.php");


function parse_user_agent( $u_agent = null ) { 
    if(is_null($u_agent) && isset($_SERVER['HTTP_USER_AGENT'])) $u_agent = $_SERVER['HTTP_USER_AGENT'];

    $data = array(
        'platform' => null,
        'browser'  => null,
        'version'  => null,
    );
    
    if(!$u_agent) return $data;
    
    if( preg_match('/\((.*?)\)/im', $u_agent, $regs) ) {

        preg_match_all('/(?P<platform>Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows(\ Phone\ OS)?|Silk|linux-gnu|BlackBerry|Nintendo\ (WiiU?|3DS)|Xbox)
            (?:\ [^;]*)?
            (?:;|$)/imx', $regs[1], $result, PREG_PATTERN_ORDER);

        $priority = array('Android', 'Xbox');
        $result['platform'] = array_unique($result['platform']);
        if( count($result['platform']) > 1 ) {
            if( $keys = array_intersect($priority, $result['platform']) ) {
                $data['platform'] = reset($keys);
            }else{
                $data['platform'] = $result['platform'][0];
            }
        }elseif(isset($result['platform'][0])){
            $data['platform'] = $result['platform'][0];
        }
    }

    if( $data['platform'] == 'linux-gnu' ) { $data['platform'] = 'Linux'; }
    if( $data['platform'] == 'CrOS' ) { $data['platform'] = 'Chrome OS'; }

    preg_match_all('%(?P<browser>Camino|Kindle(\ Fire\ Build)?|Firefox|Safari|MSIE|AppleWebKit|Chrome|IEMobile|Opera|Silk|Lynx|Version|Wget|curl|NintendoBrowser|PLAYSTATION\ \d+)
            (?:;?)
            (?:(?:[/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%x', 
    $u_agent, $result, PREG_PATTERN_ORDER);

    $key = 0;

    $data['browser'] = $result['browser'][0];
    $data['version'] = $result['version'][0];

    if( ($key = array_search( 'Kindle Fire Build', $result['browser'] )) !== false || ($key = array_search( 'Silk', $result['browser'] )) !== false ) {
        $data['browser']  = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
        $data['platform'] = 'Kindle Fire';
        if( !($data['version'] = $result['version'][$key]) || !is_numeric($data['version'][0]) ) {
            $data['version'] = $result['version'][array_search( 'Version', $result['browser'] )];
        }
    }elseif( ($key = array_search( 'NintendoBrowser', $result['browser'] )) !== false || $data['platform'] == 'Nintendo 3DS' ) {
        $data['browser']  = 'NintendoBrowser';
        $data['version']  = $result['version'][$key];
    }elseif( ($key = array_search( 'Kindle', $result['browser'] )) !== false ) {
        $data['browser']  = $result['browser'][$key];
        $data['platform'] = 'Kindle';
        $data['version']  = $result['version'][$key];
    }elseif( $result['browser'][0] == 'AppleWebKit' ) {
        if( ( $data['platform'] == 'Android' && !($key = 0) ) || $key = array_search( 'Chrome', $result['browser'] ) ) {
            $data['browser'] = 'Chrome';
            if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
        }elseif( $data['platform'] == 'BlackBerry' ) {
            $data['browser'] = 'BlackBerry Browser';
            if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
        }elseif( $key = array_search( 'Safari', $result['browser'] ) ) {
            $data['browser'] = 'Safari';
            if( ($vkey = array_search( 'Version', $result['browser'] )) !== false ) { $key = $vkey; }
        }
        
        $data['version'] = $result['version'][$key];
    }elseif( ($key = array_search( 'Opera', $result['browser'] )) !== false ) {
        $data['browser'] = $result['browser'][$key];
        $data['version'] = $result['version'][$key];
        if( ($key = array_search( 'Version', $result['browser'] )) !== false ) { $data['version'] = $result['version'][$key]; }
    }elseif( $result['browser'][0] == 'MSIE' ){
        if( $key = array_search( 'IEMobile', $result['browser'] ) ) {
            $data['browser'] = 'IEMobile';
        }else{
            $data['browser'] = 'MSIE';
            $key = 0;
        }
        $data['version'] = $result['version'][$key];
    }elseif( $key = array_search( 'PLAYSTATION 3', $result['browser'] ) !== false ) {
        $data['platform'] = 'PLAYSTATION 3';
        $data['browser']  = 'NetFront';
    }

    return $data;

}


//------------
?>
