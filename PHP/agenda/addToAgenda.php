<?php
if(isset($_POST['agenda-submit'])) {
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

    //insert the data into the database
    $sqlAgenda = "INSERT INTO agenda (id, userID, naam, omschrijving, startDatum, eindDatum, startTijd, eindTijd, taak, functie, kleur) VALUES ('', '$userID', '$agenda_naam' , '$agenda_omschrijving', '$agenda_start_datum', '$agenda_eind_datum', '$agenda_start_tijd', '$agenda_eind_tijd', 'false', '$agenda_functie', '$agenda_kleur')";
    $result = mysqli_query($connection, $sqlAgenda);
}