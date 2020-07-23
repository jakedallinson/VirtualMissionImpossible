// JQUERY
$(document).ready(function() {

  getSession(function(session) {
    $("#playerID").html(session["playerID"]);
    fillStats(session);
    fillTags(session);
  });

  $("#createPlayerButton").click(function() {
    getSession(function(session) {
      let admin = 0;
      if ($('#createPlayerAdmin').is(':checked')) { admin = 1; }
      let role = $('#createPlayerRole').val().toUpperCase();
      addSinglePlayer(session, admin, role, function(player) {
        $('#changeLog').text(role + " " + player + " created.");
      });
    });
  });

  // create n players
  $("#createNPlayerInput").on("input", function() {
    getSession(function(session) {
      let input = $('#createNPlayerInput').val();
      if (input == "") {
        clearDeleteInputs();
        return;
      }
      $('#createNPlayerButton').prop('disabled', false);
      $('#createPlayerButton').prop('disabled', true);
    });
  });

  // flip player status when tagged
  $("#createNPlayerButton").click( function() {
    getSession(function(session) {
      let input = $('#createNPlayerInput').val();
      if (!/^-{0,1}\d+$/.test(input)) {
        clearDeleteInputs();
        $('#changeLog').text(input + " is not a number");
        return;
      }
      if (parseInt(input) > 100 || parseInt(input) < 1) {
        clearDeleteInputs();
        $('#changeLog').text(input + " too big or too small. Range is [1, 100]");
        return;
      }
      // create n players (TODO: make function)
      clearDeleteInputs();
      $('#changeLog').text("");
      let admin = 0;
      if ($('#createPlayerAdmin').is(':checked')) { admin = 1; }
      let role = $('#createPlayerRole').val().toUpperCase();
      for (let i = 0; i < input; i++) {
        addSinglePlayer(session, admin, role, function(player) {
          $('#changeLog').append(role + " " + player + " created.<br>");
        });
      }
    });
  });

  // delete player
  $("#deletePlayerInput").on("input", function() {
    getSession(function(session) {
      let input = $('#deletePlayerInput').val();
      if (input == "") {
        clearDeleteInputs();
        return;
      }
      // see if player matching id
      getRunner(session, input, function(runners) {
        if (runners.length == 1) {
          $("#deletePlayerInput").css("border", "2px solid green");
          $('#deletePlayerButton').prop('disabled', false);
        } else {
          $("#deletePlayerInput").css("border", "2px solid red");
          $('#deletePlayerButton').prop('disabled', true);
        }
      });
    });
  });

  // delete player when button pressed
  $("#deletePlayerButton").click(function() {
    let playerID = $("#deletePlayerInput").val();
    let url = "http://3.16.235.207/assets/php/deletePlayer.php";
    $.post(url, {playerID: playerID}, function(result) {
      clearDeleteInputs();
      if (result) {
        $('#changeLog').text("Player " + playerID + " successfully deleted.");
      } else {
        $('#changeLog').text("Error deleting layer " + playerID);
      }
    });
  });

});

function getSession(callback)
{
  let url = "http://3.16.235.207/assets/php/getSession.php";
  $.post(url, function(result) {
    let results = JSON.parse(result);
    callback(results);
  });
}

function fillStats(session)
{
  let url = "http://3.16.235.207/assets/php/getGameStats.php";
  $.post(url, {gameID: session["gameID"]}, function(result) {
    let results = JSON.parse(result);
    $("#gameID").html(results["ID"]);
    $("#gameName").html(results["name"]);
    $("#numCheckpoints").html(results["checkpoints"].length);
    $("#numTaggers").html(results["taggers"].length);
    $("#numRunners").html(results["runners"].length);
    $("#changeLog").text("Nothing to report.");
  });
}

function fillTags(session)
{
  let url = "http://3.16.235.207/assets/php/getGameTags.php";
  $.post(url, {gameID: session["gameID"]}, function(result) {
    let results = JSON.parse(result);
    $("#numTags").html(results["tags"].length);
  });
}

function clearDeleteInputs()
{
  $("#deletePlayerInput").val("");
  $("#changeLog").text("Nothing to report.");
  $("#deletePlayerInput").css("border", "2px solid white");
  $('#deletePlayerButton').prop('disabled', true);
  $("#createNPlayerInput").val("");
  $('#createNPlayerButton').prop('disabled', true);
  $('#createPlayerButton').prop('disabled', false);
}

function addSinglePlayer(session, admin, role, callback)
{
  let url = "http://3.16.235.207/assets/php/addSinglePlayer.php";
  $.post(url, {gameID: session["gameID"], admin: admin, role: role}, function(result) {
    let results = JSON.parse(result);
    callback(results)
  });
}

function getRunner(session, ID, callback)
{
  let url = "http://3.16.235.207/assets/php/getIDRunner.php";
  $.post(url, {playerID: ID, gameID: session["gameID"]}, function(result) {
    let results = JSON.parse(result)
    callback(results);
  });
}

function showRunners()
{
  $("#searchResults").empty();
  $("#searchResultsHeader").text("(runners)");
  getSession(function(session) {
    let url = "http://3.16.235.207/assets/php/getAllRunners.php";
    $.post(url, {gameID: session["gameID"]}, function(result) {
      let runners = JSON.parse(result);
      runners.forEach(runner => {
        let html = "<tr>";
        html += "<td>Runner " + runner["ID"] + "</td>";
        html += "<td>" + runner["name"] + "</td>";
        html += "</tr>";
        $("#searchResults").append(html);
      });
    });
  });
}

function showTaggers()
{
  $("#searchResults").empty();
  $("#searchResultsHeader").text("(taggers)");
  getSession(function(session) {
    let url = "http://3.16.235.207/assets/php/getAllTaggers.php";
    $.post(url, {gameID: session["gameID"]}, function(result) {
      let runners = JSON.parse(result);
      runners.forEach(runner => {
        let html = "<tr>";
        html += "<td>Tagger " + runner["ID"] + "</td>";
        html += "<td>" + runner["name"] + "</td>";
        html += "</tr>";
        $("#searchResults").append(html);
      });
    });
  });
}

function showCheckpoints()
{
  $("#searchResults").empty();
  $("#searchResultsHeader").text("(checkpoints)");
  getSession(function(session) {
    let url = "http://3.16.235.207/assets/php/getCheckpoints.php";
    $.post(url, {gameID: session["gameID"]}, function(result) {
      let checkpoints = JSON.parse(result);
      checkpoints.forEach(checkpoint => {
        let html = "<tr>";
        html += "<td>Number " + checkpoint["number"] + "</td>";
        html += "<td>" + checkpoint["name"] + "</td>";
        html += "</tr>";
        $("#searchResults").append(html);
      });
    });
  });
}

function showTags()
{
  $("#searchResults").empty();
  $("#searchResultsHeader").text("(tags)");
  getSession(function(session) {
    let url = "http://3.16.235.207/assets/php/getAllTags.php";
    $.post(url, {gameID: session["gameID"]}, function(result) {
      let tags = JSON.parse(result);
      tags.forEach(tag => {
        let html = "<tr>";
        html += "<td>" + tag["taggerID"] + " tagged " + tag["runnerID"] + "</td>";
        html += "<td>" + tag["time"] + "</td>";
        html += "</tr>";
        $("#searchResults").append(html);
      });
    });
  });
}