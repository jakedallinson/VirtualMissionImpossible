<?php

require_once('database.php');

$taggerID = $_POST["taggerID"];
$runnerID = $_POST["runnerID"];
$gameID = $_POST["gameID"];
$results = queryAddTag($taggerID, $runnerID, $gameID);

echo json_encode($results);

?>
