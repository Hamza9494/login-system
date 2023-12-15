<?php

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
    <h3>enter your email to reset your password</h3>
    <form action="send-reset-email.php" method="post">

        <label for="email">email</label>
        <input type="email" name="email" id="email">
        <button>send</button>
    </form>
</body>

</html>