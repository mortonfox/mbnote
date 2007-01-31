<?php
require_once "common.php";
page_begin();
page_header();
check_session();

read_db();

// To handle non-post links from the main menu.
if (!isset($_POST["notenum"]) && isset($_GET["notenum"]))
    $_POST["notenum"] = $_GET["notenum"];

if (isset($_POST["notenum"])) {
    $fields = $notes[$_POST["notenum"]];
    edit_form("NB: Edit Note",
	htmlspecialchars($fields["subject"]),
	htmlspecialchars($fields["content"]),
	check_category($fields["category"]),
	$_POST["notenum"], "");
}
else 
    edit_form("NB: Add New Note", "", "", "work", -1, "");

page_end();
?>
