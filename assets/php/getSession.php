<?php
// initialize the session and show errors
session_start();

$session->playerID = $_SESSION['playerID'];
$session->gameID = $_SESSION['gameID'];
$session->role = $_SESSION['role'];
$session->error = $_SESSION['error'];

echo json_encode($session);
exit;
?>