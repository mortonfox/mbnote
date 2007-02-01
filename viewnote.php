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
    $notenum = 0 + $_POST["notenum"];
    $db = db_open();
    if ($db) {
	$note = db_getnote($db, $notenum);
	if ($note) {
	    page_head("NB: View note", "");
?>
<body>
<p><b>Notebook: View note</b></p>
<p><b>Subject:</b><br/>
<?php print htmlspecialchars($note["subject"]); ?></p>
<p><b>Content:</b><br/>     
<?php print htmlspecialchars($note["content"]); ?></p>
<p><b>Category:</b> <?php print check_category($note["category"]); ?></p>
<p><br/>
<a href="editnote.php?notenum=<?php print $notenum; ?>">Edit</a><br/>
<a href="pdel.php?notenum=<?php print $notenum; ?>">Delete</a><br/>
<a href="main.php">Return to main menu</a><br/>
</p>
</body> 
<?php
	}
	else {
	    return_to_main("Note not found.");
	}
	db_close($db);
    }
    else {
	return_to_main("Could not open database.");
    }
}

page_end();
?>
