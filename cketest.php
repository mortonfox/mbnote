<?php
require_once "common.php";
page_begin();
page_header();
session_start();

// Check for the session cookie.
if (!isset($_SESSION["login"])) {
    to_main("Session cookie not found. Please enable cookies.", "Try again");
}
else {
    header("Location: main.php");
    to_main("Login succeeded.", "Go to main menu");
}
?>

<?php
page_end();
?>
