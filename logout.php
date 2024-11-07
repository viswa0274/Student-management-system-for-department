<?php
session_start();

session_unset();

session_destroy();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/', $_SERVER['HTTP_HOST'], true, true);
}

header('Location: home.php');
exit();
?>
