<?php
    if(isset($_POST['agenda-submit'])) {
        //connect to the database
        //require_once "connectDatabase.php";

        //get the input data
        $agenda_naam = $_POST['agenda-naam'];
        $agenda_omschrijving = $_POST['agenda-omschrijving'];
        $agenda_start_datum = $_POST['agenda-start-datum'];
        $agenda_eind_datum = $_POST['agenda-start-datum'];
        $agenda_start_tijd = $_POST['agenda-start-tijd'];
        $agenda_eind_tijd = $_POST['agenda-eind-tijd'];
        $agenda_functie = $_POST['agenda-functie'];
        $agenda_kleur = $_POST['agenda-kleur'];

        //round the start and end time to the nearest 15 minutes
        $agenda_start_tijd = date("H:i", round(strtotime($agenda_start_tijd) / 900) * 900);
        $agenda_eind_tijd = date("H:i", round(strtotime($agenda_eind_tijd) / 900) * 900);

        //if the end time is before the start time, invert the start and end time
        if ($agenda_start_tijd > $agenda_eind_tijd) {
            $agenda_start_tijd = $_POST['agenda-eind-tijd'];
            $agenda_eind_tijd = $_POST['agenda-start-tijd'];
        }

        //if the start time is the same as the end time, set the end time to 15 minutes later
        if ($agenda_start_tijd == $agenda_eind_datum) {
            $agenda_eindDatum = date("H:i", strtotime($agenda_eind_tijd) + 900);
        }

        //if the end time is 00:00, set the end time to 23:59
        if ($agenda_eind_tijd == "00:00") {
            $agenda_eind_tijd = "23:59";
        }

        //prevent sql injection
        $agenda_naam = mysqli_real_escape_string($connection, $agenda_naam);
        $agenda_omschrijving = mysqli_real_escape_string($connection, $agenda_omschrijving);
        $agenda_start_datum = mysqli_real_escape_string($connection, $agenda_start_datum);
        $agenda_eind_datum = mysqli_real_escape_string($connection, $agenda_eind_datum);
        $agenda_start_tijd = mysqli_real_escape_string($connection, $agenda_start_tijd);
        $agenda_eind_tijd = mysqli_real_escape_string($connection, $agenda_eind_tijd);
        $agenda_functie = mysqli_real_escape_string($connection, $agenda_functie);
        $agenda_kleur = mysqli_real_escape_string($connection, $agenda_kleur);

        //insert the data into the database

        $sqlAgenda = "INSERT INTO agenda (id, userID, naam, omschrijving, startDatum, eindDatum, startTijd, eindTijd, taak, functie, kleur) VALUES ('', '$userID', '$agenda_naam' , '$agenda_omschrijving', '$agenda_start_datum', '$agenda_eind_datum', '$agenda_start_tijd', '$agenda_eind_tijd', 'false', '$agenda_functie', '$agenda_kleur')";

        //run the query in the database
        $result = mysqli_query($connection, $sqlAgenda);

        //get tha data from the database
        $sqlAgenda = "SELECT * FROM agenda WHERE userID = '$userID' AND naam = '$agenda_naam' AND omschrijving = '$agenda_omschrijving' AND startDatum = '$agenda_start_datum' AND eindDatum = '$agenda_eind_datum' AND startTijd = '$agenda_start_tijd' AND eindTijd = '$agenda_eind_tijd' AND functie = '$agenda_functie' AND kleur = '$agenda_kleur'";
        $result = mysqli_query($connection, $sqlAgenda);

        while ($row = mysqli_fetch_assoc($result)) {
            $agendaItemID = $row['id'];
            $agendaItemUserID = $row['userID'];
            $agendaItemNaam = $row['naam'];
            $agendaItemOmschrijving = $row['omschrijving'];
            $agendaItemStartDatum = $row['startDatum'];
            $agendaItemEindDatum = $row['eindDatum'];
            $agendaItemStartTijd = $row['startTijd'];
            $agendaItemEindTijd = $row['eindTijd'];
            $agendaItemtaak = $row['taak'];
            $agendaItemFunctie = $row['functie'];
            $agendaItemKleur = $row['kleur'];


            writeToCustomDB("agenda", $agendaItemID, $agendaItemUserID, $agendaItemNaam, $agendaItemOmschrijving, $agendaItemStartDatum, $agendaItemEindDatum, $agendaItemStartTijd, $agendaItemEindTijd, $agendaItemtaak, $agendaItemFunctie, $agendaItemKleur);
        }
    }