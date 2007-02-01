<?php
require_once "common.php";
page_begin();
page_header();
check_session();

// read_db();

// To handle non-post links from the main menu.
if (!isset($_POST["notenum"]) && isset($_GET["notenum"]))
    $_POST["notenum"] = $_GET["notenum"];

if (!isset($_POST["notenum"]) || $_POST["notenum"] < 0) {
    return_to_main("Invalid or missing note number.");
}
else {
    view_note(0 + $_POST["notenum"]);
}

page_end();
?>
