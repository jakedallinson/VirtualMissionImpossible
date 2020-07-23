$(document).ready(function() {

  // get session variables from php to set up page
  getSession(function(session) {
    // show the appropriate section
    if (session["role"] == "RUNNER") {
      $("#playerType").text("Runner");
      $("#runner").show();
      showRunnerStats(session);
    } else {
      $("#playerType").text("Tagger");
      $("#tagger").show();
      showTaggerStats(session);
      resetInputs();
    }
    // show master link if necessary
    showMasterLink(session);
  });

  // tagging players on input field change
  $("#input").on("input", function() {
    getSession(function(session) {
      let input = $('#input').val();
      if (input == "") {
        resetInputs();
        return;
      }
      $("#status").text("");
      $("#searchResults").empty();
      // get all players matching player and game ids
      getIDRunners(session, input, function(runners) {
        let max = 3;
        let counter = 0;
        runners.forEach(runner => {
          if (counter == max) { return; }
          let html = "<tr onclick=\"updateInput(" + runner["playerID"] + ")\">";
          html += "<td>Runner " + runner["playerID"] + "</td>";
          html += "<td>" + runner["name"] + "</td>";
          html += "</tr>";
          $("#searchResults").append(html);
          counter += 1;
        });
      });
    });
  });

  // flip player status when tagged
  $("#tagButton").click(function() {
    getSession(function(session) {
      let runnerID = $("#playerTaggedID").children("span").text();
      let gameID = session["gameID"];
      let taggerID = session["playerID"];
      resetInputs();
      // flip status
      let url = "http://3.16.235.207/assets/php/updateStatus.php";
      $.post(url, {playerID: runnerID, gameID: gameID}, function(result) {
        if (result) {
          let html = "Runner " + runnerID + " tagged in/out successfully.";
          $("#playerTaggedID").html(html);console.log(result);
        }
      });
      // add tag
      url = "http://3.16.235.207/assets/php/addTag.php";
      $.post(url, {taggerID: taggerID, runnerID: runnerID, gameID: gameID}, function(result) {
        console.log(result);
      });
    });
  });

  // flip player status when tagged
  $("#checkpointButton").click(function() {
    getSession(function(session) {
      let runnerID = $("#playerTaggedID").children("span").text();
      let gameID = session["gameID"];
      // clear next checkpoint
      let url = "http://3.16.235.207/assets/php/clearCheckpoint.php";
      $.post(url, {playerID: runnerID, gameID: gameID}, function(result) {
        console.log(result);
      });
      resetInputs();
    });
  });

});

// EXTRA FUNCTIONS

function getSession(callback)
{
  let url = "http://3.16.235.207/assets/php/getSession.php";
  $.post(url, function(result) {
    let results = JSON.parse(result);
    callback(results);
  });
}

function getAllRunners(session)
{
  let url = "http://3.16.235.207/assets/php/getAllRunners.php";
  $.post(url, {gameID: session["gameID"]}, function(result) {
    return JSON.parse(result);
  });
}

function getIDRunners(session, ID, callback)
{
  let url = "http://3.16.235.207/assets/php/getIDRunners.php";
  $.post(url, {playerID: ID, gameID: session["gameID"]}, function(result) {
    callback(JSON.parse(result));
  });
}

function showMasterLink(session)
{
  let url = "http://3.16.235.207/assets/php/getPlayer.php";
  $.post(url, {playerID: session["playerID"], gameID: session["gameID"]}, function(result) {
    let player = JSON.parse(result);
    if (player["gameAdmin"]) {
      $('#master').show();
    }
  });
}

function showRunnerStats(session)
{
  let url = "http://3.16.235.207/assets/php/getRunner.php";
  $.post(url, {playerID: session["playerID"], gameID: session["gameID"]}, function(result) {
    let runner = JSON.parse(result);
    console.log(runner);
    // basic info
    $(".playerID").text(runner["runner"]["playerID"]);
    $("#runnerGameID").text(runner["runner"]["gameID"]);
    // status
    if (runner["status"]) {
      $("#runnerStatus").text("IN");
    } else {
      $("#runnerStatus").text("OUT");
    }
    // checkpoints
    $("#runnerLastCheckpoint").text(formatCheckpoint(runner["lastCheckpoint"]));
    if (runner["nextCheckpoint"]) {
      $("#runnerNextCheckpoint").text(formatCheckpoint(runner["nextCheckpoint"]));
    } else {
      $("#runnerNextCheckpoint").text("none");
    }
    //tags
    $("#runnerTags").text(runner["tags"].length);
    $("#runnerMessage").text(createRunnerMessage(runner));
  });
}

function showTaggerStats(session)
{
  let url = "http://3.16.235.207/assets/php/getTaggerStats.php";
  $.post(url, {playerID: session["playerID"], gameID: session["gameID"]}, function(result) {
    let tagger = JSON.parse(result);
    $(".playerID").text(tagger["tagger"]["playerID"]);
    $("#taggerGameID").text(tagger["tagger"]["gameID"]);
    $("#taggerTags").text(tagger["tags"].length);
  });
}

function formatCheckpoint(checkpoint)
{
  return checkpoint["number"] + " (" + checkpoint["name"] + ")";
}

function createRunnerMessage(runner)
{
  if (runner["nextCheckpoint"]) {
    if (runner["runner"]["status"]) {
      return "You are currently in! Go to checkpoint " + formatCheckpoint(runner["nextCheckpoint"]);
    } else {
      return "You are currently out! Go back to checkpoint " + formatCheckpoint(runner["lastCheckpoint"]);
    }
  } else {
    return "Congratulations! You have completed Mission Impossible.";
  }
}

function resetInputs()
{
  $("#input").val("");
  $("#playerTaggedID").html("");
  $("#searchResults").html("");
  $('#tagButton').html('tag IN/OUT');
  $('#checkpointButton').html('checkpoint');
  $('#tagButton').prop('disabled', true);
  $('#checkpointButton').prop('disabled', true);
}

function toggleButton()
{
  let runner = $("#playerTaggedID").text();
  if (runner == "none") {
    $('#tagButton').prop('disabled', true);
  } else {
    $('#tagButton').prop('disabled', false);
  }
}

function updateInput(runnerID)
{
  // get the game id from the session
  getSession(function(session) {
    //let runnerID = text.children("span").text();
    let url = "http://3.16.235.207/assets/php/getRunner.php";
    $.post(url, {playerID: runnerID, gameID: session["gameID"]}, function(result) {
      let runner = JSON.parse(result);
      let html = "Runner <span>" + runner["runner"]["playerID"] + "</span><br>";
      if (runner["runner"]["status"]) {
        html += "Status: IN<br>";
        $('#tagButton').html('tag OUT');
        // show checkpoint button
        if (runner["nextCheckpoint"]) {
          $('#checkpointButton').prop('disabled', false);
          $('#checkpointButton').html('Clear checkpoint ' + formatCheckpoint(runner["nextCheckpoint"]));
        } else {
          $('#checkpointButton').prop('disabled', true);
          $('#checkpointButton').html('checkpoint');
        }
      } else {
        html += "Status: OUT<br>";
        $('#tagButton').html('tag IN');
        $('#checkpointButton').html('checkpoint');
        $('#checkpointButton').prop('disabled', true);
      }
      html += "Last checkpoint: " + formatCheckpoint(runner["lastCheckpoint"]) + "<br>";
      if (runner["nextCheckpoint"]) {
        html += "Next checkpoint: " + formatCheckpoint(runner["nextCheckpoint"])  + "<br>";
      } else {
        html += "Next checkpoint: none<br>";
      }
      html += "Tagged " + runner["tags"].length + " times<br>";
      $("#playerTaggedID").html(html);
      toggleButton();
    });
  });
}