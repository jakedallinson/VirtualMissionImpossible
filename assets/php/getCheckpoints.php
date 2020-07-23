<?php

require_once('database.php');

$gameID = $_POST['gameID'];
$results = queryGameCheckpoints($gameID);

echo json_encode($results);

?>