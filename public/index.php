<?php

// Set a decently long SECURITY key with special chars etc
define('SECURITY', '*)WT#&YHfd');
// Whether or not to check SECHASH for validity, still checks if SECURITY defined as before if disabled
define('SECHASH_CHECK', false);

// Nothing below here to configure, move along...

// change SECHASH every second, we allow up to 3 sec back for slow servers
if (SECHASH_CHECK) {
  function fip($tr=0) { return md5(SECURITY.(time()-$tr).SECURITY); }
  define('SECHASH', fip());
  function cfip() { return (fip()==SECHASH||fip(1)==SECHASH||fip(2)==SECHASH) ? 1 : 0; }
} else {
  function cfip() { return (@defined('SECURITY')) ? 1 : 0; }
}

// Define Basepath for file directories
define("BASEPATH", dirname(__FILE__) . "/");

// all our includes and config etc are now in bootstrap
include_once('include/bootstrap.php');

// switch to https if config option is enabled
$hts = ($config['https_only'] && (!empty($_SERVER['QUERY_STRING']))) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING'] : "https://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
($config['https_only'] && @!$_SERVER['HTTPS']) ? exit(header("Location: ".$hts)):0;

// version check and config check if not disabled
if (@$_SESSION['USERDATA']['is_admin'] && $user->isAdmin(@$_SESSION['USERDATA']['id'])) {
  require_once(INCLUDE_DIR . '/version.inc.php');
  if (!@$config['skip_config_tests']) {
    require_once(INCLUDE_DIR . '/admin_checks.php');
  }
}

// Load Classes, they name defines the $ variable used
// We include all needed files here, even though our templates could load them themself
require_once(INCLUDE_DIR . '/autoloader.inc.php');

// Create our pages array from existing files
if (is_dir(INCLUDE_DIR . '/pages/')) {
  foreach (glob(INCLUDE_DIR . '/pages/*.inc.php') as $filepath) {
    $filename = basename($filepath);
    $pagename = substr($filename, 0, strlen($filename) - 8);
    $arrPages[$pagename] = $filename;
    $debug->append("Adding $pagename as " . $filename . " to accessible pages", 4);
  }
}

// Set a default action here if no page has been requested
@$_REQUEST['page'] = (is_array($_REQUEST['page']) || !isset($_REQUEST['page'])) ? 'home' : $_REQUEST['page'];
if (isset($_REQUEST['page']) && isset($arrPages[$_REQUEST['page']])) {
  $page = $_REQUEST['page'];
} else if (isset($_REQUEST['page']) && ! isset($arrPages[$_REQUEST['page']])) {
  $page = 'error';
} else {
  $page = 'home';
}

// Create our pages array from existing files
if (is_dir(INCLUDE_DIR . '/pages/' . $page)) {
  foreach (glob(INCLUDE_DIR . '/pages/' . $page . '/*.inc.php') as $filepath) {
    $filename = basename($filepath);
    $pagename = substr($filename, 0, strlen($filename) - 8);
    $arrActions[$pagename] = $filename;
    $debug->append("Adding $pagename as " . $filename . ".inc.php to accessible actions", 4);
  }
}

// Default to empty (nothing) if nothing set or not known
$action = (isset($_REQUEST['action']) && !is_array($_REQUEST['action'])) && isset($arrActions[$_REQUEST['action']]) ? $_REQUEST['action'] : "";

// Check csrf token validity if necessary
if ($config['csrf']['enabled'] && isset($_POST['ctoken']) && !empty($_POST['ctoken']) && !is_array($_POST['ctoken'])) {
  $csrftoken->valid = ($csrftoken->checkBasic($user->getCurrentIP(), $arrPages[$page], $_POST['ctoken'])) ? 1 : 0;
} else if ($config['csrf']['enabled'] && (!@$_POST['ctoken'] || empty($_POST['ctoken']) || is_array($_POST['ctoken']))) {
  $csrftoken->valid = 0;
}
if ($config['csrf']['enabled']) $smarty->assign('CTOKEN', $csrftoken->getBasic($user->getCurrentIP(), $arrPages[$page]));

// Load the page code setting the content for the page OR the page action instead if set
if (!empty($action)) {
  $debug->append('Loading Action: ' . $action . ' -> ' . $arrActions[$action], 1);
  require_once(PAGES_DIR . '/' . $page . '/' . $arrActions[$action]);
} else {
  $debug->append('Loading Page: ' . $page . ' -> ' . $arrPages[$page], 1);
  require_once(PAGES_DIR . '/' . $arrPages[$page]);
}

define('PAGE', $page);
define('ACTION', $action);

// For our content inclusion
$smarty->assign("PAGE", $page);
$smarty->assign("ACTION", $action);

// Now with all loaded and processed, setup some globals we need for smarty templates
if ($page != 'api') require_once(INCLUDE_DIR . '/smarty_globals.inc.php');

// Load debug information into template
$debug->append("Loading debug information into template", 4);
$smarty->assign('DebuggerInfo', $debug->getDebugInfo());
$smarty->assign('RUNTIME', (microtime(true) - $dStartTime) * 1000);

// Display our page
if (!@$supress_master) $smarty->display($master_template, $smarty_cache_key);

// Unset any temporary values here
unset($_SESSION['POPUP']);