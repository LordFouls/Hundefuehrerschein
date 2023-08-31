<?php
/*Gibt eine zufällige Zeile einer Tabelle zurück,
   Die Gewichtung der Zeilen müssen per "weight" angegeben sein
 */
function weightedRandom($table)/*von Phillip Laue*/
{
    $summe = 0;
    foreach ($table as $key => $row) {
        $summe += $row["weight"];
    }
    $random = rand(0,$summe-1);
 
    foreach ($table as $key => $row) {
        $random -= $row["weight"];
        if($random < 0) return $row;
    }
}
 
/*
Beispielanwendung der Funktion:
 
$list = array(
	array("value" => "ja", "weight" => "100"),
	array("value" => "nein", "weight" => "20"),
	array("value" => "vielleicht", "weight" => "80")
	);
	
echo weightedRandom($list)["value"];
*/
?>