<?php
error_reporting(E_ERROR | E_PARSE);


$mysqli = require __DIR__ . "/database.php";

$sql = sprintf("SELECT * FROM user WHERE email = '%s' ",  $mysqli->real_escape_string($_GET["email"]));

$result = $mysqli->query($sql);

$is_available = (bool) $result->num_rows;

header("Content-Type: application/json");

echo json_encode(["available" => $is_available]);
