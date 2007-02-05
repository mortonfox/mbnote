<?php
require_once "common.php";
page_begin();
page_header();
check_session();


// To handle non-post links from the main menu.
if (!isset($_POST["category"]) && isset($_GET["category"]))
    $_POST["category"] = $_GET["category"];

if (isset($_POST["category"]))
    $_POST["category"] = check_category($_POST["category"]);

if (!isset($_POST["pagenum"]) && isset($_GET["pagenum"]))
    $_POST["pagenum"] = $_GET["pagenum"];

$cat = isset($_POST["category"]) ? $_POST["category"] : "";

page_head("NB: List notes", "");
?>

<body>
<p><b>Notebook: <?php print isset($_POST["category"]) ? "Category <i>$cat</i>" : "List notes";?></b></p>
<p>
<?php

$pagenum = isset($_POST["pagenum"]) ? $_POST["pagenum"] + 0 : 0;

$db = db_open();
if ($db) {
    $notelist = db_select($db, $cat, "", $pagenum, $nextpage, $prevpage, $numpages);
    if ($notelist && count($notelist) > 0) {
	for ($i = 0; $i < count($notelist); ++$i) {
	    $key = $notelist[$i]["key"];
	    $subj = show_subject($notelist[$i]["subject"]);
	    print "<a href=\"viewnote.php?notenum=$key\">$subj</a><br/>\n";
	}

	if ($nextpage >= 0 || $prevpage >= 0) {
	    $page = $pagenum + 1;
	    print "Page $page of $numpages<br/>";
	    $catparam = ($cat != "") ? "category=$cat&amp;" : "";
	    if ($nextpage >= 0)
		print "<a accesskey=\"1\" href=\"listnotes.php?${catparam}pagenum=$nextpage\">1. Next page</a><br/>\n";
	    if ($prevpage >= 0)
		print "<a accesskey=\"2\" href=\"listnotes.php?${catparam}pagenum=$prevpage\">2. Prev page</a><br/>\n";
	}
    }
    else {
	print "<i>No notes found.</i><br/>";
    }

    db_close($db);
}
else {
    print "<i>Could not open database.</i><br/>";
}

?>

<a accesskey="0" href="main.php">0. Main menu</a>
</p>
</body> 

<?php
page_end();
?>
