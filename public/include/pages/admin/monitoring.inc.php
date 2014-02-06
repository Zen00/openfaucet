<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Default crons to monitor
$aCrons = array('faucetpayouts','token_cleanup','user_purge');

// Data array for template
foreach ($aCrons as $strCron) {
  $aCronStatus[$strCron] = array(
    'disabled' => $monitoring->getStatus($strCron . '_disabled'),
    'exit' => $monitoring->getStatus($strCron . '_status'),
    'active' => $monitoring->getStatus($strCron . '_active'),
    'runtime' => $monitoring->getStatus($strCron . '_runtime'),
    'starttime' => $monitoring->getStatus($strCron . '_starttime'),
    'endtime' => $monitoring->getStatus($strCron . '_endtime'),
    'message' => $monitoring->getStatus($strCron . '_message'),
  );
}
$smarty->assign("CRONSTATUS", $aCronStatus);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
