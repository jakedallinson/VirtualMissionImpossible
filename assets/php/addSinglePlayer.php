<?php

require_once('database.php');

$gameID = $_POST["gameID"];
$admin = $_POST["admin"];
$role = $_POST["role"];

// create new player
$newID = queryAddPlayer($gameID);
if (!$newID) {
    echo "error1";
    exit;
}
// add player to the game
$result = queryAddPlayerGame($newID, $gameID, $role, $admin);
if (!$result) {
    echo "error";
    exit;
}
// allow player to clear first checkpoint
$checkpoints = queryGameCheckpoints($gameID);
if (count($checkpoints) > 0) {
    $result = queryClearCheckpoint($newID, strval($checkpoints[0]["ID"]));
    if (!$result) {
        echo "error";
        exit;
    }
}

echo json_encode($newID);

?>
