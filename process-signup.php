<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');

$credentials = json_decode(file_get_contents("php://input"), true);
if (empty($credentials['name'])) {
    die("name cannot be empty");
}

if (!filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
    die("invalid email");
}

if (strlen($credentials['password']) < 8) {
    die('password must be at least 8 characters');
}

if (!preg_match("/[a-z]/i", $credentials['password'])) {
    die('password must contain at least one letter');
}

if (!preg_match("/[0-9]/", $credentials['password'])) {
    die('password must be contain at least one number');
}

if ($credentials['password'] !== $credentials['password_confirmation']) {
    die('password does not match');
}

$activation_token = bin2hex(random_bytes(16));

$activation_token_hash = hash('sha256', $activation_token);

$password_hash = password_hash($credentials['password'], PASSWORD_DEFAULT);

$mysqli = require __DIR__ .  '/database.php';

$sql = "INSERT INTO user (name , email , password_hash , account_activation_hash) VALUE (? , ? , ? , ?) ";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param('ssss', $credentials['name'], $credentials['email'], $password_hash, $activation_token_hash);

if ($stmt->execute()) {
    $mail = require __DIR__ . "/mailer.php";

    //recipient details
    $mail->setFrom("dummybloger@email.com");
    $mail->addAddress($credentials['email']);

    //email content
    $mail->isHTML(true);
    $mail->Subject = " Account Activation";
    $mail->Body = <<<END
    Click <a href= "http://localhost/projects/new_login_system/activate-account.php?token=$activation_token" >  here </a>  to activate your account.
 END;

    $mail->send();
    echo json_encode(["message" => "data recived", "userData" => $credentials]);
    exit;
} else if ($mysqli->errno === 1062) {
    die("email already exists my dude");
}
