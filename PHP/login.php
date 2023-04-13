<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="../CSS/notebook.png">
    <title>Log in</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/root.css">
    <link rel="stylesheet" href="../CSS/login.css">

    <!-- JS -->
    <script src="../JS/login.js" defer></script>
</head>
<body>

    <?php include_once 'connectAgenda.php'; ?>
    <section class="golf-1">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#140152" fill-opacity="1" d="M0,160L24,165.3C48,171,96,181,144,165.3C192,149,240,107,288,101.3C336,96,384,128,432,149.3C480,171,528,181,576,181.3C624,181,672,171,720,144C768,117,816,75,864,74.7C912,75,960,117,1008,122.7C1056,128,1104,96,1152,74.7C1200,53,1248,43,1296,53.3C1344,64,1392,96,1416,112L1440,128L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z"></path></svg>
    </section>
    <section class="main">
    <div class="main-login-wrapper">

        <div class="signup-wrapper">
            <h1>Registeer:</h1>
            <form method="POST" action="" autocomplete="off">
                <input type="text" name="username" placeholder="Gebruikersnaam" class="input-sign-up"><br>
                <input type="email" name="email" placeholder="E-mail" class="input-sign-up"><br>
                <input type="password" name="password" placeholder="Wachtwoord" id="password" class="input-sign-up"><br>

                <button type="submit" name="signup-submit" id="submit-button" class="submit-button"><h2>Registreer nu</h2></button>
            </form>
        </div>

        <div class="login-wrapper">
            <h1>Log in:</h1>
            <form method="post" autocomplete="off">
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
                    //after the query is done execute another query to get the id of the user
                    $sql2 = "SELECT id FROM login WHERE username='$username' AND password='$password'";
                    $result2 = mysqli_query($connection, $sql2);
                    $row = mysqli_fetch_assoc($result2);
                    $id = $row['id'];
                    echo $id;

                    $sql = "SELECT email FROM login WHERE username='$username' AND password='$password'";
                    $result = mysqli_query($connection, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if(mysqli_num_rows($result) > 0){
                        $_SESSION['email'] = $row['email'];
                    }

                    //set the session variable to the id of the user
                    $_SESSION['userID'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;

                    //redirect to index.php
                    header("Location: index.php");
                } else {
                    echo "<h1>Wachtwoord of gebruikersnaam verkeerd!</h1>";
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
                    $sql = "INSERT INTO login (id, username, email, password, ColorTheme) VALUES ('', '$username' , '$email', '$password', 1)";

                    //check if the query is executed
                    if (mysqli_query($connection, $sql)) {
                        echo "aanmelding gelukt!";

                        //get the userID of the user
                        $sql2 = "SELECT id FROM login WHERE username='$username'";
                        $result2 = mysqli_query($connection, $sql2);
                        $row = mysqli_fetch_assoc($result2);
                        $id = $row['id'];

                        //after the query is done execute another query to insert the deafualt colors into the database
                        $sql2 = "SELECT id FROM login WHERE username='$username'";
                        $result2 = mysqli_query($connection, $sql2);
                        $row = mysqli_fetch_assoc($result2);
                        $id = $row['id'];

                        //after the query is done execute another query to insert the deafualt colors into the database
                        $sql1 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#000000', 'Slaap')";
                        $sql2 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#04052EFF', 'School')";
                        $sql3 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#140152FF', 'Werk')";
                        $sql4 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#22007CFF', 'Sport')";
                        $sql5 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#0D00A4FF', 'Vrije tijd')";

                        $result1 = mysqli_query($connection, $sql1);
                        $result2 = mysqli_query($connection, $sql2);
                        $result3 = mysqli_query($connection, $sql3);
                        $result4 = mysqli_query($connection, $sql4);
                        $result5 = mysqli_query($connection, $sql5);
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                    }
                } else {
                    echo "Gebruikersnaam of email zijn al in gebruik!";
                }
            }
        ?>
    </div>
    </section>
    <section class="golf">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#140152" fill-opacity="1" d="M0,224L24,218.7C48,213,96,203,144,186.7C192,171,240,149,288,160C336,171,384,213,432,218.7C480,224,528,192,576,186.7C624,181,672,203,720,213.3C768,224,816,224,864,224C912,224,960,224,1008,234.7C1056,245,1104,267,1152,272C1200,277,1248,267,1296,266.7C1344,267,1392,277,1416,282.7L1440,288L1440,320L1416,320C1392,320,1344,320,1296,320C1248,320,1200,320,1152,320C1104,320,1056,320,1008,320C960,320,912,320,864,320C816,320,768,320,720,320C672,320,624,320,576,320C528,320,480,320,432,320C384,320,336,320,288,320C240,320,192,320,144,320C96,320,48,320,24,320L0,320Z"></path></svg>
    </section>
</body>
</html>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null, null, window.location.href);
    }
</script>