<?php
// MASTER PAGE
// initialize the session and show errors
session_start();
if (isset($_SESSION['error'])) {
  echo $_SESSION['error'];
  $_SESSION['error'] = null;
}

// authetication functions
require_once('../assets/php/auth.php');

// authenticate player
if (!isLogin()) {
    $_SESSION['error'] = "Oh no! You are not logged in.";
    header("location: ../login");
} else {
    if (!isMaster()) {
        $_SESSION['error'] = "Oh no! You are not a game master.";
        header("location: ../");
    }
}

?>

<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="/assets/js/master.js"></script>
        <link rel="stylesheet" type="text/css" href="/assets/css/main.css">
        <meta http-equiv="refresh" content="60">
    </head>
    <body>
        <!-- header -->
        <div class="header container">
            <div class="title-text container">
                <h2>Master View - Player <span id="playerID"></span></h2>
            </div>
            <div class="nav-text container">
                <a href="/" class="back-link">Back to Main</a>
                <a href="/logout">Logout</a>
            </div>
        </div>
        <div class="master-main master-container">
            <div class="master-stats">
                <h3>Game <span id="gameID"></span>, <span id="gameName"></span></h3>
                <p onclick="showCheckpoints()">Checkpoints: <span id="numCheckpoints"></span></p>
                <p onclick="showRunners()">Runners: <span id="numRunners"></span></p>
                <p onclick="showTaggers()">Taggers: <span id="numTaggers"></span></p>
                <p onclick="showTags()">Tags: <span id="numTags"></span></p>
                <p class="change-log">Logs: <span id="changeLog"></span></p>
            </div>
            <div>
                <h3>Stats <span style="color:white" id="searchResultsHeader"></span></h3>
                <table id="searchResults"></table>
            </div>
            <div>
                <!-- create players -->
                <h3>Create Players</h3>
                <input type="text" class="tag-search" id="createNPlayerInput"><span style="color:white"> (n players)</span><br><br>
                <select id="createPlayerRole">
                    <option value="runner">Runner</option>
                    <option value="tagger">Tagger</option>
                </select>
                <input type="checkbox" id="createPlayerAdmin" unchecked><span style="color: white"> Admin?</span><br>
                <button type="button" id="createPlayerButton">Create Single Player</button>
                <button type="button" id="createNPlayerButton" disabled>Create n Players</button>
                <p id="createResult"></p>
            
                <!-- delete players -->
                <h3>Delete Runners</h3>
                <input type="text" class="tag-search" id="deletePlayerInput">
                <button type="button" id="deletePlayerButton" disabled>Delete Runner</button>
                <p id="deleteResult"></p>
            </div>
        </div>
    </body>
</html>