<?php
(SECURITY == "*)WT#&YHfd" && SECHASH_CHECK) ? die("public/index.php -> Set a new SECURITY value to continue") : 0;
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;


// Default classes
require_once(INCLUDE_DIR . '/lib/KLogger.php');
require_once(CLASS_DIR . '/logger.class.php');
require_once(CLASS_DIR . '/debug.class.php');
if ($config['mysql_filter']) {
  require_once(CLASS_DIR . '/strict.class.php');
}
require_once(INCLUDE_DIR . '/database.inc.php');
require_once(INCLUDE_DIR . '/config/error_codes.inc.php');

// Load these two first
require_once(CLASS_DIR . '/base.class.php');
require_once(CLASS_DIR . '/setting.class.php');

// We need this one in here to properly set our theme
require_once(INCLUDE_DIR . '/lib/Mobile_Detect.php');

/ Detect device
if ($detect->isMobile() && $setting->getValue('website_mobile_theme')) {
  // Set to mobile theme
  $setting->getValue('website_mobile_theme') ? $theme = $setting->getValue('website_mobile_theme') : $theme = 'mobile';
} else if ( PHP_SAPI == 'cli') {
  // Create a new compile folder just for crons
  // We call mail templates directly anyway
  $theme = 'cron';
} else {
  // Use configured theme, fallback to default theme
  $setting->getValue('website_theme') ? $theme = $setting->getValue('website_theme') : $theme = 'mpos';
}
define('THEME', $theme);

//Required for Smarty
require_once(CLASS_DIR . '/template.class.php');
// Load smarty now that we have our theme defined
require_once(INCLUDE_DIR . '/smarty.inc.php');

// Load everything else in proper order
require_once(CLASS_DIR . '/mail.class.php');
require_once(CLASS_DIR . '/tokentype.class.php');
require_once(CLASS_DIR . '/token.class.php');
require_once(CLASS_DIR . '/faucetpayout.class.php');

// We require the block class to properly grab the round ID
require_once(CLASS_DIR . '/bitcoin.class.php');
require_once(CLASS_DIR . '/monitoring.class.php');
require_once(CLASS_DIR . '/user.class.php');
require_once(CLASS_DIR . '/faucetusers.class.php');
require_once(CLASS_DIR . '/csrftoken.class.php');
require_once(CLASS_DIR . '/faucettransaction.class.php');
require_once(CLASS_DIR . '/news.class.php');
require_once(INCLUDE_DIR . '/lib/Michelf/Markdown.php');

// Include our versions
require_once(INCLUDE_DIR . '/version.inc.php');