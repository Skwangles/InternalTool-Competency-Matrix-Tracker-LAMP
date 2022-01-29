<?php
include_once 'includes/header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

include_once 'includes/settings.inc.php';
?>
<h1 class="centre"><b><?php echo $_SESSION["name"] ?></b></h1>
<h4 class="centre" style="color:#4C4C4C;">Your Username:</h4>
<h2 class="centre"><?php echo $_SESSION["username"] ?></h2>

<br>
<br>
<form class="centre" action="settings.php" method="post">
    <h3 class="centre">Change Your Username</h3>
    <input type="text" name="usernameChange" maxlength="20">
    <button class="centre actionbuttons addbuttons" type="submit" name="changeUsername">Update Username</button>
</form>

<form class="centre" action="settings.php" method="post">
    <h3 class="centre">Change Your Password</h3>
    <input type="text" name="passwordChange" maxlength="25">
    <button class="centre actionbuttons addbuttons" type="submit" name="changePassword">Update Password</button>
</form>
<br>
<?php
//Handles error tags

include_once 'includes/footer.php';
?>