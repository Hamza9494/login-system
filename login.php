<?php
error_reporting(E_ALL ^ E_WARNING);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;


require __DIR__ . "/vendor/autoload.php";

$credentials = json_decode(file_get_contents("php://input"), true);



$mysqli = require __DIR__ . "/database.php";

$sql = sprintf("SELECT * FROM users WHERE email = '%s'",  $mysqli->real_escape_string($credentials['email']));


$result = $mysqli->query($sql);

$user = $result->fetch_assoc();

if ($user && $user["account_activation_hash"] ===  null) {
  $key = "mykey2023";

  if (password_verify($credentials['password'], $user['password_hash'])) {


    $key = "mykey2010";
    $payload = [
      'id' => $user["id"],
      "name" => $user["name"]
    ];

    $jwt_token = JWT::encode($payload, $key, 'HS256');



    echo json_encode(["info" => $jwt_token]);

    exit;
  }
}
echo json_encode(["info" => null]);
