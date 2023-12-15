<?php
$token = $_GET['token'];

$token_hash = hash('sha256', $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM user WHERE reset_token_hash = ? ";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param('s', $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


if ($user === null) {
    die('user not found');
}

if (strtotime($user["expiry_time"]) <= time()) {
    die("token has expired");
}


if (strlen($_POST['password']) < 8) {
    die('password must be at least 8 characters');
}

if (!preg_match("/[a-z]/i", $_POST['password'])) {
    die('password must contain at least one letter');
}

if (!preg_match("/[0-9]/", $_POST['password'])) {
    die('password must be contain at least one number');
}

$new_password_hash =  password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "UPDATE user SET password_hash = ? , reset_token_hash = null , expiry_time = null  WHERE email = ?  ";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $new_password_hash, $user["email"]);

$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password rest successfuly</title>
</head>

<body>
    <?php if ($mysqli->affected_rows) : ?>
        <p>password updated sucssfully! you can now <a href="login.php">login</a> to your account with the new password!</p>

    <?php endif;  ?>
</body>

</html>