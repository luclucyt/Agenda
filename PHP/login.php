<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/root.css">
    <link rel="stylesheet" href="../CSS/login.css">

    <!-- JS -->
    <script src="../JS/login.js" defer></script>
</head>
<body>

    <?php include_once 'connectAgenda.php'; ?>

    <div class="main-login-wrapper">

        <div class="signup-wrapper">
            <h1>Registeer:</h1>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Gebruikersnaam" class="input-sign-up"><br>
                <input type="email" name="email" placeholder="E-mail" class="input-sign-up"><br>
                <input type="password" name="password" placeholder="Wachtwoord" id="password" class="input-sign-up"><br>

                <button type="submit" name="signup-submit" id="submit-button" class="submit-button"><h2>Registreer nu</h2></button>

            </form><br>
        </div>

        <div class="login-wrapper">
            <h1>Log in:</h1>
            <form method="post">
                <input type="text" name="username" placeholder="Gebruikersnaam" class="login-input"><br>
                <input type="password" name="password" placeholder="Wachtwoord" class="login-input"><br>

                <button type="submit" name="login-submit" class="submit-button"><h2>Log in</h2></button>
            </form>
        </div>

        <p class="login-toggle">already have an account? Login</p>

        <?php
            //start the session
            if (session_status() == PHP_SESSION_NONE) {
                session_set_cookie_params(31536000);
                session_start(); //Start the session if it doesn't exist
            }

            //log in form
            if (isset($_POST['login-submit'])) {

                $username = $_POST['username'];
                $password = $_POST['password'];

                $sql = "SELECT * FROM login WHERE username='$username' AND password='$password'";
                $result = mysqli_query($connection, $sql);


                if (mysqli_num_rows($result) > 0) {
                    $_SESSION['username'] = $username;

                    //after the query is done execute another query to get the id of the user
                    $sql2 = "SELECT id FROM login WHERE username='$username' AND password='$password'";
                    $result2 = mysqli_query($connection, $sql2);
                    $row = mysqli_fetch_assoc($result2);
                    $id = $row['id'];

                    //set the session variable to the id of the user
                    $_SESSION['userID'] = $id;

                    //redirect to index.php
                    header("Location: index.php");
                } else {
                    echo "Wachtwoord of gebruikersnaam verkeerd!";
                    $_SESSION['username'] = '';
                }
            }


            //sign up form
            if (isset($_POST['signup-submit'])) {

                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                //check if username already exists, or the email is already in use
                $existsNameSQL = "SELECT * FROM login WHERE username='$username'";
                $existsEmailSQL = "SELECT * FROM login WHERE email='$email'";
                $result = mysqli_query($connection, $existsNameSQL);
                $result2 = mysqli_query($connection, $existsEmailSQL);

                if (mysqli_num_rows($result) == 0 && mysqli_num_rows($result2) == 0) {
                    //insert data into database
                    $sql = "INSERT INTO login (id, username, email, password) VALUES ('', '$username' , '$email', '$password')";

                    //check if the query is executed
                    if (mysqli_query($connection, $sql)) {
                        echo "aanmelding gelukt!";
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                    }
                } else {
                    echo "Gebruikersnaam of email zijn al in gebruik!";
                }
            }
        ?>
    </div>
</body>
</html>