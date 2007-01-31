<?php
require_once "common.php";
page_begin();
page_header();
session_start();
session_destroy();
// Redirect to a different page after destroying the session to avoid 
// potential problems if the user refreshes the page.
header("Location: logout.xhtml");

page_head("NB: Logout", "");
?>

<body>
<p>Logging out...</p>
</body>

<?php
page_end();
?>

