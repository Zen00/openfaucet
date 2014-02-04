<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class User extends Base {
  protected $table = 'users';
  
  /**
   * Log the information from a user faucet request
   **/
  public function logUser() {
    $userIP = getCurrentIP();
    $userAddress = $_POST['userAddress'];
    $mysqli->bind_param('ss',$userAddress,$userIP);
    $mysqli->prepare("INSERT INTO $this->table (user_address, user_ip) VALUES (?,?)");
}
  
  /**
   * Fetch users coin address
   * @param userID int UserID
   * @return data string Coin Address
   **/
  public function getCoinAddress($userID) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($userID, 'user_address', 'id');
}

  public function getUserIP($userIP) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($userIP, 'user_IP', 'id');
}
  
  /**
   * Convenience function to get IP address, no params is the same as REMOTE_ADDR
   * @param trustremote bool must be FALSE to checkclient or checkforwarded
   * @param checkclient bool check HTTP_CLIENT_IP for a valid ip first
   * @param checkforwarded bool check HTTP_X_FORWARDED_FOR for a valid ip first
   * @return string IP address
   */
  public function getCurrentIP($trustremote=true, $checkclient=false, $checkforwarded=false) {
    $client = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : false;
    $fwd = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : false;
    $remote = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : @$_SERVER['REMOTE_ADDR'];
    // shared internet
    if (filter_var($client, FILTER_VALIDATE_IP) && !$trustremote && $checkclient) {
      return $client;
    } else if (strpos($fwd, ',') !== false && !$trustremote && $checkforwarded) {
      // multiple proxies
      $ips = explode(',', $fwd);
      $path = array();
      foreach ($ips as $ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
          $path[] = $ip;
        }
      }
      return array_pop($path);
    } else if (filter_var($fwd, FILTER_VALIDATE_IP) && !$trustremote && $checkforwarded) {
      // single
      return $fwd;
    } else {
      // as usual
      return $remote;
    }
  }
}

// Make our class available automatically
$faucetusers = new Faucetuser();
$faucetusers->setMysql($mysqli);
$faucetusers->setDebug($debug);
$faucetusers->setErrorCodes($aErrorCodes);