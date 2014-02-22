#!/usr/bin/php
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

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Begin log
$log->logInfo("Starting payout cron...");

// Check for settings
if ($setting->getValue('disable_payouts') == 1) {
	$log->logInfo("Payouts disabled via admin panel.");
	$monitoring->endCronjob($cron_name, 'E0009', 0, true, false);
}

// Check for RPC connection
if ($bitcoin->can_connect() !== true) {
	$log->logFatal("Unable to connect to RPC server!");
	$monitoring->endCronjob($cron_name, 'E0006', 1, true);
}

// Fetch outstanding payout requests
$uPayout = $oFaucetpayout->getUnprocessedPayouts();
	
// Determine if there are payouts to be completed
if (count($uPayout) > 0) {
	$log->logInfo("Found " . count($uPayout) . " queued payout requests.");
	
	// Test each payout
	foreach ($uPayout as $uData) {
		$transaction_id = NULL;
		$rpc_txid = NULL;
		
		// Validate address against RPC
		if ($bitcoin->validateaddress($uData['user_address'])) {
		
			$log->logInfo('Starting payout for user ' . $uData['id']);
			
			// Mark transaction completed before payout to prevent doubling
			if (!$oFaucetpayout->setProcessed($uData['id'])) {
				$log->logFatal('unable to mark transaction ' . $uData['id'] . ' as processed. ERROR: ' . $oFaucetpayout->getCronError());
				$monitoring->endCronjob($cron_name, 'E0010', 1, true);
			}
			
			// Create a new transaction in the table
			if ($fTransaction->addTransaction($uData['id'], $config['payout'], 'Debit_MP', NULL, $uData['user_address'], NULL)) {
				
				// Store debit transaction ID for later update
				$transaction_id = $fTransaction->insert_id;
				
				// Run the payouts from RPC now that the user is fully debited
				try {
					$rpc_txid = $bitcoin->sendtoaddress($uData['user_address'], $config['payout']);
				} catch (Exception $e) {
					$log->logError('E0078: RPC method did not return 200 OK: Address: ' . $uData['user_address'] . ' ERROR: ' . $e->getMessage());
					
					// Remove the line below if RPC calls are failing but transactions are still being added
					// Can cause serious issues after commenting this out!
					$monitoring->endCronjob($cron_name, 'E0078', 1, true);
				}
					
				// Update transaction and add the RPC Transaction ID
				if (empty($rpc_txid) || !$fTransaction->setRPCTxId($transaction_id, $rpc_txid))
					$log->logError('Unable to add RPC transaction ID ' . $rpc_txid . ' to transaction record ' . $transaction_id . ' Error: ' . $fTransaction->getCronError());
			}
			
			// Log completion
			$log->logInfo('Completed payout successfully for user ' . $uData['id'] . ' with IP ' . $uData['user_ip'] . ' and address ' . $uData['user_address']); 

		} else {
			$log->logError('Failed to verify the coin address for user ' . $uData['id'] . ', skipping payout.');
			$oFaucetpayout->setProcessed($uData['id']);
		}
	}
} else if (empty($aPayouts)) {
	$log->logInfo("Stopping payout cron. No payout requests found.");
} else {
	$log->logFatal("Failed processing payment queue! ...Aborting...");
	$monitoring->endCronjob($cron_name, 'E0050', 1, true);
}

$log->logInfo("Payout cron has finished successfully!");

// Cron cleanup and monitoring
require_once('cron_end.inc.php');