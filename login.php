<?php
/* PostQ - Post-Quantum Secure Messenger */
 
//Usage: login.php
//       POST username: email@adfs.hu
//            password: iExmLmGEgXVDPfGjI%2Fk5Iw==

include_once("helpers.php");
require_once("sqlconnect.php");
if(!$_POST['username'] || !$_POST['password'])
  die("Error - one of the parameters is not set.");

echo loginhelper($conn, $_POST['username'], $_POST['password']);
?>
