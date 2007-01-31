<?php
function page_begin() {
    ob_start();

    // Mime type negotiation. Either of the two xhtml forms should be 
    // acceptable but you never know.
//    if (stripos($_SERVER['HTTP_ACCEPT'], "application/vnd.wap.xhtml+xml") !== false)
//	header("Content-type: application/vnd.wap.xhtml+xml");
    if (stripos($_SERVER['HTTP_ACCEPT'], "application/xhtml+xml") !== false)
	header("Content-type: application/xhtml+xml");
    else
	header("Content-type: text/html");

    // Disable browser cache. Some of these may not be necessary.
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}

function page_header() {
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\""
        . " \"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">\n";
    echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
}

function page_end() {
    echo "</html>\n";
    ob_end_flush();
}

function page_head($title, $goto) {
?>
    <head>
	<title><?php print $title;?></title>
        <link rel="stylesheet" href="common.css" type="text/css"/>
<?php
    if ($goto != "")
	print "<meta http-equiv=\"refresh\" content=\"7;URL=$goto\"/>\n";
?>
    </head>
<?php
}

function check_session() {
    session_start();

    // If the user is not logged in, send the user to the login page. This 
    // check is done on every page.
    if (!isset($_SESSION["login"])) {
	page_login();
	page_end();
	exit;
    }
}

$catnames = array("work", "home", "todo", "shopping");
$catvals = array("Work", "Home", "To-do", "Shopping");

// Check the category string against known categories. If invalid or 
// missing, default to one of the categories. This is used to prevent bad 
// input from CGI.
function check_category($cat) {
    global $catnames;
    if (isset($cat)) {
	for ($i = 0; $i < count($catnames); ++$i) {
	    if ($cat == $catnames[$i])
		return $cat;
	}
    }
    return $catnames[0];
}

function page_login() {
    page_head("NB: Login", "");
?>
<body>
<p><b>Notebook: Login</b></p>
<form action="login.php" method="post">
<p>User ID:<br/>
<input name="userid"/></p>
<p>Password:<br/>
<input type="password" name="password"/></p>
<p><input type="submit" value="Login"/></p>
</form>
</body>
<?php
}

define('DBFILE', 'notes.txt');

// fields: subject, content, category, timestamp

// Read the entire database into memory.
function read_db() {
    global $notes;
    $notes = array();
    $hndl = @fopen(DBFILE, "r");
    if ($hndl) {
	while (!feof($hndl)) {
	    $line = chop(fgets($hndl));
	    if ($line != "") {
		$fields = explode(",", $line);
		$notes[] = array(
		    "subject"=>urldecode($fields[0]),
		    "content"=>urldecode($fields[1]),
		    "category"=>$fields[2],
		    "timestamp"=>$fields[3]);
	    }
	}
	fclose($hndl);
    }
}

// Write the entire database out to the file.
function write_db() {
    global $notes;
    $hndl = fopen(DBFILE, "w");
    if ($hndl) {
	for ($i = 0; $i < count($notes); $i++) {
	    $fields = $notes[$i];
	    fprintf($hndl, "%s,%s,%s,%d\n",
		urlencode($fields["subject"]), 
		urlencode($fields["content"]), 
		$fields["category"], 
		$fields["timestamp"]);
	}
	fclose($hndl);
    }
}

define('LOCKFILE', "mbnote.lck");
$lockhndl = false;

// Lock the semaphore file to control access to the database.
function lock() {
    global $lockhndl;
    $lockhndl = fopen(LOCKFILE, "w");
    if ($lockhndl) {
	flock($lockhndl, LOCK_EX);
    }
}

// Unlock the semaphore file.
function unlock() {
    global $lockhndl;
    if ($lockhndl) {
	flock($lockhndl, LOCK_UN);
	fclose($lockhndl);
	$lockhndl = false;
    }
}

// Generic routine that displays a message and sends the user back to the 
// main menu.
function to_main($msg, $linkmsg) {
    page_head("", "main.php");
?>
<body>
<p><?php print $msg;?></p>
<p><a href="main.php"><?php print $linkmsg;?></a></p>
</body>
<?php
}

function return_to_main($msg) {
    to_main($msg, "Return to main menu");
}

// Display a form for editing a note with the fields optionally filled in.
// If notenum is less than zero then the note will be saved as a new note. 
// Otherwise, the note will be saved as an existing note.
function edit_form($title, $subj, $cont, $cat, $notenum, $errormsg) {
    page_head($title, "");
    $notenum = 0 + $notenum;
?>
<body>
<p><b><?php print $title; ?></b></p>
<?php
    if (!empty($errormsg))
	print "<p><i>$errormsg</i></p>";
?>
<form action="savenote.php" method="post">
<p>Subject:<br/>
<input name="subject" maxlength="80" emptyok="false" value="<?php print $subj; ?>"/></p>
<p>Content:<br/>
<textarea name="content" maxlength="255" emptyok="true" cols="<?php print PAGEWIDTH; ?>" rows="3"><?php print $cont; ?></textarea></p>
<!-- <input name="content" maxlength="255" emptyok="true" value="<?php print $cont; ?>"/></p> -->
<p>Category:<br/>
<select name="category">

<?php
    global $catnames, $catvals;
    for ($i = 0; $i < count($catnames); ++$i) {
	print "<option value=\"$catnames[$i]\"";
	if ($cat == $catnames[$i])
	    print " selected=\"selected\"";
	print ">$catvals[$i]</option>\n";
    }
?>
</select>
<?php
    // If the notenum field is not specified, savenote.php saves the note 
    // as a new note.
    if ($notenum >= 0)
	print "<input type=\"hidden\" name=\"notenum\" value=\"$notenum\"/>";
?>
</p>
<p><br/><input type="submit" value="Save note"/></p>
</form>
<p><a href="main.php">Cancel</a></p>
</body> 
<?php
}

define(PAGELEN, 10);

// Generalized select function for getting a list of notes from the 
// database.
function select_notes($cat, $search, $pagenum, &$nextpage, &$prevpage, &$numpages) {
    global $notes;

    // Search from end to beginning so that the latest notes show up first.
    for ($i = count($notes) - 1; $i >= 0; $i--) {
	$fields = $notes[$i];

	// Skip over deleted notes.
	if ($fields["subject"] == "")
	    continue;

	// Do a category search if category is specified.
	if ($cat != "" && $fields["category"] != $cat)
	    continue;

	// Check for the search keyword in both the subject and content 
	// fields if a search keyword is specified.
	if ($search != "" && 
	    stripos($fields["subject"], $search) === false &&
	    stripos($fields["content"], $search) === false)
	    continue;

	$indices[] = $i;
    }

    $numpages = floor((count($indices) + PAGELEN - 1) / PAGELEN);

    // Sanity check for the page number.
    if ($pagenum < 0 || $pagenum >= $numpages)
	$pagenum = 0;

    $output = array_slice($indices, $pagenum * PAGELEN, PAGELEN);

    $nextpage = -1;
    if ($pagenum < $numpages - 1)
	$nextpage = $pagenum + 1;

    $prevpage = -1;
    if ($pagenum > 0)
	$prevpage = $pagenum - 1;

    return $output;
}

define(PAGEWIDTH, 15);

function show_subject($subj) {
    if (strlen($subj) > PAGEWIDTH)
	$subj = substr($subj, 0, PAGEWIDTH - 3) . "...";
    return htmlspecialchars($subj);
}

?>
