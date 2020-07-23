<?php
// LOGIN PAGE
// initialize the session and show errors
session_start();
if (isset($_SESSION['error'])) {
    echo $_SESSION['error'];
    $_SESSION['error'] = null;
}
?>

<html>
    <head>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/assets/css/main.css">
        <!--<meta http-equiv="refresh" content="10"> -->
        <title>Login</title>
    </head>
    <body>
        <h2 class="container login login-header" id="playerType">Virtual Mission Impossible</h2>
        <div class="container login">
            <form action="/assets/php/loginPlayer.php" method="post">
                <p>Enter player ID to login:</p>
                <input class="tag-search" type="text" name="playerID">
                <button type="submit" class="button">Login</button>
            </form>
        </div>
  </body>
</html>