<?php

// connect to the database
require_once('database.php');

$playerID = $_POST["playerID"];
$gameID = $_POST["gameID"];
$player = queryPlayerGame($playerID, $gameID)[0];

// echo the json results
echo json_encode($player);

?>