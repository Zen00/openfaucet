<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

/**
 * We use a wrapper class around BitcoinClient to add
 * some basic caching functionality and some debugging
 **/
class BitcoinWrapper extends BitcoinClient {
  public function __construct($type, $username, $password, $host, $debug_level, $debug_object) {
    $this->type = $type;
    $this->username = $username;
    $this->password = $password;
    $this->host = $host;
    // $this->debug is already used
    $this->oDebug = $debug_object;
    $debug_level > 0 ? $debug_level = true : $debug_level = false;
    return parent::__construct($this->type, $this->username, $this->password, $this->host, '', $debug_level);
  }
  /**
   * Wrap variouns methods to add caching
   **/
  // Caching this, used for each can_connect call
  public function getinfo() {
    $this->oDebug->append("STA " . __METHOD__, 4);
    return parent::getinfo();
  }
  
  public function validateaddress($coin_address) {
    try {
      $aStatus = $this->validateaddress($coin_address);
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
$bitcoin = new BitcoinWrapper($config['wallet']['type'], $config['wallet']['username'], $config['wallet']['password'], $config['wallet']['host'], DEBUG, $debug);