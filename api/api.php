<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// require "api/config/dbconnect.php";
// require "api/config/auth.php";
// require "api/objects/player.php";

// parse the uri into an array
$parsed = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $parsed);
// $method = $_SERVER["REQUEST_METHOD"];
// echo var_dump($uri);
// echo $method;

// connect to db and parse request
// $database = new Database();
// $conn = $database->getConnection();

// if ($conn->connect_errno) {
//     printf("Connect failed: %s\n", $conn->connect_error);
//     exit();
// }

$host       = '172.26.2.25';
$username   = '';
$password   = '';
$dbname     = 'vmi';
$port       = '3306';
$mysqli = new mysqli_connect($host, $username, $password, $dbname, $port);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (!($stmt = $mysqli->prepare("INSERT INTO player VALUES "))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

// // players
// if ($uri[3] == "players") {
//     echo "players";
//     echo $conn;
//     $players = new Player($conn);
//     echo $players;
// }
// // checkpoints
// else if ($uri[3] == "checkpoints") {

// }
// // tags
// else if ($uri[3] == "tags") {

// }

?>