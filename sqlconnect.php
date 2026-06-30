<?php
/* PostQ - Post-Quantum Secure Messenger */

if (file_exists("sqlconfig.php")) {
    include_once("sqlconfig.php");
}

$sqlservername = getenv("DB_HOST") ?: ($sqlservername ?? "127.0.0.1");
$sqlusername = getenv("DB_USER") ?: ($sqlusername ?? "root");
$sqlpassword = getenv("DB_PASS") ?: ($sqlpassword ?? "");
$sqldbname = getenv("DB_NAME") ?: ($sqldbname ?? "postq");

// Parse port if specified in DB_HOST (e.g. host:port)
$db_host_parts = explode(":", $sqlservername);
$sqlhost = $db_host_parts[0];
$sqlport = isset($db_host_parts[1]) ? intval($db_host_parts[1]) : 3306;

// Create connection
$conn = new mysqli($sqlhost, $sqlusername, $sqlpassword, $sqldbname, $sqlport);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
