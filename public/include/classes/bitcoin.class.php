<?php
// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

require_once(INCLUDE_DIR . "/lib/jsonRPCClient.php");

class Bitcoin extends jsonRPCClient {

  /**
* Create a jsonrpc_client object to talk to the bitcoin server and return it,
* or false on failure.
*
* @param string $scheme
* "http" or "https"
* @param string $username
* User name to use in connection the Bitcoin server's JSON-RPC interface
* @param string $password
* Server password
* @param string $address
* Server hostname or IP address
* @param mixed $port
* Server port (string or integer)
* @param string $certificate_path
* Path on the local filesystem to server's PEM certificate (ignored if $scheme != "https")
* @param integer $debug_level
* 0 (default) = no debugging;
* 1 = echo JSON-RPC messages received to stdout;
* 2 = log transmitted messages also
* @return jsonrpc_client
* @access public
* @throws BitcoinClientException
*/

  public function __construct($scheme, $username, $password, $address = "localhost", $certificate_path = '', $debug = false) {
    $scheme = strtolower($scheme);
    $debug > 0 ? $debug = true : $debug = false;
    if ($scheme != "http" && $scheme != "https")
      throw new Exception("Scheme must be http or https");
    if (empty($username))
      throw new Exception("Username must be non-blank");
    if (empty($password))
      throw new Exception("Password must be non-blank");
    if (!empty($certificate_path) && !is_readable($certificate_path))
      throw new Exception("Certificate file " . $certificate_path . " is not readable");
    $uri = $scheme . "://" . $username . ":" . $password . "@" . $address . "/";
    parent::__construct($uri, $debug);
  }
  
/**
* Test if the connection to the Bitcoin JSON-RPC server is working
*
* The check is done by calling the server's getinfo() method and checking
* for a fault.
*
* @return mixed boolean TRUE if successful, or a fault string otherwise
* @access public
* @throws none
*/
  public function can_connect() {
    try {
      $r = parent::getinfo();
    } catch (Exception $e) {
      return $e->getMessage();
    }
    return true;
  }

  public function validateaddress($coin_address) {
    try {
      $aStatus = parent::validateaddress($coin_address);
      if (!$aStatus['isvalid']) {
        return false;
      }
    } catch (Exception $e) {
      return false;
    }
    return true;
  }
}

// Load this wrapper
$bitcoin = new Bitcoin($config['wallet']['type'], $config['wallet']['username'], $config['wallet']['password'], $config['wallet']['host'], DEBUG);