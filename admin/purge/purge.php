<?php

/**
 * DIS MODULE PURGEZ DEPRECATD DATA FRUM TEH DATA BASE.
 * ALL U HAS 2 DO 2 CHANGE SOMETHIN IZ 2 CONFIGURE TEH SCRIPT IN DA CONFIG.FP.INC FILE.
 * 
 * ENJOY :3
 * 
 * @author Kamule
 * @version 1.0
 */

require_once __DIR__ .'/config.php.inc';

if(!isset(SUPER_CRON)){
	require_once __DIR__ . '/../../conf/master.php';
	if(!ControleAcces('admin;at',0)){
		 header('Location: index.php');
		die();
	}
}

echo '<ul>';
$sql = conf\ConnecteurDAO::getInstance('ewo');


/**
 * DELETE SENDD MESAGEZ
 */
$rq = '
	DELETE
	FROM `bals_send`
	WHERE
		`date` <= NOW() - INTERVAL '.$conf[Wordz::MESAGEZ_SENDD].' DAY;';
$nb = $sql->exec($rq);
echo '<li><strong style="color:green;">MESSAGEZ SENDD OK (',$nb,')</strong</li>';
/**
 * DELETE RECEIVD MESAGEZ
 */
$rq = '
	DELETE
	FROM `bals`
	WHERE corps_id IN (
		SELECT id
		FROM `bals_corps`
		WHERE
			`date` <= NOW() - INTERVAL '.$conf[Wordz::MESAGEZ_RECEIVD].' DAY
	)
	AND flag_archive = 0;';
$nb = $sql->exec($rq);
echo '<li><strong style="color:green;">MESSAGEZ RECEIVD OK (',$nb,')</strong</li>';

/**
 * DELETE MOUVEMENTZ
 */
$rq = '
	DELETE
	FROM `evenements`
	WHERE
		`type_ev` = "mouv" AND
		`date_ev` <= NOW() - INTERVAL '.$conf[Wordz::MOUVEMENTS].' DAY;';
$nb = $sql->exec($rq);
echo '<li><strong style="color:green;">MOUVEMENTS OK (',$nb,')</strong</li>';

/**
 * DELETE ACSHUNS
 */
$rq = '
	DELETE
	FROM `evenements`
	WHERE
		(`type_ev` = "attaque" OR `type_ev` = "sort") AND
		`result` != 5 AND
		`date_ev` <= NOW() - INTERVAL '.$conf[Wordz::ACSHUNS].' DAY;';
$nb = $sql->exec($rq);
echo '<li><strong style="color:green;">ACSHUNS OK (',$nb,')</strong</li>';

/**
 * DELETE ANTI CHEAT
 */
$rq = '
	DELETE
	FROM `at_log`
	WHERE
		`date` <= NOW() - INTERVAL '.$conf[Wordz::ANTI_CHEAT].' DAY;';
$nb = $sql->exec($rq);
$rq = '
	DELETE
	FROM `at_log_connexion`
	WHERE
		id NOT IN (
			SELECT `id` FROM `at_log`
		);';
$sql->exec($rq);
echo '<li><strong style="color:green;">ANTI CHEAT OK (',$nb,')</strong</li>';

echo '</ul>';