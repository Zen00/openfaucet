<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Faucetusers extends Base {
  protected $table = 'users';
  
  /**
   * Log the information from a user faucet request
   **/
  public function logUser() {
    $userIP = $this->user->getCurrentIP();
    $userAddress = $_POST['userAddress'];
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (user_address, user_ip) VALUES (?,?)");
    $stmt->bind_param('ss',$userAddress,$userIP);
    $stmt->execute();
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
  
}

// Make our class available automatically
$faucetusers = new Faucetusers();
$faucetusers->setMysql($mysqli);
$faucetusers->setDebug($debug);
$faucetusers->setErrorCodes($aErrorCodes);