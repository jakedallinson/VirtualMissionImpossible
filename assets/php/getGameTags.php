<?php

require_once('database.php');

$gameID = $_POST['gameID'];
$gameStats->tags = queryGameTags($gameID);

// echo the json results
echo json_encode($gameStats);

?>