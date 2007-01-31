<?php
require_once "common.php";
page_begin();
page_header();
check_session();

// To handle non-post links from the main menu.
if (!isset($_POST["notenum"]) && isset($_GET["notenum"]))
    $_POST["notenum"] = $_GET["notenum"];

page_head("NB: Delete note", "");
?>

<body>
<p><b>Delete note:</b> Are you sure?</p>
<p>
<a href="delnote.php?notenum=<?php print 0 + $_POST["notenum"]; ?>">Yes</a><br/>
<a href="viewnote.php?notenum=<?php print 0 + $_POST["notenum"]; ?>">No</a><br/></p>
</body> 

<?php
page_end();
?>
