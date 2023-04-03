<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LogIN</title>
</head>
<body>

    <?php include_once 'ConnectLogin.php'; ?>

    <h1>Sign up</h1>
    <form method="POST" action="">
        <label for="username">Username: </label>
        <input type="text" name="username" placeholder="Username">

        <label for="email">Email: </label>
        <input type="email" name="email" placeholder="Email">

        <label for="password">Password: </label>
        <input type="password" name="password" placeholder="Password" id="password">

        <button type="submit" name="signup-submit" id="submit-button">Sign up</button>
    </form><br>

    <h1>Log in</h1>
    <form method="POST" action="">
        <label for="username">Username: </label>
        <input type="text" name="username" placeholder="Username">

        <label for="password">Password: </label>
        <input type="password" name="password" placeholder="Password">

        <button type="submit" name="login-submit">Log in</button>
    </form>
</body>
</html>

<?php
    if (session_status() == PHP_SESSION_NONE) {
        ini_set('session.gc_maxlifetime', 999999); // Set the session max lifetime to 999999 seconds (11 days)
        session_start(); // Start the session if it doesn't exist
    }

    //log in
    if (isset($_POST['login-submit'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM login WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "You are logged in!";
            $_SESSION['username'] = $username;
            header("Location: index.php");
        } else {
            echo "Wrong username or password!";
            $_SESSION['username'] = '';
        }
    }
?>
<?php
    //sign up
    if (isset($_POST['signup-submit'])) {

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        //check if username already exists, or the email is already in use
        $existsNameSQL = "SELECT * FROM login WHERE username='$username'";
        $existsEmailSQL = "SELECT * FROM login WHERE email='$email'";
        $result = mysqli_query($conn, $existsNameSQL);
        $result2 = mysqli_query($conn, $existsEmailSQL);

        if (mysqli_num_rows($result) == 0 && mysqli_num_rows($result2) == 0) {

            //insert data into database
            $sql = "INSERT INTO login (id, username, email, password) VALUES ('', '$username' , '$email', '$password')";

            //check if the query is executed
            if (mysqli_query($conn, $sql)) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Username or email already in use!";
        }
    }
?>

