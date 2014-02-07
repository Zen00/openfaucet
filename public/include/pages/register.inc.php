<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
	die('Hacking attempt');

if ($setting->getValue('lock_registration')) {
	$_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
	$smarty->assign("CONTENT", "disabled.tpl");
} else {
	
	// Load news entries for Desktop site and unauthenticated users
	$smarty->assign("CONTENT", "default.tpl");
}