<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

include_once 'error.php';

?>


<form class="centre" action="includes/modifyusers.inc.php" method="post">
    <h3 class="centre">Change Single User's Password</h3>
    <input type="text" name="passwordChange">
    <button class="centre actionbuttons addbuttons" type="submit" name="changePassword">Update Password</button>
</form>


<?php
//Handles error tags

include_once 'footer.php';
?>