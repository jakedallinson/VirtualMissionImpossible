<?php

// connect to the database
require_once('database.php');

$runnerID = $_POST["playerID"];
$gameID = $_POST["gameID"];

// checkpoints
$allCheckpoints = queryGameCheckpoints($gameID);
$lastCheckpoint = queryRunnerLastCheckpoint($runnerID, $gameID);
$nextCheckpoint = null;
//$nextNum = strval($lastCheckpoint["number"]) + 1;
foreach ($allCheckpoints as $checkpoint) {
    if (strval($lastCheckpoint["number"]) + 1 == $checkpoint["number"]) {
        $nextCheckpoint = $checkpoint;
    }
}

$stats->runner = queryPlayerGame($runnerID, $gameID)[0];
//$stats->completedCheckpoints = queryRunnerCheckpoints($runnerID, $gameID);
$stats->lastCheckpoint = $lastCheckpoint;
$stats->nextCheckpoint = $nextCheckpoint;
$stats->tags = queryRunnerTags($runnerID, $gameID);

// echo the json results
echo json_encode($stats);

?>