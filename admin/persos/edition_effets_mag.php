<?php
session_start(); 
$root_url = "./../..";
//-- Header --
include($root_url."/conf/master.php");
include($root_url."/admin/AdminDAO.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);

$dao = AdminDAO::getInstance();

if(isset($_POST['uid'])) {
    
    $uid = $_POST['uid'];
    $del = null;
    $mod = null;
    
    foreach($uid as $key => $value)
    {
        try {
            
            $supp = (isset($_POST['suppression'][$key])) ? 1 : 0;
            
            if($supp == 1) {
                $del[$key] = $value;
            } else {
                $mod[$key]['uid'] = $value;
                $mod[$key]['pa'] = $_POST['pa'][$key];
                $mod[$key]['pv'] = $_POST['pv'][$key];
                $mod[$key]['mouv'] = $_POST['mouv'][$key];
                $mod[$key]['def'] = $_POST['def'][$key];
                $mod[$key]['att'] = $_POST['att'][$key];
                $mod[$key]['recup'] = $_POST['recup'][$key];
                $mod[$key]['force'] = $_POST['force'][$key];
                $mod[$key]['percept'] = $_POST['percept'][$key];
                $mod[$key]['nivmag'] = $_POST['nivmag'][$key];
                $mod[$key]['resmag'] = $_POST['resmag'][$key];
                $mod[$key]['esqmag'] = $_POST['esqmag'][$key];
                $mod[$key]['resphy'] = $_POST['resphy'][$key];
                $mod[$key]['nbtour'] = $_POST['nbtour'][$key];
                $mod[$key]['casse'] = (isset($_POST['cassable'][$key])) ? 1 : 0;
                $mod[$key]['dissip'] = (isset($_POST['dissipe_mort'][$key])) ? 1 : 0;
            }
            
        } catch(Exception $e) {
            //Nothing to do
        }
    } 
    
    if($mod != null) {
        //print_r($mod);
        $dao->ModifieAlteration($mod);
    }
    
    if($del != null) {
        //print_r($del);
        $dao->SupprimeAlteration($del);
    }
}

if(isset($_POST['pa_new']) && $_POST['pa_new'] != null && isset($_POST['id_perso'])) {
    
    $add['pa'] = $_POST['pa_new'];
    $add['pv'] = $_POST['pv_new'];
    $add['mouv'] = $_POST['mouv_new'];
    $add['def'] = $_POST['def_new'];
    $add['att'] = $_POST['att_new'];
    $add['recup'] = $_POST['recup_new'];
    $add['force'] = $_POST['force_new'];
    $add['percept'] = $_POST['percept_new'];
    $add['nivmag'] = $_POST['nivmag_new'];
    $add['resmag'] = $_POST['resmag_new'];
    $add['esqmag'] = $_POST['esqmag_new'];
    $add['resphy'] = $_POST['resphy_new'];
    $add['nbtour'] = $_POST['nbtour_new'];
    $add['casse'] = (isset($_POST['cassable_new'])) ? 1 : 0;
    $add['dissip'] = (isset($_POST['dissipemort_new'])) ? 1 : 0;        
    
    $dao->AjouteAlteration($_POST['id_perso'], $add);
}

echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;
