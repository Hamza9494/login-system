<?php
$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);


$expiry_time = date("y-m-d H:i:s ", time() + 60 * 60);

$mysqli = require __DIR__ . "/database.php";

$sql = "UPDATE user SET reset_token_hash = ? , expiry_time = ? WHERE email =  ? ";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry_time, $_POST['email']);

$stmt->execute();

if ($mysqli->affected_rows) {
    $mail = require __DIR__  . "/mailer.php";

    //recipient details
    $mail->setFrom("dummybloger@email.com");
    $mail->addAddress($_POST['email']);

    //email content
    $mail->isHTML(true);
    $mail->Subject = " Password Reset";
    $mail->Body = <<<END
    Click <a href= "http://localhost/projects/new_login_system/reset-password.php?token=$token" >  here </a>  to activate your account.
 END;

    try {
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>

<body>
    <h3>an email has been sent to you. click on the link to reset your password</h3>
</body>

</html>