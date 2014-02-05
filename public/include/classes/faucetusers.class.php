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
		if ($this->checkUserIP($userIP)) {
			$stmt = $this->mysqli->prepare("INSERT INTO $this->table (user_address, user_ip) VALUES (?,?)");
			$stmt->bind_param('ss',$userAddress,$userIP);
			$stmt->execute();
		}
	}
	
	public function checkUserIP($userIP) {
		$this->debug->append("STA " . __METHOD__, 4);
		$stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE user_ip = ? LIMIT 1");
		if ($this->checkStmt($stmt)) {
			$stmt->bind_param("s", $userIP);
			$stmt->execute();
			$stmt->bind_result($retval);
			$stmt->fetch();
			$stmt->close();
			if (empty($retval) {
				return true;
		} else {
			return false;
		}
		return false;
	}
}

// Make our class available automatically
$faucetusers = new Faucetusers();
$faucetusers->setMysql($mysqli);
$faucetusers->setDebug($debug);
$faucetusers->setErrorCodes($aErrorCodes);
$faucetusers->setUser($user);