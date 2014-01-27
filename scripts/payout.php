#!/bin/bash
<?php

/*

Copyright:: 2014, Grant Brown

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

*/

// Start logging
require_once(BASEPATH . '/scripts/KLogger.php');
$log = new KLogger ( '/logs/' . $cron_name . '.txt' , KLogger::INFO );
$log->LogDebug('Starting ' . $cron_name);
$log->logInfo("Starting Payout...");

// Test for repeat users
$uData['user_local_ip'] =  mysql_query("SELECT * FROM user_local_ip");
$uData['user_proxy_ip'] = mysql_query("SELECT * FROM user_proxy_ip");

// Fail against common fatal errors
if ($uData['user_local_ip'] or $uData['user_proxy_ip'] == $ip_local or $ip_proxy) {
  $log->logFatal(" user has already paid out for today!");
  $monitoring->endCronjob($cron_name, 'E0000', 1, true);
}

if ($bitcoin->can_connect() !== true) {
  $log->logFatal(" unable to connect to RPC server, exiting");
  $monitoring->endCronjob($cron_name, 'E0001', 1, true);
}

if ($wBalance == 0) {
  $log->logFatal(" the wallet has been emptied!");
  $monitoring->endCronjob($cron_name, 'E0002', 1, true);
}

// Start payment logs
$log->logInfo("\tStarting Manual Payment...");
$log->logInfo("\tID\tIP\tBalance\t\tCoin Address");

// Validate address against RPC
try {
	$uStatus = $bitcoin->validateaddress($uAddress);
	if (!$uStatus['isvalid']) {
		$log->logError('User: ' . $uData['user_local_ip'] . ' - Failed to verify this users coin address, skipping payout');
		continue;
	}
} catch (Exception $e) {
	$log->logError('User: ' . $uData['user_local_ip'] . ' - Failed to verify this users coin address, skipping payout');
	continue;
}

// Payout and mark job completion
if (!$oPayout->setProcessed($uData['user_ip'])) {
	$log->logFatal('unable to mark transaction for ' . $uData['user_IP'] . ' as processed. ERROR: ' . $oPayout->getCronError());
	$monitoring->endCronjob($cron_name, 'E0003', 1, true);
}

$log->logInfo("\t" . $uData['user_ip'] . "\t\t" .  $uData['user_address']);

try {
	$txid = $bitcoin->sendtoaddress($uData['user_address'], "1.00000000");
} catch (Exception $e) {
	$log->logError(' RPC method did not return 200 OK: Address: ' . $uData['user_address'] . ' ERROR: ' . $e->getMessage());
	$monitoring->endCronjob($cron_name, 'E0004', 1, true);
}
          
$faucettransaction->addTransaction($uData['user_ip'], "1.00000000", 'Faucet_Payout', $uData['user_address'], $txid, 1)

$log->logInfo("Completed Payouts");

wait(1);

// Cron cleanup and monitoring
require_once('cron_end.inc.php');
?>
















