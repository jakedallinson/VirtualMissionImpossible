<?php

require_once('database.php');

$playerID = $_POST["playerID"];
$gameID = $_POST["gameID"];
$results = queryUpdateStatus($playerID, $gameID);

echo json_encode($results);

?>