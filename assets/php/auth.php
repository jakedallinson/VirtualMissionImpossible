<?php
// initialize the session
// session_start();

// require_once('database.php');

// function isLogin()
// {
//     $playerID = $_SESSION['playerID'];
//     $gameID = $_SESSION['gameID'];
//     if (isset($playerID) && isset($gameID))
//     {
//         $results = queryPlayerGame($playerID, $gameID);
//         return (count($results) == 1);
//     }
//     return False;
// }

// function isMaster()
// {
//     $playerID = $_SESSION['playerID'];
//     $gameID = $_SESSION['gameID'];
//     $results = queryPlayerGame($playerID, $gameID);
//     //echo json_encode($results);
//     if (count($results) == 1) {
//         return ($results[0]["gameAdmin"] == "1");
//     }
//     return False;
// }

// function loginPlayer($playerID)
// {
//     $results = queryPlayer($playerID);
//     if (count($results) == 1) {
//         $_SESSION['playerID'] = $results[0]["playerID"];
//         $_SESSION['gameID'] = $results[0]["gameID"];
//         $_SESSION['role'] = $results[0]["role"];
//         return True;
//     } else {
//         $_SESSION['error'] = "Oh no! Issues logging in player " . strval($playerID);
//         return False;
//     }
// }

?>