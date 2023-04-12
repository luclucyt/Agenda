<!doctype html>
<html lang="en">
<head>
    <?php include_once 'connectAgenda.php'; ?>
    <?php
        if(session_status() == PHP_SESSION_NONE) {
            session_set_cookie_params(31536000);
            session_start();
        }
        if(!isset($_SESSION['username'])) {
            header('Location: login.php');
        }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setting - <?= $_SESSION['username'] ?></title>
</head>
<body>
    <h1>Settings</h1>
    <p>Welkom <?= $_SESSION['username'] ?>!</p>



</body>
</html>