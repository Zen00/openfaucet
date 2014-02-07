<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

$aThemes = $template->getThemes();

// Load the settings available in this system
$aSettings['website'][] = array(
  'display' => 'Maintenance Mode', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'maintenance', 'value' => $setting->getValue('maintenance'),
  'tooltip' => 'Enable or Disable maintenance mode. Only admins can still login.'
);
$aSettings['website'][] = array(
  'display' => 'Website Name', 'type' => 'text',
  'size' => 25,
  'default' => 'The Faucet',
  'name' => 'website_name', 'value' => $setting->getValue('website_name'),
  'tooltip' => 'The name of you faucet page, displayed in the header of the page.'
);
$aSettings['website'][] = array(
  'display' => 'Website e-mail', 'type' => 'text',
  'size' => 25,
  'default' => 'test@example.com',
  'name' => 'website_email', 'value' => $setting->getValue('website_email'),
  'tooltip' => 'The email address for your faucet, used in mail templates.'
);
$aSettings['website'][] = array(
  'display' => 'Website theme', 'type' => 'select',
  'options' => $aThemes,
  'default' => 'faucet',
  'name' => 'website_theme', 'value' => $setting->getValue('website_theme'),
  'tooltip' => 'The default theme used on your faucet.'
);
$aSettings['website'][] = array(
  'display' => 'Website mobile theme', 'type' => 'select',
  'options' => $aThemes,
  'default' => 'mobile',
  'name' => 'website_mobile_theme', 'value' => $setting->getValue('website_mobile_theme'),
  'tooltip' => 'The mobile theme used for your faucet.'
);
$aSettings['wallet'][] = array(
  'display' => 'Cold Coins', 'type' => 'text',
  'size' => 6,
  'default' => 0,
  'name' => 'wallet_cold_coins', 'value' => $setting->getValue('wallet_cold_coins'),
  'tooltip' => 'Amount of coins held in a cold wallet.'
);
$aSettings['statistics'][] = array(
  'display' => 'Enable Google analytics', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'statistics_analytics_enabled', 'value' => $setting->getValue('statistics_analytics_enabled'),
  'tooltip' => 'Enable or Disable Google Analytics.'
);
$aSettings['statistics'][] = array(
  'display' => 'Google Analytics Code', 'type' => 'textarea',
  'size' => 20,
  'height' => 12,
  'default' => 'Code from Google Analytics',
  'name' => 'statistics_analytics_code', 'value' => $setting->getValue('statistics_analytics_code'),
  'tooltip' => '.'
);
$aSettings['news'][] = array(
  'display' => 'Hide news post author', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'hide_news_author', 'value' => $setting->getValue('hide_news_author'),
  'tooltip' => 'Should the news author username be hidden.'
);
$aSettings['system'][] = array(
  'display' => 'E-mail address for system error notifications', 'type' => 'text',
  'size' => 25,
  'default' => 'test@example.com',
  'name' => 'system_error_email', 'value' => $setting->getValue('system_error_email'),
  'tooltip' => 'The email address for system errors notifications, like cronjobs failures.'
);
$aSettings['system'][] = array(
  'display' => 'Disable e-mail confirmations', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'accounts_confirm_email_disabled', 'value' => $setting->getValue('accounts_confirm_email_disabled'),
  'tooltip' => 'Should users supply a valid e-mail address upon registration. Requires them to confirm the address before accounts are activated.'
);
$aSettings['system'][] = array(
  'display' => 'Disable registrations', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'lock_registration', 'value' => $setting->getValue('lock_registration'),
  'tooltip' => 'Enable or Disable registrations.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Payout Cron', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_payouts', 'value' => $setting->getValue('disable_payouts'),
  'tooltip' => 'Enable or Disable the payout cronjob. Users will not be able to withdraw any funds if disabled. Will be logged in monitoring page.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Contactform', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_contactform_guest', 'value' => $setting->getValue('disable_contactform'),
  'tooltip' => 'Enable or Disable Contactform. Users will not be able to use the contact form.'
);
$aSettings['system'][] = array(
  'display' => 'Disable About Page', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 1,
  'name' => 'disable_about', 'value' => $setting->getValue('disable_about'),
  'tooltip' => 'Enable or Disable About page in footer.'
);
$aSettings['system'][] = array(
  'display' => 'Disable TX Summaries', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 0,
  'name' => 'disable_transactionsummary', 'value' => $setting->getValue('disable_transactionsummary'),
  'tooltip' => 'Disable transaction summaries. Helpful with large transaction tables.'
);
$aSettings['monitoring'][] = array(
  'display' => 'Uptime Robot Private API Key', 'type' => 'text',
  'size' => 100,
  'default' => '<API KEY>|<MONITOR ID>,<API KEY>|<MONITOR ID>, ...',
  'name' => 'monitoring_uptimerobot_api_keys', 'value' => $setting->getValue('monitoring_uptimerobot_api_keys'),
  'tooltip' => 'Create per-monitor API keys and save them here to propagate your uptime statistics.'
);

