<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agenda</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/root.css">
    <link rel="stylesheet" href="../CSS/agenda.css">

    <!-- JAVA SCRIPT -->
    <script src="../JS/agenda.js" defer></script>

</head>
<body>
    <?php include 'connectAgenda.php'; ?>

    <div class="header">
        <h1>Agenda:</h1>
    </div>

<!--    <div class="filter-agenda-wrapper">-->
<!--        <div class="filter-agenda">-->
<!--            <h2>Filter agenda:</h2>-->
<!--            <form method="post" class="filter-agenda-form">-->
<!--                <label for="filter">Filter:</label>-->
<!--                <select name="filter" class="agenda-filter-function">-->
<!--                    <option value="all">Alles</option>-->
<!--                    <option value="werk">Werk</option>-->
<!--                    <option value="school">School</option>-->
<!--                    <option value="prive">Persoonlijk</option>-->
<!--                </select>-->
<!--            </form>-->
<!--        </div>-->
<!--    </div>-->


    <form method="post" class="week-buttons">
         <input type="submit" name="prev_week" value="Vorige week" class="week-button">
         <input type="submit" name="this_week" value="Deze week" class="week-button">
         <input type="submit" name="next_week" value="Volgende week" class="week-button">
    </form>

    <form method="post" class="log-out-form">
        <input type="submit" name="logout" value="Log uit" class="log-out">
    </form>

    <?php include 'add_to_agenda.php'; ?>

<!--    <h1>Agenda:</h1>-->
    <?php
        if(isset($_POST['logout'])) {
            session_destroy();
            header('Location: login.php');
        }

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

        // Check if this week button has been clicked
        if(isset($_POST['this_week'])) {
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
        }

        // Check if the next week button has been clicked
        if(isset($_POST['next_week'])) {
            // Move the week start and end dates forward by 7 days
            $week_start = $_SESSION['week_start'] = date('Y-m-d', strtotime('+1 week', strtotime($_SESSION['week_start'])));
            $week_end = $_SESSION['week_end'] = date('Y-m-d', strtotime('+1 week', strtotime($_SESSION['week_end'])));
        }

        echo "<input type='hidden' id='week_start' value='$week_start'>";

        // Query the database for all agenda items within the current week range and with the selected filter
        $sqlAgenda = "SELECT * FROM agenda WHERE startDatum >= '$week_start' AND eindDatum <= '$week_end'";
        $result = mysqli_query($connection, $sqlAgenda);
        $resultCheck = mysqli_num_rows($result);
        //put the form section back to its selcected value
        if(isset($_POST['filter-submit'])) {
            echo "<script>document.getElementById('filter').value = '{$_POST['filter']}'</script>";
        }

    ?>
    <div class="main-main-agenda-wrapper">

        <div class='agenda-header'>
            <?php
                for ($i = 0; $i < 7; $i++) {
                    $date = date('jS M', strtotime($_SESSION['week_start'] . ' +' . $i . ' days'));

                    // Get the name of the day of the week
                    $day_name = strftime('%A', strtotime($_SESSION['week_start'] . ' +' . $i . ' days'));

                    if($day_name == "Monday") {
                        $day_name = "Maandag";
                    } elseif($day_name == "Tuesday") {
                        $day_name = "Dinsdag";
                    } elseif($day_name == "Wednesday") {
                        $day_name = "Woensdag";
                    } elseif($day_name == "Thursday") {
                        $day_name = "Donderdag";
                    } elseif($day_name == "Friday") {
                        $day_name = "Vrijdag";
                    } elseif($day_name == "Saturday") {
                        $day_name = "Zaterdag";
                    } elseif($day_name == "Sunday") {
                        $day_name = "Zondag";
                    }

                    // If that day is today's day, highlight it and add the name of the day
                    if ($date == date('jS M')) {
                        $date = "<div class='agenda-day current-day'>$date, $day_name</div>";
                    } else {
                        $date = "<div class='agenda-day'>$date, $day_name</div>";
                    }
                    echo $date;
                }
            ?>
        </div>

        <div class='agenda-line-wrapper'>
            <?php
                for($i = 1; $i <= 7; $i++){
                    echo "<div class='agenda-day-line'></div>";
                }
            ?>
        </div>

        <div class='agenda-wrapper'>
            <div class="agenda-times">
                <?php
                for($i = 0; $i <= 23; $i++){
                ?>
                    <div class="time-wrapper">
                        <div class="time-header"><?= $i ?> uur</div>
                        <div class="time-line"></div>
                    </div>
                <?php } ?>
            </div>
        <?php
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
                    $endRow = floor(((strtotime($row['eindTijd']) - strtotime('00:00')) / 900) + 1);

                    //put the values in a variable
                    $agenda_item_id1 = $row['id'];
                    $agenda_item_naam = $row['naam'];
                    $agenda_item_omschrijving = $row['omschrijving'];
                    $agenda_item_startDatum = $row['startDatum'];
                    $agenda_item_startTijd = $row['startTijd'];
                    $agenda_item_eindTijd = $row['eindTijd'];
                    $agenda_item_functie = $row['functie'];
                    $agenda_item_kleur = $row['kleur'];

                    echo "<div class='agenda-item agenda-date{$dayDifference} {$agenda_item_functie}' id='agendaID{$agenda_item_id1}' style='background-color:{$agenda_item_kleur}; grid-row-start:{$startRow};grid-row-end:{$endRow};'>";
                        echo "<h1 class='agenda'>{$agenda_item_naam}</h1>";
                        echo "<p>{$agenda_item_omschrijving}</p>";
                        echo "<p>{$agenda_item_startTijd} - {$agenda_item_eindTijd}</p>";
                        echo "<p>Functie: {$agenda_item_functie}</p>";


                        echo "<div class='agenda-form-wrapper'>";


                            //delete button
                            echo "<form method='POST' action='index.php'>";
                                echo "<input type='hidden' name='id' value='{$agenda_item_id1}'>";
                                echo "<button type='submit' name='agenda-delete' class='agenda-delete'>Verwijder</button>";
                            echo "</form>";
                        echo "</div>";

                    //close the agenda item
                    echo "</div>";
                }
            }
            //close the agenda container
            echo "</div>";
        echo "</div>";

        //delete button is pressed
        if(isset($_POST['agenda-delete'])){
            $id = $_POST['id'];
            $sqlAgenda = "DELETE FROM agenda WHERE id = '$id'";
            $result = mysqli_query($connection, $sqlAgenda);

            //refresh the page
            echo "<script>window.location.href = 'index.php';</script>";
        } ?>


        <?php
            //update button is pressed
            if(isset($_POST['agenda-update'])){
                $id = $_POST['id'];
                $naam = $_POST['naam'];
                $omschrijving = $_POST['omschrijving'];
                $startDatum = $_POST['startDatum'];
                $startTijd = $_POST['startTijd'];
                $eindTijd = $_POST['eindTijd'];
                $functie = $_POST['functie'];
                $kleur = $_POST['kleur'];

                $sqlAgenda = "UPDATE agenda SET naam = '$naam', omschrijving = '$omschrijving', startDatum = '$startDatum', startTijd = '$startTijd', eindTijd = '$eindTijd', functie = '$functie', kleur = '$kleur' WHERE id = '$id'";
                $result = mysqli_query($connection, $sqlAgenda);

                //refresh the page
                echo "<script>window.location.href = 'index.php';</script>";
            }
        ?>
    </div>

    <script>
        //DO NEVER CHANGE, IT WILL BREAK THE CODE AND IT WON'T WORK,
        if(window.history.replaceState) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>
</html>