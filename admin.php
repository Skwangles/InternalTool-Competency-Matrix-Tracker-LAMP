<?php
include_once 'includes/header.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
    header("location: index.php?error=invalidcall");
    exit();
}

require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

require_once 'includes/actiongroups.inc.php'; //Adds actions for check upon reload
require_once 'includes/actioncompetencies.inc.php';

require_once 'includes/modifyusers.inc.php';
require_once 'includes/signup.inc.php';
?>

<hr class="seperator">
<h1 class="centre">Staff Edit</h1>
<hr class="seperator">

<?php

include_once 'includes/staffedit.php';

?>


<hr class="seperator">
<h1 class="centre">Group and Competency Edit</h1>
<hr class="seperator">

<?php

include_once 'includes/gcedit.php';

include_once 'includes/footer.php';
?>