$(document).ready(function() {

  let gameID = 1;
  // hideResult();
  setSearch();

  // keypad and searching
  $(".keypad").click(function() {
    // update keypad and search based on key pressed
    let $key = $(this).attr("value");
    let $search = $("#search");
    if ($key == "back") {
      if ($search.attr("value").length > 0) {
        // remove last char from value
        $search.attr("value", $search.attr("value").slice(0, -1));
      }
    } else if ($key == "clear") {
      $search.attr("value", "");
    } else {
      if ($search.attr("value").length < 4) {
        // append the key pressed to the value
        $search.attr("value", $search.attr("value") + $key)
      }
    }
    setSearch();
    // make the request if 4 digits are entered
    if ($search.attr("value").length == 4) {
      let params = {
        gameID: gameID,
        playerID: parseInt($search.attr("value")),
      };
      getPlayers(params, function(results) {
        readResult(results);
      });
    }
  });

  // options
  $("#options").click(function() {
    let $icon = $("#options-icon");
    if ($icon.attr("pressed") == "false") {
      $icon.removeClass("fa-bars");
      $icon.addClass("fa-times");
      $icon.attr("pressed", "true");
    } else {
      $icon.removeClass("fa-times");
      $icon.addClass("fa-bars");
      $icon.attr("pressed", "false");
    }
  });

});

function hideResult()
{
  let $result = $("#result");
  $result.children().hide();
}

function showResult()
{
  let $result = $("#result");
  $result.children().show();
}

function setSearch()
{
  let $search = $("#search");
  let $clear = $("#clear");
  if ($search.attr("value").length > 0) {
    $search.text($search.attr("value"));
    $search.css("color", "#161616");
    $clear.show();
  } else {
    $search.text("search for runner...");
    $search.css("color", "#d6d6d6");
    $clear.hide();
    // hideResult();
  }
}

function readResult(results)
{
  if (results.length == 1) {
    showResult();
    let result = results[0];
    // set player status
    let $playerStatus = $("#playerStatus");
    let $playerInfo = $("#playerInfo");
    if (result["status"]) {
      $playerStatus.text("IN");
      $playerInfo.css("background-color", "#0f7f12");
    } else {
      $playerStatus.text("OUT");
      $playerInfo.css("background-color", "#fc625d");
    }
    // set player id
    // if length of id is < 4, pad with 0s
    let $playerID = $("#playerID");
    let id = result["playerID"].toString();
    for (let i = id.length; i < 4; i++) {
      id = "0" + id;
    }
    $playerID.text(id);
  }
}

function getPlayers(params, callback)
{
  let url = "http://3.16.235.207/assets/php/getPlayers.php";
  // make the request
  $.post(url, params, function(result) {
    let results = JSON.parse(result);
    callback(results);
  });
}