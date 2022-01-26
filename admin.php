<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
    header("location: index.php?error=invalidcall");
    exit();
}
?>

<!-- <br>
<form class="centre" action="staffedit.php" method="post"><button class="actionbuttons addbuttons" type="submit" name="submit">Modify Staff Accounts</button></form>
<form class="centre" action="gcedit.php" method="post"><button class="actionbuttons addbuttons" type="submit" name="submit">Modify Groups & Competencies</button></form> -->

<hr class="seperator">
<h1 class="centre">Staff Edit</h1>
<hr class="seperator">

<?php

include_once 'staffedit.php';

?>

<hr class="seperator">
<h1 class="centre">Group and Competency Edit</h1>
<hr class="seperator">

<?php

include_once 'gcedit.php';

include_once 'footer.php';
