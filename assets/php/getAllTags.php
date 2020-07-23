<?php

// connect to the database
require_once('database.php');

$gameID = $_POST["gameID"];
$tags = queryGameTags($gameID);

// echo the json results
echo json_encode($tags);

?>