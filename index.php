<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agenda</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- JAVA SCRIPT -->
    <script src="agenda.js" defer></script>

</head>
<body>
    <?php require_once 'connectAgenda.php'; ?>


    <h1>add to Agenda:</h1>
    <form method="POST" action="index.php">
        <label for="agenda-naam">AgendaNaam: </label>
        <input type="text" name="agenda-naam" placeholder="AgendaNaam" value="test"><br>

        <label for="agenda-omschrijving">AgendaOmschrijving: </label>
        <input type="text" name="agenda-omschrijving" placeholder="AgendaOmschrijving" value="test"><br>

        <label for="agenda-start-datum">AgendaDatum: </label>
        <input type="date" name="agenda-start-datum" placeholder="AgendaDatum" id="agenda-start-date"><br>

        <label for="agenda-start-tijd">AgendaStartTijd: </label>
        <input type="time" name="agenda-start-tijd" placeholder="AgendaStartTijd" value="10:00" id="agenda-start-time"><br>

        <label for="agenda-eind-tijd">AgendaEindTijd: </label>
        <input type="time" name="agenda-eind-tijd" placeholder="AgendaEindTijd" value="17:00" id="agenda-eind-time"><br>

        <label for="agenda-functie">Kies een functie: </label>
        <select name="agenda-functie">
            <option value="werk">Werk</option>
            <option value="school">School</option>
            <option value="prive">Prive</option>
        </select><br>

        <label for="agenda-kleur">AgendaKleur: </label>
        <input type="color" name="agenda-kleur" placeholder="AgendaKleur"><br>

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
            //force the user to login (: man I can be so mean sometimes, or better said, I can be so lazy sometimes (: haha look table flip (╯°□°）╯︵ ┻━┻ )
            header("Location: login.php");
            exit();
        }

        $username = $_SESSION['username'];
        $userID = $_SESSION['userID'];

        echo "<h1>Welkom $username, $userID </h1>";

        //add to agenda
        if(isset($_POST['agenda-submit'])){
            $agenda_naam = $_POST['agenda-naam'];
            $agenda_omschrijving = $_POST['agenda-omschrijving'];
            $agenda_start_datum = $_POST['agenda-start-datum'];
            $agenda_eind_datum = $_POST['agenda-start-datum'];
            $agenda_start_tijd = $_POST['agenda-start-tijd'];
            $agenda_eind_tijd = $_POST['agenda-eind-tijd'];
            $agenda_functie = $_POST['agenda-functie'];
            $agenda_kleur = $_POST['agenda-kleur'];

            //round the start and end time to the nearest 15 minutes
            $agenda_start_tijd = date("H:i", round(strtotime($agenda_start_tijd) / 900 ) * 900);
            $agenda_eind_tijd = date("H:i", round(strtotime($agenda_eind_tijd) / 900 ) * 900);


            //if the end time is before the start time, invert the start and end time
            if($agenda_start_tijd > $agenda_eind_tijd){
                $agenda_start_tijd = $_POST['agenda-eind-tijd'];
                $agenda_eind_tijd = $_POST['agenda-start-tijd'];
            }

            //if the start time is the same as the end time, set the end time to 15 minutes later
            if($agenda_start_tijd == $agenda_eind_datum){
                $agenda_eindDatum = date("H:i", strtotime($agenda_eind_tijd) + 900);
            }

            //if the start time is 00:00, set the end time to 00:01
            if($agenda_start_tijd == "00:00"){
                $agenda_start_tijd = "00:00";
            }

            //if the end time is 00:00, set the end time to 23:59
            if($agenda_eind_tijd == "00:00"){
                $agenda_eind_tijd = "23:59";
            }

            //if the start time the same as the end time, set the end time to 15 minutes later
            if($agenda_start_tijd == $agenda_eind_tijd){
                $agenda_eind_tijd = date("H:i", strtotime($agenda_eind_tijd) + 900);
            }

            //run the query in the database
            $sqlAgenda = "INSERT INTO agenda (id, userID, naam, omschrijving, startDatum, eindDatum, startTijd, eindTijd, taak, functie, kleur) VALUES ('', '$userID', '$agenda_naam' , '$agenda_omschrijving', '$agenda_start_datum', '$agenda_eind_datum', '$agenda_start_tijd', '$agenda_eind_tijd', 'false', '$agenda_functie', '$agenda_kleur')";
            $result = mysqli_query($connection, $sqlAgenda);

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

    echo "<input type='hidden' id='week_start' value='$week_start'>";

    // Query the database for all agenda items within the current week range
    $sqlAgenda = "SELECT * FROM agenda WHERE userID = '{$_SESSION['userID']}' AND (startDatum BETWEEN '{$_SESSION['week_start']}' AND '{$_SESSION['week_end']}') ORDER BY startDatum ASC, startTijd ASC";
    $result = mysqli_query($connection, $sqlAgenda);
    $resultCheck = mysqli_num_rows($result);

    // Display the week start and end dates
    echo "<div class='agenda-header'>";
        //Display the week start and end dates
        for ($i = 0; $i < 7; $i++) {
            $date = date('jS M', strtotime($_SESSION['week_start'] . ' +' . $i . ' days'));
            echo "<div class='agenda-day'>$date</div>";
        }
    echo "</div>";

    // If there are agenda items in the current week range, display them

    echo "<div class='agenda-wrapper'>";
    if($resultCheck > 0){

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

    }
    //close the agenda container
    echo "</div>";

    //delete button is pressed
    if(isset($_POST['agenda-delete'])){
        $id = $_POST['id'];
        $sqlAgenda = "DELETE FROM agenda WHERE id = '$id'";
        $result = mysqli_query($connection, $sqlAgenda);

        //refresh the page
        echo "<script>window.location.href = 'index.php';</script>";
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