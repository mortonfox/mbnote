<?php
require_once "common.php";
page_begin();
page_header();
check_session();

lock();
read_db();

// To handle non-post links from the main menu.
if (!isset($_POST["notenum"]) && isset($_GET["notenum"]))
    $_POST["notenum"] = $_GET["notenum"];

if (!isset($_POST["notenum"]) || $_POST["notenum"] < 0 || $_POST["notenum"] >= count($notes)) {
    return_to_main("Invalid or missing note number.");
}
else {
    $notes[$_POST["notenum"]] = array(
	    "subject"=>"",
	    "content"=>"",
	    "category"=>"",
	    "timestamp"=>0);
    write_db();
    return_to_main("Note deleted.");
}

unlock();

page_end();
?>
