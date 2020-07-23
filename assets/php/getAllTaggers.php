<?php

// connect to the database
require_once('database.php');

$gameID = $_POST["gameID"];
$taggers = queryGamePlayerRoles($gameID, "TAGGER");

// echo the json results
echo json_encode($taggers);

?>