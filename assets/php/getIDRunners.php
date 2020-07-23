<?php

require_once('database.php');

$playerID = strval($_POST["playerID"]) . "%";
$gameID = $_POST["gameID"];
$results = queryGameRunners($playerID, $gameID);

echo json_encode($results);
?>