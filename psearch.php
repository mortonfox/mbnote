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
<p>
<a accesskey="0" href="main.php">0. Main menu</a>
</p>
</body> 
<?php
page_end();
?>

