<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Include markdown library
use \Michelf\Markdown;



if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);

  $smarty->assign("HIDEAUTHOR", $setting->getValue('acl_hide_news_author'));
} else {
  $debug->append('Using cached page', 3);
}
// Load news entries for Desktop site and unauthenticated users
$smarty->assign("CONTENT", "default.tpl");
?>
