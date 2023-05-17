<?php
// Query id the user is authorized to view the agenda
$sqlAccess = "SELECT * FROM access WHERE userID = '$userID'";
$resultAccess = mysqli_query($connection, $sqlAccess);
$resultCheckAccess = mysqli_num_rows($resultAccess);

if($resultCheckAccess > 0) {
    while ($accessRow = mysqli_fetch_assoc($resultAccess)) {

        $userID = $accessRow['accesUserID'];

        // Query the database for all agenda items within the current week range
        $sqlAgenda = "SELECT * FROM agenda WHERE (startDatum >= '$week_start' AND eindDatum <= '$week_end') AND userID = '$userID'";
        $result = mysqli_query($connection, $sqlAgenda);
        $resultCheck = mysqli_num_rows($result);

        if ($resultCheck > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

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

                echo "<div class='agenda-item agenda-date{$dayDifference} {$agenda_item_functie} userID{$userID}' id='agendaID{$agenda_item_id1} ' style='background-color:{$agenda_item_functie}; grid-row-start:{$startRow};grid-row-end:{$endRow};'>";
                    echo "<h1 class='agenda-item-header'>{$agenda_item_naam}</h1>";
                    echo "<p class='agenda-item-omschrijving'>{$agenda_item_omschrijving}</p>";
                    echo "<p>{$agenda_item_startTijd} - {$agenda_item_eindTijd}</p>";

                        echo "<div class='agenda-form-wrapper'>";

                            //check if the user is the owner of the agenda item
                            if($userID == $_SESSION['userID']) {
                                //delete button
                                echo "<form method='POST' action='index.php'>";
                                    echo "<input type='hidden' name='id' value='{$agenda_item_id1}'>";
                                    echo "<button type='submit' name='agenda-delete' class='agenda-delete'>Verwijder</button>";
                                echo "</form>";
                            }
                        echo "</div>";

                //close the agenda item
                echo "</div>";
            }
        }

        $sql = "SELECT * FROM login WHERE ID = '$userID'";
        $result = mysqli_query($connection, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
            while ($rowUsers = mysqli_fetch_assoc($result)) {
                $user = $rowUsers['username'];
                $ID = $rowUsers['id'];

                echo "<script>document.getElementsByClassName('agenda-hide-wrapper')[0].innerHTML += `<label for='{$user}'>{$user}</label><input type='checkbox' value='userID{$ID}' name'{$user}' checked class='agenda-view-users'><br>`</script>";
            }
        }
        $userID = $_SESSION['userID'];
    }

} 