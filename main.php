<?php
require_once "common.php";
page_begin();
page_header();
check_session();

page_head("NB: Main", "");
?>
<body>
<p><b>Notebook: Main</b></p>
<p><a accesskey="1" href="editnote.php">1. New note</a><br/>
<a accesskey="2" href="listnotes.php">2. List notes</a><br/>
<a accesskey="3" href="pcat.php">3. Categories</a><br/>
<a accesskey="4" href="psearch.php">4. Search</a><br/>
<a accesskey="5" href="logout.php">5. Logout</a></p>
</body>
<?php
page_end();
?>
