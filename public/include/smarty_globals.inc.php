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
  'config' => array(
    'recaptcha_enabled' => $setting->getValue('recaptcha_enabled'),
    'recaptcha_enabled_logins' => $setting->getValue('recaptcha_enabled_logins'),
    'disable_navbar' => $setting->getValue('disable_navbar'),
    'disable_navbar_api' => $setting->getValue('disable_navbar_api'),
    'accounts' => $config['accounts'],
   )
);

// Website configurations
$aGlobal['website']['name'] = $setting->getValue('website_name');
$aGlobal['website']['title'] = $setting->getValue('website_title');
$aGlobal['website']['slogan'] = $setting->getValue('website_slogan');
$aGlobal['website']['email'] = $setting->getValue('website_email');
$aGlobal['website']['api']['disabled'] = $setting->getValue('disable_api');
$aGlobal['website']['blockexplorer']['disabled'] = $setting->getValue('website_blockexplorer_disabled');
$aGlobal['website']['transactionexplorer']['disabled'] = $setting->getValue('website_transactionexplorer_disabled');
$aGlobal['website']['chaininfo']['disabled'] = $setting->getValue('website_chaininfo_disabled');
$aGlobal['website']['donors']['disabled'] = $setting->getValue('disable_donors');
$aGlobal['website']['about']['disabled'] = $setting->getValue('disable_about');
$setting->getValue('website_blockexplorer_url') ? $aGlobal['website']['blockexplorer']['url'] = $setting->getValue('website_blockexplorer_url') : $aGlobal['website']['blockexplorer']['url'] = 'http://explorer.litecoin.net/block/';
$setting->getValue('website_transactionexplorer_url') ? $aGlobal['website']['transactionexplorer']['url'] = $setting->getValue('website_transactionexplorer_url') : $aGlobal['website']['transactionexplorer']['url'] = 'http://explorer.litecoin.net/tx/';
$setting->getValue('website_chaininfo_url') ? $aGlobal['website']['chaininfo']['url'] = $setting->getValue('website_chaininfo_url') : $aGlobal['website']['chaininfo']['url'] = 'http://allchains.info';

// Google Analytics
$aGlobal['statistics']['analytics']['enabled'] = $setting->getValue('statistics_analytics_enabled');
$aGlobal['statistics']['analytics']['code'] = $setting->getValue('statistics_analytics_code');

// ACLs
$aGlobal['acl']['pool']['statistics'] = $setting->getValue('acl_pool_statistics');
$aGlobal['acl']['block']['statistics'] = $setting->getValue('acl_block_statistics');
$aGlobal['acl']['round']['statistics'] = $setting->getValue('acl_round_statistics');
$aGlobal['acl']['blockfinder']['statistics'] = $setting->getValue('acl_blockfinder_statistics');
$aGlobal['acl']['uptime']['statistics'] = $setting->getValue('acl_uptime_statistics');

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
