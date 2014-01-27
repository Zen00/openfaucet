<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

/**
* Do not edit this unless you have confirmed that your config has been updated!
* This is used in the version check to ensure you run the latest version of the configuration file.
* Once you upgraded your config, change the version here too.
**/
$config['version'] = '0.0.1';

// Our include directory for additional features
define('INCLUDE_DIR', BASEPATH . 'include');

// Our class directory
define('CLASS_DIR', INCLUDE_DIR . '/classes');

// Our pages directory which takes care of
define('PAGES_DIR', INCLUDE_DIR . '/pages');

// Our theme folder holding all themes
define('THEME_DIR', BASEPATH . 'templates');

// Set debugging level for our debug class
// Values valid from 0 (disabled) to 5 (most verbose)
define('DEBUG', 0);

/**
* Database configuration
*
* A MySQL database backend is required for MPOS.
* Also ensure the database structure is imported!
* The SQL file should be included in this project under the `sql` directory
*
* Default:
* host = 'localhost'
* user = 'someuser'
* pass = 'somepass'
* port = 3306
* name = 'water'
**/

$config['db']['host'] = 'localhost';
$config['db']['user'] = 'someuser';
$config['db']['pass'] = 'somepass';
$config['db']['port'] = 3306;
$config['db']['name'] = 'water';

/**
* Local wallet RPC configuration
*
* MPOS uses the RPC backend to fetch transactions, blocks
* and various other things. They need to match your coind RPC
* configuration.
*
* Default:
* type = 'http'
* host = 'localhost:19334'
* username = 'testnet'
* password = 'testnet'
**/
$config['wallet']['type'] = 'http';
$config['wallet']['host'] = 'localhost:19334';
$config['wallet']['username'] = 'someuser';
$config['wallet']['password'] = 'somepass';
?>