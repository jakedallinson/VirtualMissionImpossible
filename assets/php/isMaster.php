<?php

require_once('queries.php');

$playerID = $_POST['playerID'];
$gameID = $_POST['gameID'];
$results = queryPlayerGame($playerID, $gameID);

if (count($results) == 1) {
    echo ($results[0]["gameAdmin"] == "1");
}
echo False;

?>