<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('maintenance') && !$user->isAdmin($user->getUserIdByEmail($_POST['username']))) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'You are not allowed to login during maintenace.', 'TYPE' => 'info');
} else if (!empty($_POST['username']) && !empty($_POST['password'])) {
  // Process form data if valid
    if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
      if ($user->checkLogin(@$_POST['username'], @$_POST['password']) ) {
        $port = ($_SERVER["SERVER_PORT"] == "80" or $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
        $location = @$_SERVER['HTTPS'] === true ? 'https://' : 'http://';
        $location .= $_SERVER['SERVER_NAME'] . $port . $_SERVER['SCRIPT_NAME'] . '?page=index.php';
        if (!headers_sent()) header('Location: ' . $location);
        	exit('<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($location) . '"/>');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '.$user->getError(), 'TYPE' => 'errormsg');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
    }
}

// Load login template
$smarty->assign('CONTENT', 'default.tpl');