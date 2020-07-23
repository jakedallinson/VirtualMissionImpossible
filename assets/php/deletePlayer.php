<?php

require_once('database.php');

$playerID = $_POST["playerID"];
$results = queryDeletePlayer($playerID);

echo json_encode($results);

?>