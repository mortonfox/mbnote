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
	isset($_POST["notenum"]) ? $_POST["notenum"] : -1,
	"Blank subject not allowed.");
}
else {
    $_POST["category"] = check_category($_POST["category"]);
    lock();
    read_db();
    $savednote = array(
	"subject"=>$_POST["subject"],
	"content"=>$_POST["content"],
	"category"=>$_POST["category"],
	"timestamp"=>time());
    if (isset($_POST["notenum"])) {
	$notes[$_POST["notenum"]] = $savednote;
    }
    else {
	$notes[] = $savednote;

	$db = db_open();
	if ($db) {
	    db_savenote($db, null, $_POST["subject"], $_POST["content"],
		$_POST["category"]);
	    db_close();
	}
    }
    write_db();
    unlock();
    return_to_main("Note saved.");
}

page_end();
?>
