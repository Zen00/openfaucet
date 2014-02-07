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
  public function getTransactionSummary() {
    $sql = "SELECT SUM(t.amount) AS total, t.type AS type FROM transactions AS t LEFT OUTER JOIN blocks AS b ON b.id = t.block_id WHERE ( b.confirmations > 0 OR b.id IS NULL GROUP BY t.type";
    $stmt = $this->mysqli->prepare($sql);
      if (!($this->checkStmt($stmt) && $stmt->execute()))
        return false;
      $result = $stmt->get_result();
    if ($result) {
      $aData = NULL;
      while ($row = $result->fetch_assoc()) {
        $aData[$row['type']] = $row['total'];
      }
    }
    return $this->sqlError();
  }

  /**
   * Get all transactions from start for account_id
   * @param start int Starting point, id of transaction
   * @param filter array Filter to limit transactions
   * @param limit int Only display this many transactions
   * @param account_id int Account ID
   * @return data array Database fields as defined in SELECT
   **/
  public function getTransactions($start=0, $filter=NULL, $limit=30, $account_id=NULL) {
    $this->debug->append("STA " . __METHOD__, 4);
    $sql = "
      SELECT
        t.id AS id,
        t.type AS type,
        t.amount AS amount,
        t.coin_address AS coin_address,
        t.timestamp AS timestamp,
        t.txid AS txid,
      FROM $this->table AS t
      LEFT JOIN " . $this->user->getTableName() . " AS a ON t.account_id = a.id";
    if (!empty($account_id)) {
      $sql .= " WHERE ( t.account_id = ? ) ";
      $this->addParam('i', $account_id);
    }
    if (is_array($filter)) {
      $aFilter = array();
      foreach ($filter as $key => $value) {
        if (!empty($value)) {
          switch ($key) {
          case 'type':
            $aFilter[] = "( t.type = ? )";
            $this->addParam('s', $value);
            break;
          case 'status':
            switch ($value) {
            case 'Confirmed':
              if (empty($filter['type']) || ($filter['type'] != 'Debit_MP' && $filter['type'] != 'Debit_SP' && $filter['type'] != 'Donation_PPS')) {
                $aFilter[] = "( b.confirmations >= " . $this->config['confirmations'] . " OR ISNULL(b.confirmations) )";
              }
                break;
            case 'Unconfirmed':
              $aFilter[] = "( b.confirmations < " . $this->config['confirmations'] . " AND b.confirmations >= 0 )";
                break;
            }
            break;
            case 'account':
              $aFilter[] = "( LOWER(a.username) = LOWER(?) )";
              $this->addParam('s', $value);
              break;
            case 'address':
              $aFilter[] = "( t.coin_address = ? )";
              $this->addParam('s', $value);
              break;
          }
        }
      }
      if (!empty($aFilter)) {
      	empty($account_id) ? $sql .= " WHERE " : $sql .= " AND ";
        $sql .= implode(' AND ', $aFilter);
      }
    }
    $sql .= " ORDER BY id DESC LIMIT ?,?";
    // Add some other params to query
    $this->addParam('i', $start);
    $this->addParam('i', $limit);
    $stmt = $this->mysqli->prepare($sql);
    if ($this->checkStmt($stmt) && call_user_func_array( array($stmt, 'bind_param'), $this->getParam()) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
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
$fTransaction->setDebug($debug);
$fTransaction->setErrorCodes($aErrorCodes);
$fTransaction->setUser($user);