<?php

// connect to the database
require_once('database.php');

$players = getPlayers($_POST);

// echo the json results
echo json_encode($players);

?>