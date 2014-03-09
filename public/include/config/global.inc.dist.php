<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * Do not edit this unless you have confirmed that your config has been updated!
 * This is used in the version check to ensure you run the latest version of the configuration file.
 * Once you upgraded your config, change the version here too.
 **/
$config['version'] = '0.0.1';

/**
* Unless you disable this, we'll do a quick check on your config first.
* https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-config-check
*/
$config['skip_config_tests'] = false;

// Set debugging level for our debug class
// Values valid from 0 (disabled) to 5 (most verbose)
$config['DEBUG'] = 0;

// SALT used to hash passwords
$config['SALT'] = 'PLEASEMAKEMESOMETHINGRANDOM';
$config['SALTY'] = 'THISSHOULDALSOBERRAANNDDOOM';

/**
 * Database configuration
 *
 * A MySQL database backend is required for MPOS.
 * Also ensure the database structure is imported!
 * The SQL file should be included in this project under the `sql` directory
 *
 * Default:
 *   host     =  'localhost'
 *   port     =  3306
 *   user     =  'someuser'
 *   pass     =  'somepass'
 *   name     =  'mpos'
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
 *   type      =  'http'
 *   host      =  'localhost:19334'
 *   username  =  'testnet'
 *   password  =  'testnet'
 **/
$config['wallet']['type'] = 'http';
$config['wallet']['host'] = 'localhost:19334';
$config['wallet']['username'] = 'testnet';
$config['wallet']['password'] = 'testnet';

/**
 * Payout of liquid assets
 *
 * Explanation:
 *   Running pools, especially those with active fees, will build up a good
 *   amount of liquid assets that can be used by pool operators. If you wish
 *   to automatically send your assets to a offline wallet, set your account
 *   address, reserves and thresholds here.
 *
 * Options:
 *   address    :  The address of the wallet to the address you'd like to receive the coins in
 *   reserve    :  The amount you'd like to remain in the wallet. Recommended is at least 1 block value
 *   threshold  :  The amount of coins you'd like to send per batch minimum. Once exceeded, this is sent
 *                 to the offline wallet address specified.
 * Default:
 *   addresss   :  empty
 *   reserve    :  50
 *   threshold  :  25
 **/
$config['coldwallet']['address'] = '';
$config['coldwallet']['reserve'] = 50;
$config['coldwallet']['threshold'] = 5;

/**
 * Amount to give users
 *
 * Explanation:
 *   Set this to the amount you want people to recieve of your coin on each payout request
 *
 * Default:
 *   payout    :  1.0
 *
 */
$config['payout'] = 1.0;

// Currency system used in this pool, default: `LTC`
$config['currency'] = 'LTC';

// Coins transaction time for confirmation
$config['confirmations'] = 120;

/**
 * Cookie configuration
 *
 * You can configure the cookie behaviour to secure your cookies more than the PHP defaults
 *
 * For multiple installations of MPOS on the same domain you must change the cookie path.
 *
 * Explanation:
 * duration:
 *   the amount of time, in seconds, that a cookie should persist in the users browser.
 *   0 = until closed; 1440 = 24 minutes. Check your php.ini 'session.gc_maxlifetime' value
 *   and ensure that it is at least the duration specified here.
 *
 * domain:
 *   the only domain name that may access this cookie in the browser
 *
 * path:
 *   the highest path on the domain that can access this cookie; i.e. if running two pools
 *   from a single domain you might set the path /ltc/ and /ftc/ to separate user session
 *   cookies between the two.
 *
 * httponly:
 *   marks the cookie as accessible only through the HTTP protocol. The cookie can't be
 *   accessed by scripting languages, such as JavaScript. This can help to reduce identity
 *   theft through XSS attacks in most browsers.
 *
 * secure:
 *   marks the cookie as accessible only through the HTTPS protocol. If you have a SSL
 *   certificate installed on your domain name then this will stop a user accidentally
 *   accessing the site over a HTTP connection, without SSL, exposing their session cookie.
 *
 * Default:
 *   duration = '1440'
 *   domain   = ''
 *   path     = '/'
 *   httponly = true
 *   secure   = false
 **/
$config['cookie']['duration'] = '1440';
$config['cookie']['domain'] = '';
$config['cookie']['path'] = '/';
$config['cookie']['httponly'] = true;
$config['cookie']['secure'] = false;

/**
 * Enable or disable the Smarty cache
 *
 * Explanation:
 *   Smarty implements a file based cache for all HTML output generated
 *   from dynamic scripts. It can be enabled to cache the HTML data on disk,
 *   future request are served from those cache files.
 *
 *   This may or may not work as expected, in general Memcache is used to cache
 *   all data so rendering the page should not take too long anyway.
 *
 *   You can test this out and enable (1) this setting but it's not guaranteed to
 *   work with MPOS.
 *
 *   Ensure that the folder `templates/cache` is writeable by the web server!
 *
 *   cache           =  Enable/Disable the cache
 *   cache_lifetime  =  Time to keep files in seconds before updating them
 *
 *  Options:
 *    cache:
 *      0  =  disabled
 *      1  =  enabled
 *    cache_lifetime:
 *      time in seconds
 *
 *  Defaults:
 *    cache           =  0, disabled
 *    cache_lifetime  =  30 seconds
 **/
$config['smarty']['cache'] = 0;
$config['smarty']['cache_lifetime'] = 30;

/**
 * System load setting
 *
 * This will disable loading of some API calls in case the system
 * loads exceeds the defined max setting. Useful to temporarily suspend
 * live statistics on a server that is too busy to deal with requests.
 *
 * Options
 *   max    =  float, maximum system load
 *
 * Defaults:
 *   max    =  10.0
 **/
$config['system']['load']['max'] = 10.0;