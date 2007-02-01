<?php
require_once "common.php";
page_begin();
page_header();
check_session();

page_head("NB: Search", "");
?>
<body>
<p><b>Notebook: Search</b></p>
<?php search_form(); ?>
<p><br/><a href="main.php">Return to main menu</a></p>
</body> 
<?php
page_end();
?>

