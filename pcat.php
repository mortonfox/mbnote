<?php
require_once "common.php";
page_begin();
page_header();
check_session();

page_head("NB: Categories", "");
?>
<body>
<p><b>Notebook: Categories</b></p>
<p>Choose a category to list notes in that category:</p>
<p>
<?php
    for ($i = 0; $i < count($catnames); ++$i) {
	$accesskey = $i + 1;
	print "<a accesskey=\"$accesskey\" href=\"listnotes.php?category=$catnames[$i]\">$accesskey. $catvals[$i]</a><br/>\n";
    }
?>
</p>
<p>
<a accesskey="0" href="main.php">0. Main menu</a>
</p>
</body>

<?php
page_end();
?>
