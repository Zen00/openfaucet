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
		if (!$this->bitcoin->validateaddress($userAddress)) {
			$_SESSION['POPUP'][] = array('CONTENT' => "There's been a problem, your address doesn't match the format for our currency. Please try again with another address.", 'TYPE' => 'info');
			return false;
		}
		if ($this->checkUserIP($userIP)) {
			$stmt = $this->mysqli->prepare("INSERT INTO $this->table (user_address, user_ip) VALUES (?,?)");
			$stmt->bind_param('ss',$userAddress,$userIP);
			$stmt->execute();
			$_SESSION['POPUP'][] = array('CONTENT' => "Thank you for using our faucet, you can come back in 24 hours for more coin!", 'TYPE' => 'info');
			return true;
		} else {
			$_SESSION['POPUP'][] = array('CONTENT' => "There has already been a request from your location today, please wait 24 hours between submissions.", 'TYPE' => 'info');
			return false;
		}
	}
	
	public function checkUserIP($userIP) {
		$this->debug->append("STA " . __METHOD__, 4);
		$stmt = $this->mysqli->prepare("SELECT COUNT(*) FROM $this->table WHERE user_ip = ?");
		if ($this->checkStmt($stmt)) {
			$stmt->bind_param("s", $userIP);
			$stmt->execute();
			$stmt->bind_result($retval);
			$stmt->fetch();
			$stmt->close();
			if ($retval == 0)
				return true;
		}
		return false;
	}
	
	public function emptyTable() {
		$this->debug->append("STA " . __METHOD__, 4);
		$stmt = $this->mysqli->prepare("TRUNCATE TABLE $this->table")->execute();
	}
}

// Make our class available automatically
$faucetusers = new Faucetusers();
$faucetusers->setMysql($mysqli);
$faucetusers->setDebug($debug);
$faucetusers->setErrorCodes($aErrorCodes);
$faucetusers->setUser($user);
$faucetusers->setBitcoin($bitcoin);