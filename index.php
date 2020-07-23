<?php
// initialize the session and show errors
session_start();
if (isset($_SESSION['error'])) {
  echo $_SESSION['error'];
  $_SESSION['error'] = null;
}

require_once('assets/php/auth.php');

// authenticate player
if (!isLogin()) {
  $_SESSION['error'] = "Oh no! You are not logged in.";
  header("location: /login");
}
?>

<html>
  <head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/assets/js/index.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <meta http-equiv="refresh" content="60">
    <title>Virtual Mission Impossible</title>
  </head>
  <body>
    <!-- header -->
    <div class="header container">
      <div class="title-text container">
        <h2 id="playerType"><h2>&nbsp;<span class="playerID"></span>
      </div>
      <div class="nav-text container">
        <a href="/master" id="master" class="master">Master</a>
        <a href="/logout">Logout</a>
      </div>
    </div>
    <!-- runner fields -->
    <div class="fields container">
      <div id="runner" style="display: none;">
        <p class="runner-message" id="runnerMessage"><p>
        <p>Game: <span id="runnerGameID"></span></p>
        <p>Status: <span id="runnerStatus"></span></p>
        <p>Next checkpoint: <span id="runnerNextCheckpoint"></span></p>
        <p>Last checkpoint: <span id="runnerLastCheckpoint"></span></p>
        <p>Total tags: <span id="runnerTags"></span></p>
      </div>
      <!-- tagger fields -->
      <div id="tagger" style="display: none;">
        <p>Game: <span id="taggerGameID"></span></p>
        <p>Total tags: <span id="taggerTags">TODO</span></p>
        <div>
          <p>Enter player ID: </p>
          <input class="tag-search" type="text" id="input">
          <p class="tagged-box" id="playerTaggedID"></p>
          <button type="button" id="tagButton" disabled></button>
          <button type="button" id="checkpointButton" disabled></button>
          <table id="searchResults"></table>
        </div>
      </div>
    </div>
  </body>
</html>