<?php
/* PostQ - Post-Quantum Secure Messenger */
 
//Usage: getPublicKey.php?user=test@test.com
include_once("sqlconnect.php");
if(!$_POST['user'])
  die("Error - one of the parameters is not set.");

// prepare, bind and execute
$stmt = $conn->prepare("SELECT publickey FROM users WHERE username = ?");
$stmt->bind_param("s", $_POST['user']);
$stmt->execute();
if ($stmt->errno)
  die("Error during the execution of the SQL query");

//get the result
$stmt->bind_result($publickey);

if(!$stmt->fetch())
  die("Error - user does not exist");
  
echo $publickey;
?>
