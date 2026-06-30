<?php
/* PostQ - Post-Quantum Secure Messenger */

echo "<h1>PostQ Installation script</h1>";

if (file_exists("sqlconfig.php")) {
    include_once("sqlconfig.php");
}

$sqlservername = getenv("DB_HOST") ?: ($sqlservername ?? "127.0.0.1");
$sqlusername = getenv("DB_USER") ?: ($sqlusername ?? "root");
$sqlpassword = getenv("DB_PASS") ?: ($sqlpassword ?? "");
$sqldbname = getenv("DB_NAME") ?: ($sqldbname ?? "postq");

echo "Attempting to connect to the database server...<br>";

// 1. Try to create the database (if we have permissions, e.g. localhost)
$temp_conn = @new mysqli($sqlservername, $sqlusername, $sqlpassword);
if ($temp_conn && !$temp_conn->connect_error) {
    @$temp_conn->query("CREATE DATABASE IF NOT EXISTS `{$sqldbname}` DEFAULT CHARACTER SET latin2 COLLATE latin2_hungarian_ci");
    $temp_conn->close();
}

// 2. Connect to the specified database
$conn = new mysqli($sqlservername, $sqlusername, $sqlpassword, $sqldbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connected. Running setup queries...<br><pre>";

$sql = file_get_contents("database_setup.sql");

// Remove CREATE DATABASE and USE statements to prevent errors on cloud databases
$sql = preg_replace('/CREATE DATABASE IF NOT EXISTS.*?;/i', '', $sql);
$sql = preg_replace('/USE `?.*?`?;/i', '', $sql);

if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
}

if ($conn->error) {
    echo "Error executing setup queries: " . $conn->error . "\n";
} else {
    echo "Database setup completed successfully!\n";
}

$conn->close();
echo "</pre>If no error was shown above, the installation was successful. <a href='index.html'>Click here.</a>";
?>
