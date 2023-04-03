<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agenda</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <?php require 'ConnectLogin.php'; ?>
    <?php require 'connectAgenda.php'; ?>


    <h1>add to Agenda:</h1>
    <form method="POST" action="index.php">
        <label for="AgendaNaam">AgendaNaam: </label>
        <input type="text" name="AgendaNaam" placeholder="AgendaNaam"><br>

        <label for="AgendaOmschrijving">AgendaOmschrijving: </label>
        <input type="text" name="AgendaOmschrijving" placeholder="AgendaOmschrijving"><br>

        <label for="AgendaStartDatum">AgendaDatum: </label>
        <input type="date" name="AgendaStartDatum" placeholder="AgendaDatum"><br>

        <label for="AgendaStartTijd">AgendaStartTijd: </label>
        <input type="time" name="AgendaStartTijd" placeholder="AgendaStartTijd"><br>

        <label for="AgendaEindTijd">AgendaEindTijd: </label>
        <input type="time" name="AgendaEindTijd" placeholder="AgendaEindTijd"><br>

        <label for="AgendaFunctie">Kies een functie: </label>
        <select name="AgendaFunctie">
            <option value="werk">Werk</option>
            <option value="school">School</option>
            <option value="prive">Prive</option>
        </select><br>

        <label for="AgendaKleur">AgendaKleur: </label>
        <select name="AgendaKleur">
            <option value="red">Rood</option>
            <option value="green">Groen</option>
            <option value="blue">Blauw</option>
        </select><br>

        <button type="submit" name="agenda-submit">Voeg to aan de Agenda</button>
    </form>

    <form method="post">
         <input type="submit" name="prev_week" value="Vorige week">
         <input type="submit" name="next_week" value="Volgende week">
    </form>

    <?php
        session_start();

        //if the user is not logged in, redirect to the login page and stop the script
        if($_SESSION['username'] == '') {
            //force the user to login (: man i can be so mean sometimes, or better said, i can be so lazy sometimes (: haha look table flip (╯°□°）╯︵ ┻━┻ )
            header("Location: login.php");
            exit();
        }

        //add to agenda
        if(isset($_POST['agenda-submit'])){
            $AgendaNaam = $_POST['AgendaNaam'];
            $AgendaOmschrijving = $_POST['AgendaOmschrijving'];
            $AgendaStartDatum = $_POST['AgendaStartDatum'];
            $AgendaEindDatum = $_POST['AgendaStartDatum'];
            $AgendaStartTijd = $_POST['AgendaStartTijd'];
            $AgendaEindTijd = $_POST['AgendaEindTijd'];
            $AgendaFunctie = $_POST['AgendaFunctie'];
            $AgendaKleur = $_POST['AgendaKleur'];

            //round the start and end time to the nearest 15 minutes
            $AgendaStartTijd = date("H:i", round(strtotime($AgendaStartTijd) / 900 ) * 900);
            $AgendaEindTijd = date("H:i", round(strtotime($AgendaEindTijd) / 900 ) * 900);


            //if the end time is before the start time, invert the start and end time
            if($AgendaStartTijd > $AgendaEindTijd){
                $AgendaStartTijd = $_POST['AgendaEindTijd'];
                $AgendaEindTijd = $_POST['AgendaStartTijd'];
            }

            //if the start time is the same as the end time, set the end time to 15 minutes later
            if($AgendaStartTijd == $AgendaEindTijd){
                $AgendaEindTijd = date("H:i", strtotime($AgendaEindTijd) + 900);
            }

            //if the start time is 00:00, set the end time to 00:01
            if($AgendaStartTijd == "00:00"){
                $AgendaEindTijd = "00:01";
            }

            //if the end time is 00:00, set the end time to 23:59
            if($AgendaEindTijd == "00:00"){
                $AgendaEindTijd = "23:59";
            }

            //run the query in the database
            $sqlAgenda = "INSERT INTO agenda (id, naam, omschrijving, startDatum, eindDatum, startTijd, eindTijd, taak, functie, kleur) VALUES ('', '$AgendaNaam' , '$AgendaOmschrijving', '$AgendaStartDatum', '$AgendaEindDatum', '$AgendaStartTijd', '$AgendaEindTijd', 'false', '$AgendaFunctie', '$AgendaKleur')";
            $result = mysqli_query($connA, $sqlAgenda);

        }
    ?>

    <h1>Agenda:</h1>
    <?php

    // Get the current week start and end dates if they are not already set
    if(!isset($_SESSION['week_start']) || !isset($_SESSION['week_end'])) {
        // Get the current date
        $date = date('Y-m-d');

        // Get the current week day
        $current_week_day = date('N', strtotime($date));

        // Get the start date of the week
        $week_start = date('Y-m-d', strtotime('-' . ($current_week_day - 1) . ' days', strtotime($date)));

        // Get the end date of the week
        $week_end = date('Y-m-d', strtotime('+' . (7 - $current_week_day) . ' days', strtotime($date)));

        // Set the session variables
        $_SESSION['week_start'] = $week_start;
        $_SESSION['week_end'] = $week_end;
    } else {
        // Get the week start and end dates from the session variables
        $week_start = $_SESSION['week_start'];
        $week_end = $_SESSION['week_end'];
    }

    // Check if the previous week button has been clicked
    if(isset($_POST['prev_week'])) {
        // Move the week start and end dates back by 7 days
        $week_start = $_SESSION['week_start'] = date('Y-m-d', strtotime('-1 week', strtotime($_SESSION['week_start'])));
        $week_end = $_SESSION['week_end'] = date('Y-m-d', strtotime('-1 week', strtotime($_SESSION['week_end'])));
    }

    // Check if the next week button has been clicked
    if(isset($_POST['next_week'])) {
        // Move the week start and end dates forward by 7 days
        $week_start = $_SESSION['week_start'] = date('Y-m-d', strtotime('+1 week', strtotime($_SESSION['week_start'])));
        $week_end = $_SESSION['week_end'] = date('Y-m-d', strtotime('+1 week', strtotime($_SESSION['week_end'])));
    }

    // Query the database for all agenda items within the current week range
    $sqlAgenda = "SELECT * FROM agenda WHERE startDatum BETWEEN '{$_SESSION['week_start']}' AND '{$_SESSION['week_end']}' ORDER BY startDatum ASC, startTijd ASC";
    $result = mysqli_query($connA, $sqlAgenda);
    $resultCheck = mysqli_num_rows($result);

    //Display the time (one

    // Display the week start and end dates
    echo "<div class='agenda-header'>";
        //Display the week start and end dates
        for ($i = 0; $i < 7; $i++) {
            $date = date('jS M', strtotime($_SESSION['week_start'] . ' +' . $i . ' days'));
            echo "<div class='agenda-day'>$date</div>";
        }
    echo "</div>";

    // If there are agenda items in the current week range, display them
    if($resultCheck > 0){
        echo "<div class='agenda-container'>";
        while($row = mysqli_fetch_assoc($result)){

            //calculate the difference in days between the start date and the week start date
            $start = new DateTime($row['startDatum']);
            $end = new DateTime($week_start);
            $dayDifference = $start->diff($end)->format("%a");

            //remove the seconds from the start time and end time
            $row['startTijd'] = substr($row['startTijd'], 0, -3);
            $row['eindTijd'] = substr($row['eindTijd'], 0, -3);

            //calculate the gird row start and end position of the event based on the start and end time when every 15 minutes is 1 row (there are 168 rows in the grid)
            $startRow = floor(((strtotime($row['startTijd']) - strtotime('00:00')) / 900) + 1);
            $endRow = floor((((strtotime($row['eindTijd']) - strtotime('00:00')) / 900) + 1));

            //put the values in a variable
            $agenda_item_id = $row['id'];
            $agenda_item_naam = $row['naam'];
            $agenda_item_omschrijving = $row['omschrijving'];
            $agenda_item_startDatum = $row['startDatum'];
            $agenda_item_startTijd = $row['startTijd'];
            $agenda_item_eindTijd = $row['eindTijd'];
            $agenda_item_functie = $row['functie'];
            $agenda_item_kleur = $row['kleur'];

            echo "<div class='agenda-item agenda-date{$dayDifference}' style='background-color:{$agenda_item_kleur}; grid-row-start:{$startRow};grid-row-end:{$endRow};'>";
                echo "<h1 class='agenda'>{$agenda_item_naam}</h1>";
                echo "<p>{$agenda_item_omschrijving}</p>";
                echo "<p>{$agenda_item_startTijd} - {$agenda_item_eindTijd}</p>";
                echo "<p>{$agenda_item_functie}</p>";

                //delete button
                echo "<form method='POST' action='index.php'>";
                    echo "<input type='hidden' name='id' value='{$agenda_item_id}'>";
                    echo "<button type='submit' name='agenda-delete'>Verwijder</button>";
                echo "</form>";

            //close the agenda item
            echo "</div>";
        }
        //close the agenda container
        echo "</div>";
    }

    //delete button is pressed
    if(isset($_POST['agenda-delete'])){
        $id = $_POST['id'];
        $sqlAgenda = "DELETE FROM agenda WHERE id = '$id'";
        $result = mysqli_query($connA, $sqlAgenda);
        header("Location: index.php");
    }
    ?>

<script>
    //DO NEVER CHANGE, IT WILL BREAK THE CODE AND IT WON'T WORK,
    if(window.history.replaceState) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>
</html>