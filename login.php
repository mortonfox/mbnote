<?php
require_once "common.php";
page_begin();
page_header();
session_start();
unset($_SESSION["login"]);

if (strcasecmp($_POST["userid"], "g") == 0 &&
    strcasecmp($_POST["password"], "g") == 0) {
	$_SESSION["login"] = 1;
	header("Location: cketest.php");
	to_main("Login succeeded.", "Go to main menu");
}
else {
    to_main("Login failed.", "Try again");
}
?>

<?php
page_end();
?>
