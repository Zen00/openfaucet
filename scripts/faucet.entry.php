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

// Our security check
define("SECURITY", 1);

// Include our configuration (holding defines for the requires)
require_once(BASEPATH . 'config/global.inc.php');

// Find the user IP
$ip_local = $_SERVER["REMOTE_ADDR"];
$ip_proxy = $_SERVER["HTTP_X_FORWARDED_FOR"];

// Connect to the database
$con=mysqli_connect($config['db']['host'],$config['db']['user'],$config['db']['pass'],$config['db']['name']);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
// Insert to the database
$sql="INSERT INTO user (user_address, user_local_ip, user_proxy_ip)
VALUES
('$_POST[user_address]',$ip_local,$ip_proxy)";

if (!mysqli_query($con,$sql))
  {
  die('Error: ' . mysqli_error($con));
  }

mysqli_close($con);

// Run the payout cron
require_once(BASEPATH . 'cronjobs/payout.php');

?>