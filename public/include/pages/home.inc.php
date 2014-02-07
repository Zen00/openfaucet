<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Include markdown library
use \Michelf\Markdown;

// Display the payout amount
$smarty->assign("PAYOUT", $config['payout']);

// Log the user
if(isset($_POST['userAddress']) && $_POST['userAddress'] !== '') {
    $faucetusers->logUser();
    unset($_POST['userAddress']);
}

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  // Fetch active news to display
  $aNews = $news->getAllActive();
  if (is_array($aNews)) {
    foreach ($aNews as $key => $aData) {
      // Transform Markdown content to HTML
      $aNews[$key]['content'] = Markdown::defaultTransform($aData['content']);
    }
  }

  $smarty->assign("HIDEAUTHOR", $setting->getValue('hide_news_author'));
  $smarty->assign("NEWS", $aNews);
} else {
  $debug->append('Using cached page', 3);
}
// Load news entries for Desktop site and unauthenticated users
$smarty->assign("CONTENT", "default.tpl");