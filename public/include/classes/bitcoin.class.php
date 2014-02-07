<?php
// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

require_once(INCLUDE_DIR . "/lib/jsonRPCClient.php");

/**
* Bitcoin client class for access to a Bitcoin server via JSON-RPC-HTTP[S]
*
* Implements the methods documented at https://en.bitcoin.it/wiki/Api
*
* @version 0.3.19
* @author Mike Gogulski
* http://www.gogulski.com/ http://www.nostate.com/
*/
class BitcoinClient extends jsonRPCClient {

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
public function __construct($scheme, $username, $password, $host = "localhost", $debug_level) {
    $scheme = strtolower($type);
    $this->scheme = $scheme;
    $this->username = $username;
    $this->password = $password;
    $this->host = $host;
    $debug_level > 0 ? $debug_level = true : $debug_level = false;
    if ($scheme != "http" && $scheme != "https")
      throw new Exception("Scheme must be http or https");
    if (empty($username))
      throw new Exception("Username must be non-blank");
    if (empty($password))
      throw new Exception("Password must be non-blank");
    return parent::__construct($this->type, $this->username, $this->password, $this->host, '', $debug_level);
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
$bitcoin = new BitcoinClient($config['wallet']['type'], $config['wallet']['username'], $config['wallet']['password'], $config['wallet']['host'], DEBUG);