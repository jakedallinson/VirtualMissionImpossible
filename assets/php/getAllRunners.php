<?php

// connect to the database
require_once('database.php');

$gameID = $_POST["gameID"];
$runners = queryGamePlayerRoles($gameID, "RUNNER");

// echo the json results
echo json_encode($runners);

?>