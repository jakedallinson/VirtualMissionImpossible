<?php

require_once('database.php');

$playerID = $_POST["playerID"];
$gameID = $_POST["gameID"];

// get the players last checkpoint
$lastCheckpoint = queryRunnerLastCheckpoint($playerID, $gameID);
$nextNum = strval($lastCheckpoint["number"]) + 1;

// find the next checkpoint and send its id to be cleared
$nextCheckpoint = queryGameCheckpoint($gameID, $nextNum);
$results = queryClearCheckpoint($playerID, strval($nextCheckpoint[0]["ID"]));

echo json_encode($results);

?>