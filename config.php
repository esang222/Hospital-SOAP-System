<?php
$profileImage = 'img/admin.png';
$adminName = 'Admin01';
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "hospital_soap_system";
$port = 3307;//baguhin na lng tong port sa 3306

// increase memory limit
ini_set('memory_limit', '1024M');//nag error kulang memory for php script

// enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // create MySQL connection
    $conn = new mysqli($servername, $username, $password, $database, $port);
    
    // check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>