<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

/**
 * Helper class for our cronjobs
 * Implements some common cron tasks outside
 * the scope of our web application
 **/
class Tools extends Base {
  /**
   * Fetch JSON data from an API
   * @param url string API URL
   * @param target string API method
   * @param auth array Optional authentication data to be sent with
   * @return dec array JSON decoded PHP array
   **/
  public function getApi($url, $target, $auth=NULL) {
    static $ch = null;
    static $ch = null;
    if (is_null($ch)) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
    }
    curl_setopt($ch, CURLOPT_URL, $url . $target);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    // run the query
    $res = curl_exec($ch);
    if ($res === false) {
      $this->setErrorMessage('Could not get reply: '.curl_error($ch));
      return false;
    }
    $dec = json_decode($res, true);
    if (!$dec) {
      $this->setErrorMessage('Invalid data received, please make sure connection is working and requested API exists');
      return false;
    }
    return $dec;
  }
}

$tools = new Tools();
$tools->setDebug($debug);
$tools->setConfig($config);