<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($setting->getValue('lock_registration')) {
	$_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
	$smarty->assign("CONTENT", "disabled.tpl");
} else {
	// Load news entries for Desktop site and unauthenticated users
	$smarty->assign("CONTENT", "default.tpl");
}