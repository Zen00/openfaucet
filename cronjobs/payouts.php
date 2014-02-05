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
$aPayouts = $oFaucetpayout->getUnprocessedPayouts();
	
// Determine if there are payouts to be completed
if (count($aPayouts) > 0) {
	$log->logInfo("Found " . count($aPayouts) . " queued payout requests.");
	
	// Create a array of the found payouts
	foreach ($aPayouts as $aData) {
		$transaction_id = NULL;
		$rpc_txid = NULL;
		
		// Validate address against RPC
		if (!$bitcoin->validateaddress($aData['user_address'])) {
			$log->logError('Failed to verify the coin address for user ' . $aData['id'] . ', skipping payout.');
			$oFaucetpayout->setProcessed($aData['id']
			continue;
		}
		
		// To ensure we don't run this transaction again, lets mark it completed
		if (!$oFaucetpayout->setProcessed($aData['id'])) {
			$log->logFatal('unable to mark transaction ' . $aData['id'] . ' as processed. ERROR: ' . $oFaucetpayout->getCronError());
			$monitoring->endCronjob($cron_name, 'E0010', 1, true);
		}
		
		// Create a new transaction in the table
		if ($transaction->addTransaction($aData['id'], $config['payout'], 'Debit_MP', NULL, $aData['user_address'], NULL)) {
			
			// Store debit transaction ID for later update
			$transaction_id = $transaction->insert_id;
			
			// Mark all older transactions as archived
			if (!$transaction->setArchived($aData['id'], $transaction->insert_id))
				$log->logError('Failed to mark transactions for #' . $aData['id'] . ' prior to #' . $transaction->insert_id . ' as archived. ERROR: ' . $transaction->getCronError());
			
			// Run the payouts from RPC now that the user is fully debited
			try {
				$rpc_txid = $bitcoin->sendtoaddress($aData['user_address'], $config['payout']);
			} catch (Exception $e) {
				$log->logError('E0078: RPC method did not return 200 OK: Address: ' . $aData['user_address'] . ' ERROR: ' . $e->getMessage());
				// Remove this line below if RPC calls are failing but transactions are still added to it
				// Don't blame MPOS if you run into issues after commenting this out!
				$monitoring->endCronjob($cron_name, 'E0078', 1, true);
			}
				
			// Update our transaction and add the RPC Transaction ID
			if (empty($rpc_txid) || !$transaction->setRPCTxId($transaction_id, $rpc_txid))
				$log->logError('Unable to add RPC transaction ID ' . $rpc_txid . ' to transaction record ' . $transaction_id . ' Error: ' . $transaction->getCronError());
		}
		
		// Log completion
		$log->logInfo('Completed payout successfully for user ' . $aData['id'] . ' with IP ' . $aData['user_ip'] . ' and address ' . $aData['user_address']); 
	}
	
} else if (empty($aPayouts)) {
	$log->logInfo("Stopping payout cron. No payout requests found.");
} else {
	$log->logFatal("Failed processing payment queue! ...Aborting...");
	$monitoring->endCronjob($cron_name, 'E0050', 1, true);
}

// Cron cleanup and monitoring
require_once('cron_end.inc.php');