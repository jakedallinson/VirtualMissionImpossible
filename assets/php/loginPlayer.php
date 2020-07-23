<?php
require_once('auth.php');

// attempt to log player in and redirect to appropriate page
if (loginPlayer($_POST["playerID"])) {
    header("location: /");
} else {
    header("location: /login");
}

exit;
?>