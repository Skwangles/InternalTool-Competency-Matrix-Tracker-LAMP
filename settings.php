<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

include_once 'error.php';

?>
<h1 class="centre"><b><?php echo $_SESSION["name"] ?></b></h1>
<h2 class="centre">Your Username: <br><?php echo $_SESSION["username"] ?></h2>

<br>
<br>
<form class="centre" action="includes/settings.inc.php" method="post">
    <h3 class="centre">Change Your Username</h3>
    <input type="text" name="usernameChange" maxlength="20">
    <button class="centre actionbuttons addbuttons" type="submit" name="changeUsername">Update Username</button>
</form>

<form class="centre" action="includes/settings.inc.php" method="post">
    <h3 class="centre">Change Your Password</h3>
    <input type="text" name="passwordChange" maxlength="25">
    <button class="centre actionbuttons addbuttons" type="submit" name="changePassword">Update Password</button>
</form>


<?php
//Handles error tags

include_once 'footer.php';
?>