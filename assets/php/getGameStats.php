<?php

require_once('database.php');

$gameID = $_POST["gameID"];

$gameStats->ID = $gameID;
$gameStats->name = queryGameName($gameID);
$gameStats->checkpoints = queryGameCheckpoints($gameID);
$gameStats->runners = queryGamePlayerRoles($gameID, "RUNNER");
$gameStats->taggers = queryGamePlayerRoles($gameID, "TAGGER");

// echo the json results
echo json_encode($gameStats);

?>