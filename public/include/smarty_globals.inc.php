<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

$debug->append('No cached page detected, loading smarty globals', 3);
// Defaults to get rid of PHP Notice warnings
$dDifficulty = 1;

// Global data for Smarty
$aGlobal = array(
  'confirmations' => $config['confirmations'],
  'twofactor' => $config['twofactor'],
  'csrf' => $config['csrf'],
  'config' => array(
    'disable_payouts' => $setting->getValue('disable_payouts'),
    'disable_contactform' => $setting->getValue('disable_contactform'),
    'monitoring_uptimerobot_api_keys' => $setting->getValue('monitoring_uptimerobot_api_keys'),
    'currency' => $config['currency'],
    'payout' => $config['payout'],
  )
);

// Website configurations
$aGlobal['website']['name'] = $setting->getValue('website_name');
$aGlobal['website']['title'] = $setting->getValue('website_title');
$aGlobal['website']['slogan'] = $setting->getValue('website_slogan');
$aGlobal['website']['email'] = $setting->getValue('website_email');
$aGlobal['website']['about']['disabled'] = $setting->getValue('disable_about');

// Google Analytics
$aGlobal['statistics']['analytics']['enabled'] = $setting->getValue('statistics_analytics_enabled');
$aGlobal['statistics']['analytics']['code'] = $setting->getValue('statistics_analytics_code');

// We don't want these session infos cached
if (@$_SESSION['USERDATA']['id']) {
  $aGlobal['userdata'] = $_SESSION['USERDATA']['id'] ? $user->getUserData($_SESSION['USERDATA']['id']) : array();

  // Site-wide notifications, based on user events
  if ($user->getUserFailed($_SESSION['USERDATA']['id']) > 0)
    $_SESSION['POPUP'][] = array('CONTENT' => 'You have ' . $user->getUserFailed($_SESSION['USERDATA']['id']) . ' failed login attempts! <a href="?page=account&action=reset_failed">Reset Counter</a>', 'TYPE' => 'errormsg');
}

if ($setting->getValue('maintenance'))
  $_SESSION['POPUP'][] = array('CONTENT' => 'This pool is currently in maintenance mode.', 'TYPE' => 'warning');
if ($motd = $setting->getValue('system_motd'))
  $_SESSION['POPUP'][] = array('CONTENT' => $motd, 'TYPE' => 'info');

// So we can display additional info
$smarty->assign('DEBUG', DEBUG);

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBALASSETS', 'site_assets/global');
$smarty->assign('GLOBAL', $aGlobal);
?>
