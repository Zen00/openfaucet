<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class faucetTransaction extends Base {
  protected $table = 'transactions';
  public $num_rows = 0, $insert_id = 0;

  /**
   * Add a new transaction to our class table
   * We also store the inserted ID in case the user needs it
   * @param account_id int Account ID to book transaction for
   * @param amount float Coin amount
   * @param type string Transaction type [Credit, Debit_AP, Debit_MP, Fee, Donation]
   * @param coin_address string Coin address for this transaction [optional]
   * @return bool
   **/
  public function addTransaction($amount, $type='Credit', $coin_address=NULL, $txid=NULL) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (amount, type, coin_address, txid) VALUES (?, ?, ?, ?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param("dsss", $amount, $type, $coin_address, $txid) && $stmt->execute()) {
      $this->insert_id = $stmt->insert_id;
      return true;
    }
    return $this->sqlError();
  }

  /**
* Update a transaction with a RPC transaction ID
* @param id integer Transaction ID
* @param txid string RPC Transaction Identifier
* @return bool true or false
**/
  public function setRPCTxId($transaction_id, $rpc_txid=NULL) {
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET txid = ? WHERE id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('si', $rpc_txid, $transaction_id) && $stmt->execute())
      return true;
    return $this->sqlError();
  }

  /**
   * Fetch a transaction summary by type with total amounts
   * @param account_id int Account ID, NULL for all
   * @return data array type and total
   **/
  public function getTransactionSummary($account_id=NULL) {
    $sql = "
      SELECT
        SUM(t.amount) AS total, t.type AS type
      FROM transactions AS t
      LEFT OUTER JOIN blocks AS b
      ON b.id = t.block_id
      WHERE ( b.confirmations > 0 OR b.id IS NULL )";
    if (!empty($account_id)) {
      $sql .= " AND t.account_id = ? ";
      $this->addParam('i', $account_id);
    }
    $sql .= " GROUP BY t.type";
    $stmt = $this->mysqli->prepare($sql);
    if (!empty($account_id)) {
      if (!($this->checkStmt($stmt) && call_user_func_array( array($stmt, 'bind_param'), $this->getParam()) && $stmt->execute()))
        return false;
      $result = $stmt->get_result();
    } else {
      if (!($this->checkStmt($stmt) && $stmt->execute()))
        return false;
      $result = $stmt->get_result();
    }
    if ($result) {
      $aData = NULL;
      while ($row = $result->fetch_assoc()) {
        $aData[$row['type']] = $row['total'];
      }
    }
    return $this->sqlError();
  }

  /**
   * Get all different transaction types
   * @return mixed array/bool Return types on succes, false on failure
   **/
  public function getTypes() {
    $stmt = $this->mysqli->prepare("SELECT DISTINCT type FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = array('' => '');
      while ($row = $result->fetch_assoc()) {
        $aData[$row['type']] = $row['type'];
      }
      return $aData;
    }
    return $this->sqlError();
  }
}

$fTransaction = new faucetTransaction();
$fTransaction->setMysql($mysqli);
$transaction->setDebug($debug);
$fTransaction->setErrorCodes($aErrorCodes);