<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class Faucetpayout Extends Base {
  protected $table = 'users';

  /**
   * Check if the user has an active payout request already
   * @param user_ip str User IP
   * @return boolean bool True of False
   **/
  public function isPayoutActive($userIP) {
    $stmt = $this->mysqli->prepare("SELECT user_ip FROM $this->table WHERE transaction_processed = 0 LIMIT 1");
    if ($stmt && $stmt->bind_param('i', $user_ip) && $stmt->execute( )&& $stmt->store_result() && $stmt->num_rows > 0)
      return true;
    return $this->sqlError('GE0002');
  }
  
  /**
   * Check if the user has paid out for the day
   * @param user_ip str User IP
   * @return boolean bool True of False
   **/
  public function isUserReturning($userIP) {
    $stmt = $this->mysqli->prepare("SELECT user_ip FROM $this->table LIMIT 1");
    if ($stmt && $stmt->bind_param('i', $user_ip) && $stmt->execute( )&& $stmt->store_result() && $stmt->num_rows > 0)
      return true;
    return $this->sqlError('GE0003');
  }

  /**
   * Get all new, unprocessed payout requests
   * @param none
   * @return data Associative array with DB Fields
   **/
  public function getUnprocessedPayouts() {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE transaction_processed = 0");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError('E0050');
  }
  
  /**
   * Mark a payout as processed
   * @param id int Payout ID
   * @return boolean bool True or False
   **/
  public function setProcessed($id) {
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET transaction_processed = 1 WHERE id = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('i', $id) && $stmt->execute())
      return true;
    return $this->sqlError('E0051');
  }
}

$oFaucetpayout = new Faucetpayout();
$oFaucetpayout->setDebug($debug);
$oFaucetpayout->setMysql($mysqli);
$oFaucetpayout->setErrorCodes($aErrorCodes);