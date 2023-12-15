<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


require __DIR__ . "/vendor/autoload.php";

// 




$is_invalid = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credentials = json_decode(file_get_contents("php://input"), true);
    echo $credentials;


    $mysqli = require __DIR__ . "/database.php";

    $sql = sprintf("SELECT * FROM user WHERE email = '%s'",  $mysqli->real_escape_string($credentials['email']));

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if ($user && $user["account_activation_hash"] ===  null) {
        $key = "mykey2023";

        if (password_verify($credentials['password'], $user['password_hash'])) {

            // session_start();
            // session_regenerate_id();
            //$_SESSION['user_id'] = $user['id'];
            //header("Location: index.php");



            $key = "mykey2010";
            $payload = [
                'id' => $user["id"],
                "name" => $user["name"]
            ];

            $jwt_token = JWT::encode($payload, $key, 'HS256');

            echo json_encode(["token" => $jwt_token]);

            exit;
        }
    }

    $is_invalid = true;
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>


    <?php if ($is_invalid) : ?>
        <em>Invalid credentials</em>
    <?php endif; ?>

    <form method="post">

        <label for="email">email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? " ") ?>">

        <label for="password">password</label>
        <input type="password" id="password" name="password">

        <button>login</button>
        <a href="forget-password.php">forgot password?</a>

    </form>
</body>

</html>