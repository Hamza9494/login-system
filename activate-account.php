<?php
$token = $_GET['token'];

$token_hash = hash('sha256', $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM user WHERE account_activation_hash = ? ";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param('s', $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    die('No unactivated account to activate!');
}

$sql = "UPDATE user SET account_activation_hash = NULL WHERE id = ? ";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param('s', $user['id']);

$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activated</title>
</head>

<body>
    <h2>Account activated successfully</h2>
    <p>you can now <a href="login.php">login!</a></p>
</body>

</html>