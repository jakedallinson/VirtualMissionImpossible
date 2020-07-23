<?php
// LOGOUT PAGE
// remove all session variables
session_start();
session_destroy();
header('location: /login');
exit;
?>