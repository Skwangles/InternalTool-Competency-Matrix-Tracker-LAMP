<?php
include_once 'header.php';
?>


<h1 class="centre">Login</h1>
<?php
if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
        echo "<p class=\"centre error\">Some fields are empty!</p>";
    } else if ($_GET["error"] == "stmtfailure") {
        echo "<p class=\"centre error\">Processing failure occurred!</p>";
    } else if ($_GET["error"] == "invaliduser") {
        echo "<p class=\"centre error\">Username must be alphanumeric!</p>";
    } else if ($_GET["error"] == "incorrectlogin") {
        echo "<p class=\"centre error\">Incorrect Username/Password!</p>";
    } else if ($_GET["error"] == "none") {
        echo "<p class=\"centre error\">Successfully Logged In!</p>";
    }
}
?>
<form class="centre" action="includes/login.inc.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <br>
    <input type="password" name="pwd" placeholder="password">
    <br>
    <button class="actionbuttons" type="submit" name="submit">Login</button>
</form>



<?php
include_once 'footer.php';
?>