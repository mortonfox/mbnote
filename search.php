<?php
require_once "common.php";
page_begin();
page_header();
check_session();

read_db();

if (!isset($_POST["search"]) && isset($_GET["search"]))
    $_POST["search"] = $_GET["search"];

if (!isset($_POST["pagenum"]) && isset($_GET["pagenum"]))
    $_POST["pagenum"] = $_GET["pagenum"];

if (!isset($_POST["search"]) || $_POST["search"] == "") {
    return_to_main("No search query specified.");
}
else {
    page_head("NB: Search results", "");
?>
<body>
<p><b>Notebook: Search results</b></p>
<p>Select a note to view, edit, or delete it:<br/>

<?php
    $pagenum = isset($_POST["pagenum"]) ? $_POST["pagenum"] + 0 : 0;
    $indices = select_notes("", $_POST["search"], $pagenum, $nextpage, $prevpage, $numpages);

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
	    if ($nextpage >= 0)
		print "<a href=\"search.php?pagenum=$nextpage&search=" . urlencode($_POST["search"]) . "\">Next page</a><br/>\n";
	    if ($prevpage >= 0)
		print "<a href=\"search.php?pagenum=$prevpage&search=" . urlencode($_POST["search"]) . "\">Prev page</a><br/>\n";
	}
    }
?>

<br/>          
<a href="main.php">Return to main menu</a>
</p>
</body> 

<?php
}

page_end();
?>

