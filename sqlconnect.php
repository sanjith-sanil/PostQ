<?php
/* PostQ - Post-Quantum Secure Messenger */

if (file_exists("sqlconfig.php")) {
    include_once("sqlconfig.php");
}

$sqlservername = getenv("DB_HOST") ?: ($sqlservername ?? "127.0.0.1");
$sqlusername = getenv("DB_USER") ?: ($sqlusername ?? "root");
$sqlpassword = getenv("DB_PASS") ?: ($sqlpassword ?? "");
$sqldbname = getenv("DB_NAME") ?: ($sqldbname ?? "postq");

// Create connection
$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
