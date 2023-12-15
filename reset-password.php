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
    die("no such user exist in the database!");
}

if (strtotime($user['expiry_time']) < time()) {
    die('time expired my dude try again later!');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">

    <title>Reset Password</title>
</head>

<body>
    <h3>Reset Password</h3>
    <form action="process.reset-passowrd.php" method="post">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">


        <label for="password">password</label>
        <input type="password" name="password" id="password">

        <label for=" password_confirmation"> password_confirmation</label>
        <input type=" password" name=" password_confirmation" id=" password_confirmation">

        <button>reset</button>
    </form>
</body>

</html>