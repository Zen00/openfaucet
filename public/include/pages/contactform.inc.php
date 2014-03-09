<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($setting->getValue('disable_contactform')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Contactform is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "empty");
} else {
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}