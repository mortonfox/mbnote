<?php
require_once "common.php";
page_begin();
page_header();
check_session();

read_db();

// To handle non-post links from the main menu.
if (!isset($_POST["category"]) && isset($_GET["category"]))
    $_POST["category"] = $_GET["category"];

if (isset($_POST["category"]))
    $_POST["category"] = check_category($_POST["category"]);

if (!isset($_POST["pagenum"]) && isset($_GET["pagenum"]))
    $_POST["pagenum"] = $_GET["pagenum"];

page_head("NB: List notes", "");
?>

<body>
<p><b>Notebook: List notes <?php if (isset($_POST["category"])) print "in category <i>" . $_POST["category"] . "</i>";?></b></p>
<p>Select a note to view, edit, or delete it:<br/>
<?php

$cat = isset($_POST["category"]) ? $_POST["category"] : "";
$pagenum = isset($_POST["pagenum"]) ? $_POST["pagenum"] + 0 : 0;
$indices = select_notes($cat, "", $pagenum, $nextpage, $prevpage, $numpages);

if (count($indices) == 0) {
    print "<i>No notes found.</i><br/>";
}
else {
    for ($i = 0; $i < count($indices); ++$i) {
	$indx = $indices[$i];
	$fields = $notes[$indx];
	print "<a href=\"viewnote.php?notenum=$indx\">" . show_subject($fields["subject"]) . "</a><br/>\n";
    }

    if ($nextpage >= 0 || $prevpage >= 0) {
	$page = $pagenum + 1;
	print "<br/>Page $page of $numpages<br/>";
	$catparam = ($cat != "") ? "category=$cat&" : "";
	if ($nextpage >= 0)
	    print "<a href=\"listnotes.php?${catparam}pagenum=$nextpage\">Next page</a><br/>\n";
	if ($prevpage >= 0)
	    print "<a href=\"listnotes.php?${catparam}pagenum=$prevpage\">Prev page</a><br/>\n";
    }
}
?>

<br/>          
<a href="main.php">Return to main menu</a></p>
</body> 

<?php
page_end();
?>
