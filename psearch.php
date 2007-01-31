<?php
require_once "common.php";
page_begin();
page_header();
check_session();

page_head("NB: Search", "");
?>
<body>
<p><b>Notebook: Search</b></p>
<form action="search.php" method="post">
<p>Enter a keyword to look for in the notes:<br/>
<input name="search"/>
</p>
<p><input type="submit" value="Search"/></p>
</form>
<p><br/><a href="main.php">Return to main menu</a></p>
</body> 
<?php
page_end();
?>

