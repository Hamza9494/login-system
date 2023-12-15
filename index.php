<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');
header('access-control-allow-headers: Authorization');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


require __DIR__ . "/vendor/autoload.php";
$mysqli = require __DIR__ . "/database.php";

$headers = getallheaders();
$token = explode("Bearer ", $headers['Authorization']);
$userToken = $token[1];


$key = "mykey2010";
try {
    $decoded = JWT::decode($userToken, new Key($key, 'HS256'));
    $user_id = $decoded->id;
    $sql = "SELECT * FROM user WHERE id = ? ";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    echo json_encode(["userName" => $user['name']]);
} catch (Exception $e) {
    var_dump($e);
}
 //if (isset($_SESSION['user_id'])) {
   // $id = $_SESSION['user_id'];
    //$mysqli = require __DIR__ . "/database.php";
    //$sql = "SELECT * FROM user WHERE id = $id ";
    //$result = $mysqli->query($sql);
    // $user = $result->fetch_assoc();
//}
