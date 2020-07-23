<?php

// connect to the database
require_once('database.php');

$playerID = $_POST["playerID"];
$gameID = $_POST["gameID"];

$stats->tagger = queryPlayerGame($playerID, $gameID)[0];
$stats->tags = queryTaggerTags($playerID, $gameID);

// echo the json results
echo json_encode($stats);

?>