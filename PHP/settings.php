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
    <p>Welkom <?= $_SESSION['username'] ?>!</p>

    <a href="#">General Settings</a><br>
    <a href="#">Display Settings</a><br>
    <a href="#">Account Settings</a><br>

    <div class="titel-wrapper"><h1 class="main-titel">Settings:</h1></div>
    <section class="general-wrapper">
        <form>
            <h2>General</h2>
                <h3>Taal:</h3>
                <select name="language" class="language">
                    <option value="nl">Nederlands</option>
                    <option value="en">English</option>
                </select>


                <h3>Notifications:</h3>
                <select class="notification-wrapper">
                    <option value="email">Email</option>
                    <option value="sms">SMS</option>
                    <option value="push">Push</option>
                </select>

                <h3>Agenda: </h3>
                <select class="agenda-wrapper">
                    <option type="checkbox" name="agenda" value="maand">Maand<br>
                    <option type="checkbox" name="agenda" value="week">Week<br>
                    <option type="checkbox" name="agenda" value="dag">Dag<br>
                </select>

            <input type="submit" value="Opslaan">
        </form>
    </section>
    <section class="display-wrapper">
        <h2>Display</h2>
            <h3>Color Theme:</h3>
            <select class="color-theme-wrapper">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
            </select>

            <h3>Font Size:</h3>
            <select class="font-size-wrapper">
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
            </select>

        <input type="submit" value="Opslaan">
    </section>
    <section class="account-wrapper">
        <h2>Account</h2>
            <h3>account informatie:</h3>
            <form action="" method="post">
                <input type="text" name="username" value="<?= $_SESSION['username'] ?>">
                <input type="text" name="email" value="<?= $_SESSION['email'] ?>">
                <input type="password" name="password" value="<?= $_SESSION['password'] ?>">


                <input type="submit" value="Opslaan">
            </form>

        <?php

            if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $id = $_SESSION['id'];

                $sql = "UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE id = '$id'";
                $result = mysqli_query($connection, $sql);

                if($result) {
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    header('Location: settings.php');
                } else {
                    echo "Er is iets fout gegaan";
                }
            } else {
                echo "Vul alle velden in";
            } ?>


    </section>

</body>
</html>