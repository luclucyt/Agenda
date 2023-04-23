<?php
    function writeToCustomDB($table, ...$data) {
        $table = $_SERVER['DOCUMENT_ROOT']."/database/".$table.".txt";

        $dataToWrite = "";
        foreach ($data as $value){
            $value = str_replace("||", "/||", $value);
            $dataToWrite .= $value."||";
        }

        $dataToWrite .= "\n";

        $tableFile = fopen($table, "a");
        fwrite($tableFile, $dataToWrite);

        fclose($tableFile);
    }

    function removeFromCustomDB($tabel, ...$data){
        $tabel = $_SERVER['DOCUMENT_ROOT']."/database/".$tabel.".txt";

        $dataToRemove = "";
        foreach ($data as $value){
            $value = str_replace("||", "/||", $value);
            $dataToRemove .= $value."||";
        }

        $dataToRemove .= "\n";

        $tabelData = file_get_contents($tabel);

        $tabelData = str_replace($dataToRemove, "", $tabelData);

        file_put_contents($tabel, $tabelData);
    }