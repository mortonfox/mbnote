<?php
require_once "common.php";
page_begin();
page_header();
check_session();

if (!isset($_POST["subject"]) || $_POST["subject"] == "") {
    // If no subject entered, send the user back to the edit form. Do not 
    // allow subject lines to be blank because that's what we display in 
    // the list of notes.
    edit_form("NB: Edit Note",
	htmlspecialchars($_POST["subject"]),
	htmlspecialchars($_POST["content"]),
	check_category($_POST["category"]),
	isset($_POST["notenum"]) ? 0 + $_POST["notenum"] : -1,
	"Blank subject not allowed.");
}
else {
    $_POST["category"] = check_category($_POST["category"]);

    $db = db_open();
    if ($db) {
	if (db_savenote($db, 
	    isset($_POST["notenum"]) ? 0 + $_POST["notenum"] : null, 
	    $_POST["subject"],
	    $_POST["content"],
	    $_POST["category"])
	) {
	    // After saving, redisplay the note.
	    $notenum = isset($_POST["notenum"]) ? 
		0 + $_POST["notenum"] : 
		sqlite_last_insert_rowid($db);
	    view_note($notenum);
	}
	else {
	    return_to_main("Could not save note.");
	}

	db_close($db);
    }
    else {
	return_to_main("Could not open database.");
    }
}

page_end();
?>
