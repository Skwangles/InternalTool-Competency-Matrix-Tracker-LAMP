<?php
include_once 'header.php';
?>

<form action="includes/login.inc.php" method="post">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="pwd" placeholder="password">
    <button type="submit" name="submit">Login</button>
</form>

<?php
if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
        echo "<p>Some fields are empty!</p>";
    } else if ($_GET["error"] == "stmtfailure") {
        echo "<p>Processing failure occurred!</p>";
    } else if ($_GET["error"] == "invaliduser") {
        echo "<p>Username must be alphanumeric!</p>";
    } else if ($_GET["error"] == "incorrectlogin") {
        echo "<p>Incorrect Username/Password!</p>";
    } else if ($_GET["error"] == "none") {
        echo "<p>Successfully Logged In!</p>";
    }
}
?>

<?php
include_once 'footer.php';
?>