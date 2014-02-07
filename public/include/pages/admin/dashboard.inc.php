<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if ($bitcoin->can_connect() === true){
  $aGetInfo = $bitcoin->getinfo();
} else {
  $aGetInfo = array('errors' => 'Unable to connect');
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

// Fetch version information
$version['CURRENT'] = array('DB' => DB_VERSION, 'CONFIG' => CONFIG_VERSION, 'CORE' => FAUCET_VERSION);
$version['INSTALLED'] = array('DB' => $setting->getValue('DB_VERSION'), 'CONFIG' => $config['version'], 'CORE' => FAUCET_VERSION);

// Fetch cron information
$aCrons = array('payouts','token_cleanup','user_purge');
// Data array for template
$cron_errors = 0;
$cron_disabled = 0;
foreach ($aCrons as $strCron) {
  $status = $monitoring->getStatus($strCron . '_status');
  if ($status['value'] != 0)
    $cron_errors++;
  if ($monitoring->isDisabled($strCron) == 1)
    $cron_disabled++;
}
$smarty->assign('CRON_ERROR', $cron_errors);
$smarty->assign('CRON_DISABLED', $cron_disabled);

// Fetch user information
$aUserInfo = array(
  'total' => $user->getCount(),
  'locked' => $user->getCountFiltered('is_locked', 1),
  'admins' => $user->getCountFiltered('is_admin', 1),
);
$smarty->assign('USER_INFO', $aUserInfo);

// Fetch login information
$aLoginInfo = array(
  '24hours' => $user->getCountFiltered('last_login', time() - 86400, 'i', '>='),
  '7days' => $user->getCountFiltered('last_login', (time() - (86400 * 7)), 'i', '>='),
  '1month' => $user->getCountFiltered('last_login', (time() - (86400 * 7 * 4)), 'i', '>='),
  '6month' => $user->getCountFiltered('last_login', (time() - (86400 * 7 * 4 * 6)), 'i', '>='),
  '1year' => $user->getCountFiltered('last_login', (time() - (86400 * 365)), 'i', '>=')
);
$smarty->assign('USER_LOGINS', $aLoginInfo);

// Wallet status
$smarty->assign('WALLET_ERROR', $aGetInfo['errors']);

// Tempalte specifics
$smarty->assign('VERSION', $version);
$smarty->assign("CONTENT", "default.tpl");
?>
