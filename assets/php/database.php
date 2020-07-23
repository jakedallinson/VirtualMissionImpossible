<?php

// make the database connection object
function connectDB()
{
    $mysqli = mysqli_connect('172.26.2.25', 'brian', 'eDyulloTI8y2XzYe', 'vmi', '3306');
    if (mysqli_connect_errno()) {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
        exit();
    }
    return $mysqli;
}

// PLAYERS

function getPlayers($params)
{
    $mysqli = connectDB();
    if ($params["role"]) {
        // get all players of a certain role
        $preparedSQL = "select PG.game_id, P.ID, P.name, PG.role, PG.status, PG.clearance
                        from player_game PG inner join player P
                        on PG.player_id = P.ID
                        where PG.game_id = ? and PG.role = ?";
        if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
            mysqli_stmt_bind_param($stmt, "is", $params["gameID"], $params["role"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $gID, $pID, $name, $role, $status, $clearance);
            $results = array();
            while(mysqli_stmt_fetch($stmt)) {
                $results []= [
                    "gameID"    => $gID,
                    "playerID"  => $pID,
                    "name"      => $name,
                    "role"      => $role,
                    "status"    => $status,
                    "clearance" => $clearance
                ];
            }
            mysqli_stmt_close($stmt);
            return $results;
        } else {
            return array();
        }
    } else if ($params["playerID"]) {
        // get a single player
        $preparedSQL = "select PG.game_id, P.ID, P.name, PG.role, PG.status, PG.clearance
                        from player_game PG inner join player P
                        on PG.player_id = P.ID
                        where PG.player_id = ? and PG.game_id = ?";
        if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
            mysqli_stmt_bind_param($stmt, "ii", $params["playerID"], $params["gameID"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $gID, $pID, $name, $role, $status, $clearance);
            $results = array();
            while(mysqli_stmt_fetch($stmt)) {
                $results []= [
                    "gameID"    => $gID,
                    "playerID"  => $pID,
                    "name"      => $name,
                    "role"      => $role,
                    "status"    => $status,
                    "clearance" => $clearance
                ];
            }
            mysqli_stmt_close($stmt);
            return $results;
        } else {
            return array();
        }
    } else {
        // get all players
        $preparedSQL = "select PG.game_id, P.ID, P.name, PG.role, PG.status, PG.clearance
                        from player_game PG inner join player P
                        on PG.player_id = P.ID
                        where PG.game_id = ?";
        if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
            mysqli_stmt_bind_param($stmt, "i", $params["gameID"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $gID, $pID, $name, $role, $status, $clearance);
            $results = array();
            while(mysqli_stmt_fetch($stmt)) {
                $results []= [
                    "gameID"    => $gID,
                    "playerID"  => $pID,
                    "name"      => $name,
                    "role"      => $role,
                    "status"    => $status,
                    "clearance" => $clearance
                ];
            }
            mysqli_stmt_close($stmt);
            return $results;
        } else {
            return array();
        }
    }
}

// CHECKPOINTS

function queryUpdateStatus($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "update player_game
                    set status = not status
                    where player_id = ? and game_id = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $gameID);
        // echo the response from execution and close the connection
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return ($result == 1);
    } else {
        return false;
    }
}

function queryGetPlayers($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select P.ID, PG.game_id, P.name, PG.role, PG.status, PG.game_admin
                    from player_game PG inner join player P
                    on PG.player_id = P.ID
                    where cast(PG.player_id as char) like ? and PG.game_id = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "si", $playerID, $gameID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $pID, $gID, $name, $role, $status, $gameAdmin);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "playerID"  => $pID,
                "gameID"    => $gID,
                "name"      => $name,
                "role"      => $role,
                "status"    => $status,
                "gameAdmin" => $gameAdmin
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}


function queryGameCheckpoint($gameID, $next)
{
    $mysqli = connectDB();
    $preparedSQL = "select C.ID, GC.number, C.name
                    from checkpoint C inner join game_checkpoint GC
                    on C.ID = GC.checkpoint_id
                    where GC.game_id = ? and GC.number = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $gameID, $next);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $ID, $number, $name);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "ID"        => $ID,
                "number"    => $number,
                "name"      => $name
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryClearCheckpoint($playerID, $checkpointID)
{
    $mysqli = connectDB();
    $preparedSQL = "insert into player_checkpoint
                    values (?, ?, NOW())";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $checkpointID);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        return false;
    }
}

function queryPlayerGame($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select * from player_game
                    where player_id = ? and game_id = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $gameID);
        mysqli_stmt_execute($stmt);      
        mysqli_stmt_bind_result($stmt, $pID, $gID, $role, $status, $gameAdmin);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "playerID"  => $pID,
                "gameID"    => $gID,
                "role"      => $role,
                "status"    => $status,
                "gameAdmin" => $gameAdmin
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryTaggerTags($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select * from tag
                    where tagger_id = ? and game_id = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $gameID);
        mysqli_stmt_execute($stmt);      
        mysqli_stmt_bind_result($stmt, $tID, $rID, $gID, $time, $location);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "taggerID"  => $tID,
                "runnerID"  => $rID,
                "gameID"    => $gID,
                "time"      => $time,
                "location"  => $location
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryAddTag($taggerID, $runnerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "insert into tag
                    values (?, ?, ?, NOW(), null)";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "iii", $taggerID, $runnerID, $gameID);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return ($result == 1);
    } else {
        return false;
    }
}

function queryPlayer($playerID)
{
    $mysqli = connectDB();
    $preparedSQL = "select player_id, game_id, role from player_game
                    where player_id = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "i", $playerID);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $pID, $gID, $role);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "playerID"  => $pID,
                "gameID"    => $gID,
                "role"      => $role,
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryRunnerStatus($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select status from player_game
                    where player_id = ? and game_id = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $gameID);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $status);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= $status;
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryRunnerCheckpoints($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select GC.number, C.ID, C.name
                    from game_checkpoint GC
                        inner join checkpoint C on GC.checkpoint_id = C.ID
                        inner join player_checkpoint PC on C.ID = PC.checkpoint_id
                    where PC.player_id = ? and GC.game_id = ?
                    order by GC.number desc";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $gameID);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $number, $id, $name);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "number"    => $number,
                "id"        => $id,
                "name"      => $name,
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryRunnerLastCheckpoint($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select GC.number, C.ID, C.name
                    from game_checkpoint GC
                        inner join checkpoint C on GC.checkpoint_id = C.ID
                        inner join player_checkpoint PC on C.ID = PC.checkpoint_id
                    where PC.player_id = ? and GC.game_id = ?
                    order by GC.number desc
                    limit 1";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $gameID);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $number, $id, $name);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "number"    => $number,
                "id"        => $id,
                "name"      => $name,
            ];
        }
        mysqli_stmt_close($stmt);
        return $results[0];
    } else {
        return array();
    }
}

function queryRunnerTags($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select tagger_id, time, location from tag
                    where runner_id = ? and game_id = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $gameID);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $taggerID, $time, $location);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "taggerID"  => $taggerID,
                "time"      => $time,
                "location"  => $location,
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryGameName($gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select name from game
                    where ID = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "i", $gameID);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $name);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "name"  => $name,
            ];
        }
        mysqli_stmt_close($stmt);
        return $results[0]["name"];
    } else {
        return null;
    }
}

function queryGameCheckpoints($gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select C.ID, GC.number, C.name
                    from game_checkpoint GC inner join checkpoint C
                    on GC.checkpoint_id = C.ID
                    where GC.game_id = ?
                    order by GC.number asc";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "i", $gameID);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $id, $number, $name);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "ID"  => $id,
                "number"  => $number,
                "name"  => $name,
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryGamePlayerRoles($gameID, $roleName)
{
    $mysqli = connectDB();
    $preparedSQL = "select P.ID, P.name
                    from player P inner join player_game PG
                    on P.ID = PG.player_id
                    where PG.game_id = ? and PG.role = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "is", $gameID, $roleName);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $id, $name);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "ID"  => $id,
                "name"  => $name
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryGameRunners($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select P.ID, P.name
                    from player_game PG inner join player P
                    on PG.player_id = P.ID
                    where cast(PG.player_id as char) like ? and PG.game_id = ? and PG.role = 'RUNNER'";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "si", $playerID, $gameID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $pID, $name);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "playerID"  => $pID,
                "name"      => $name
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryGameRunner($playerID, $gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select P.ID, P.name
                    from player_game PG inner join player P
                    on PG.player_id = P.ID
                    where PG.player_id = ? and PG.game_id = ? and PG.role = 'RUNNER'";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "ii", $playerID, $gameID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $pID, $name);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "playerID"  => $pID,
                "name"      => $name
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryGameTags($gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "select tagger_id, runner_id, time, location
                    from tag
                    where game_id = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "i", $gameID);
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $tagID, $runID, $time, $location);
        $results = array();
        while(mysqli_stmt_fetch($stmt)) {
            $results []= [
                "taggerID"  => $tagID,
                "runnerID"  => $runID,
                "time"  => $time,
                "location"  => $location,
            ];
        }
        mysqli_stmt_close($stmt);
        return $results;
    } else {
        return array();
    }
}

function queryAddSingleRunner($ID, $name)
{
    $mysqli = connectDB();
    $preparedSQL = "INSERT INTO `player` (`ID`, `name`) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "iii", $ID, $name);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return ($result == 1);
    } else {
        return false;
    }
}

function queryAddPlayer($gameID)
{
    $mysqli = connectDB();
    $preparedSQL = "insert into player values (null, null)";
    $stmt = mysqli_prepare($mysqli, $preparedSQL);
    mysqli_stmt_execute($stmt);
    $createdID = mysqli_insert_id($mysqli);
    mysqli_stmt_close($stmt);
    return $createdID;
}

function queryAddPlayerGame($newID, $gameID, $role, $admin)
{
    $mysqli = connectDB();
    $preparedSQL = "insert into player_game values (?, ?, ?, 1, ?)";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "iisi", $newID, $gameID, $role, $admin);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return ($result == 1);
    } else {
        return false;
    }
}

function queryDeletePlayer($playerID)
{
    $mysqli = connectDB();
    $preparedSQL = "delete from player
                    where ID = ?";
    if ($stmt = mysqli_prepare($mysqli, $preparedSQL)) {
        mysqli_stmt_bind_param($stmt, "i", $playerID);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return ($result == 1);
    } else {
        return false;
    }
}

?>