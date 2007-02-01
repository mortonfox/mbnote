<?php
require_once "common.php";
page_begin();
page_header();
check_session();

// read_db();

// To handle non-post links from the main menu.
if (!isset($_POST["notenum"]) && isset($_GET["notenum"]))
    $_POST["notenum"] = $_GET["notenum"];

if (isset($_POST["notenum"])) {

    $notenum = 0 + $_POST["notenum"];
    $db = db_open();
    if ($db) {
	$note = db_getnote($db, $notenum);
	if ($note) {
	    edit_form("NB: Edit Note",
		htmlspecialchars($note["subject"]),
		htmlspecialchars($note["content"]),
		check_category($note["category"]),
		$notenum, "");
	}
	else {
	    return_to_main("Note not found.");
	}
	db_close($db);
    }
    else {
	return_to_main("Could not open database.");
    }

    /*
    $fields = $notes[$_POST["notenum"]];
    edit_form("NB: Edit Note",
	htmlspecialchars($fields["subject"]),
	htmlspecialchars($fields["content"]),
	check_category($fields["category"]),
	$_POST["notenum"], "");
     */
}
else {
    edit_form("NB: Add New Note", "", "", "work", -1, "");
}

page_end();
?>
