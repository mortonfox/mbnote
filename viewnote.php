<?php
require_once "common.php";
page_begin();
page_header();
check_session();

read_db();

// To handle non-post links from the main menu.
if (!isset($_POST["notenum"]) && isset($_GET["notenum"]))
    $_POST["notenum"] = $_GET["notenum"];

if (!isset($_POST["notenum"]) || 
    $_POST["notenum"] < 0 || 
    $_POST["notenum"] >= count($notes)) {
	return_to_main("Invalid or missing note number.");
}
else {
    $fields = $notes[$_POST["notenum"]];

    page_head("NB: View note", "");
?>

<body>
<p><b>Notebook: View note</b></p>
<p><b>Subject:</b><br/>
<?php print htmlspecialchars($fields["subject"]); ?></p>
<p><b>Content:</b><br/>     
<?php print htmlspecialchars($fields["content"]); ?></p>
<p><b>Category:</b> <?php print check_category($fields["category"]); ?></p>
<p><br/>
<a href="editnote.php?notenum=<?php print 0 + $_POST["notenum"]; ?>">Edit</a><br/>
<a href="pdel.php?notenum=<?php print 0 + $_POST["notenum"]; ?>">Delete</a><br/>
<a href="main.php">Return to main menu</a><br/>
</p>
</body> 
<?php
}

page_end();
?>
