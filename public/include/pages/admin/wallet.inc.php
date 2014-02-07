<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($bitcoin->can_connect() === true){
    $dBalance = $bitcoin->getbalance();
    $aGetInfo = $bitcoin->getinfo();
    if (is_array($aGetInfo) && array_key_exists('newmint', $aGetInfo)) {
      $dNewmint = $aGetInfo['newmint'];
    } else {
      $dNewmint = -1;
    }
  } else {
    $aGetInfo = array('errors' => 'Unable to connect');
    $dBalance = 0;
    $dNewmint = -1;
    $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
  }

  // Cold wallet balance
  if (! $dColdCoins = $setting->getValue('wallet_cold_coins')) $dColdCoins = 0;
  $smarty->assign("BALANCE", $dBalance);
  $smarty->assign("COLDCOINS", $dColdCoins);
  $smarty->assign("NEWMINT", $dNewmint);
  $smarty->assign("COININFO", $aGetInfo);

  // Tempalte specifics
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign("CONTENT", "default.tpl");
?>
