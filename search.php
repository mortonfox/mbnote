<?php
require_once "common.php";
page_begin();
page_header();
check_session();


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

    $db = db_open();
    if ($db) {
	$notelist = db_select($db, "", $_POST["search"], $pagenum, $nextpage, $prevpage, $numpages);
	if ($notelist && count($notelist) > 0) {
	    for ($i = 0; $i < count($notelist); ++$i) {
		$key = $notelist[$i]["key"];
		$subj = show_subject($notelist[$i]["subject"]);
		print "<a href=\"viewnote.php?notenum=$key\">$subj</a><br/>\n";
	    }
	    if ($nextpage >= 0 || $prevpage >= 0) {
		$page = $pagenum + 1;
		print "<br/>Page $page of $numpages<br/>";
		if ($nextpage >= 0)
		    print "<a href=\"search.php?pagenum=$nextpage&amp;search=" . urlencode($_POST["search"]) . "\">Next page</a><br/>\n";
		if ($prevpage >= 0)
		    print "<a href=\"search.php?pagenum=$prevpage&amp;search=" . urlencode($_POST["search"]) . "\">Prev page</a><br/>\n";
	    }
	}
	else {
	    print "<i>No notes found.</i><br/>";
	    search_form();
	}

	db_close($db);
    }
    else {
	print "<i>Could not open database.</i><br/>";
    }

?>

<a href="psearch.php">Search again</a>
<br/>          
<a href="main.php">Return to main menu</a>
</p>
</body> 

<?php
}

page_end();
?>

