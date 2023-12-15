<?php
mysqli_report(MYSQLI_REPORT_OFF);


$host = 'localhost';
$user = 'root';
$db_name = 'blogs';
$password = '';

$mysqli = new mysqli($host, $user, $password, $db_name);

if ($mysqli->connect_errno) {
    die("connection error my dude" . $mysqli->connect_errno);
}

return $mysqli;
