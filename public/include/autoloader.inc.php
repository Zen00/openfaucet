<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Default classes
require_once(CLASS_DIR . '/debug.class.php');
require_once(INCLUDE_DIR . '/lib/KLogger.php');
require_once(INCLUDE_DIR . '/database.inc.php');
require_once(INCLUDE_DIR . '/config/error_codes.inc.php');

// We need to load these two first
require_once(CLASS_DIR . '/base.class.php');
require_once(CLASS_DIR . '/setting.class.php');

// We need this one in here to properly set our theme
require_once(INCLUDE_DIR . '/lib/Mobile_Detect.php');

// Detect device
if ($detect->isMobile() && $setting->getValue('website_mobile_theme')) {
  // Set to mobile theme
  $setting->getValue('website_mobile_theme') ? $theme = $setting->getValue('website_mobile_theme') : $theme = 'mobile';
} else {
  // Use configured theme, fallback to default theme
  $setting->getValue('website_theme') ? $theme = $setting->getValue('website_theme') : $theme = 'faucet';
}
define('THEME', $theme);

//Required for Smarty
require_once(CLASS_DIR . '/template.class.php');
// Load smarty now that we have our theme defined
require_once(INCLUDE_DIR . '/smarty.inc.php');

// Load everything else in proper order
require_once(CLASS_DIR . '/bitcoin.class.php');
require_once(CLASS_DIR . '/bitcoinwrapper.class.php');
require_once(CLASS_DIR . '/transaction.class.php');
require_once(CLASS_DIR . '/news.class.php');
require_once(INCLUDE_DIR . '/lib/Michelf/Markdown.php');

// Include our versions
require_once(INCLUDE_DIR . '/version.inc.php');

?>