<?php
/* PostQ - Post-Quantum Secure Messenger */

//Usage: sendMsg.php
//       POST data:     username=email@adfs.hu
//            password: iExmLmGEgXVDPfGjI
//            user2Id:  5
//            msg:      iExmLmGEgXVDPfGjI+=
require_once("helpers.php");
require_once("sqlconnect.php");
if(!$_POST['username'] || !$_POST['password'] || !$_POST['user2Id'] || !$_POST['msg'] || !$_POST['nonce'])
  die("Error - one of the parameters is not set.");

//check password
if(!(substr(loginhelper($conn, $_POST['username'], $_POST['password']),0,1) === "1")) //loginhelper() needs to return with 1... for successfull login
  die("Authentication failed");

$userId = getUserId($conn, $_POST['username']);
// prepare, bind and execute
$stmt = $conn->prepare("INSERT INTO messages (user1, user2, messages, nonce) VALUES (?, ?, ?, ?)"); 
$stmt->bind_param("iiss", $userId, $_POST['user2Id'], $_POST['msg'], $_POST['nonce']);
$stmt->execute();
if ($stmt->errno)
  die("Error during the execution of the SQL query");

echo "1";
?>
