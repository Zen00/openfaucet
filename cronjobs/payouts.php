#!/usr/bin/php
<?php

/*

Copyright:: 2013, Sebastian Grewe

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

if ($setting->getValue('disable_payouts') == 1) {
  $log->logInfo(" payouts disabled via admin panel");
  $monitoring->endCronjob($cron_name, 'E0009', 0, true, false);
}
$log->logInfo("Starting Payout...");
if ($bitcoin->can_connect() !== true) {
  $log->logFatal(" unable to connect to RPC server, exiting");
  $monitoring->endCronjob($cron_name, 'E0006', 1, true);
}
if ($setting->getValue('disable_manual_payouts') != 1) {
  // Fetch outstanding payout requests
  if ($aPayouts = $oFaucetpayout->getUnprocessedPayouts()) {
    if (count($aPayouts) > 0) {
      $log->logInfo("\tStarting Manual Payments...");
      $log->logInfo("\tAccount ID\tUsername\tBalance\t\tCoin Address");
      foreach ($aPayouts as $aData) {
        $transaction_id = NULL;
        $rpc_txid = NULL;
        // Validate address against RPC
        try {
          $aStatus = $bitcoin->validateaddress($aData['user_address']);
          if (!$aStatus['isvalid']) {
            $log->logError('User: ' . $aData['id'] . ' - Failed to verify this users coin address, skipping payout');
            continue;
          }
        } catch (Exception $e) {
          $log->logError('User: ' . $aData['id'] . ' - Failed to verify this users coin address, skipping payout');
          continue;
        }
          // To ensure we don't run this transaction again, lets mark it completed
          if (!$oFaucetpayout->setProcessed($aData['id'])) {
            $log->logFatal('unable to mark transaction ' . $aData['id'] . ' as processed. ERROR: ' . $oFaucetpayout->getCronError());
            $monitoring->endCronjob($cron_name, 'E0010', 1, true);
          }
          $log->logInfo("\t" . $aData['id'] . "\t\t" . $aData['user_ip'] . "\t\t" . $aData['user_address']);
          if ($transaction->addTransaction($aData['id'], $config['payout'], 'Debit_MP', NULL, $aData['user_address'], NULL)) {
            // Store debit transaction ID for later update
            $transaction_id = $transaction->insert_id;
            // Mark all older transactions as archived
            if (!$transaction->setArchived($aData['account_id'], $transaction->insert_id))
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
              $log->logError('Unable to add RPC transaction ID ' . $rpc_txid . ' to transaction record ' . $tx_id . ': ' . $transaction->getCronError());
          } else {
            $log->logFatal('Failed to add new Debit_MP transaction in database for user ' . $aData['id'] . ' ERROR: ' . $transaction->getCronError());
            $monitoring->endCronjob($cron_name, 'E0064', 1, true);
          }
      }
    }
  } else if (empty($aPayouts)) {
    $log->logInfo("\tStopping Payments. No Payout Requests Found.");
  } else {
    $log->logFatal("\tFailed Processing Manual Payment Queue...Aborting...");
    $monitoring->endCronjob($cron_name, 'E0050', 1, true);
  }
  if (count($aPayouts > 0)) $log->logDebug(" found " . count($aPayouts) . " queued manual payout requests");
} else {
  $log->logDebug("Manual payouts are disabled via admin panel");
}

$log->logInfo("Completed Payouts");
// Cron cleanup and monitoring
require_once('cron_end.inc.php');