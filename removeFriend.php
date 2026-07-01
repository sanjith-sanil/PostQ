<?php
/* PostQ - Post-Quantum Secure Messenger */

//Usage: removeFriend.php
//    username=test@test.com
//    password=iExmLmGEgXVDPfGjI/k5Iw==
//    friendId=5

require_once("helpers.php");
require_once("sqlconnect.php");
if(!$_POST['username'] || !$_POST['password'] || !$_POST['friendId'])
  die("Error - one of the parameters is not set.");

//check password
if(!(substr(loginhelper($conn, $_POST['username'], $_POST['password']),0,1) === "1")) //loginhelper() needs to return with 1... for successfull login
  die("Authentication failed");

$userId = getUserId($conn, $_POST['username']);
$friendId = (int)$_POST['friendId'];

// 1. Delete friendship symmetric key (both directions)
$stmt = $conn->prepare("DELETE FROM symkeys WHERE (user1 = ? AND user2 = ?) OR (user1 = ? AND user2 = ?)");
$stmt->bind_param("iiii", $userId, $friendId, $friendId, $userId);
$stmt->execute();
if ($stmt->errno)
  die("Error during deletion from symkeys");
$stmt->close();

// 2. Delete all chat messages between these two users
$stmt = $conn->prepare("DELETE FROM messages WHERE (user1 = ? AND user2 = ?) OR (user1 = ? AND user2 = ?)");
$stmt->bind_param("iiii", $userId, $friendId, $friendId, $userId);
$stmt->execute();
if ($stmt->errno)
  die("Error during deletion from messages");
$stmt->close();

// 3. Delete any pending key change requests
$stmt = $conn->prepare("DELETE FROM symkeyrequests WHERE (useridTO = ? AND useridFROM = ?) OR (useridTO = ? AND useridFROM = ?)");
$stmt->bind_param("iiii", $userId, $friendId, $friendId, $userId);
$stmt->execute();
if ($stmt->errno)
  die("Error during deletion from symkeyrequests");
$stmt->close();

// 4. Delete any friend requests between these two users
$stmt = $conn->prepare("DELETE FROM friendrequests WHERE (useridTO = ? AND useridFROM = ?) OR (useridTO = ? AND useridFROM = ?)");
$stmt->bind_param("iiii", $userId, $friendId, $friendId, $userId);
$stmt->execute();
if ($stmt->errno)
  die("Error during deletion from friendrequests");
$stmt->close();

echo "1";

$conn->close();
?>
