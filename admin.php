
<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
    header("location: index.php?error=invalidcall");
    exit();
}
?>
<form action="staffedit.php" method="post"><button type="submit" name="submit">Modify Staff</button></form>
<form action="groupedit.php" method="post"><button type="submit" name="submit">Modify Groups & Roles</button></form>
<form action="userdefinitions.php" method="post"><button type="submit" name="submit">Define User Competencies</button></form>
<form action="competencyedit.php" method="post"><button type="submit" name="submit">Modify Competencies</button></form>
<?php
//Handles error tags
if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
        echo "<p>Some fields are empty!</p>";
    } else if ($_GET["error"] == "stmtfailure") {
        echo "<p>Processing failure occurred!</p>";
    } else if ($_GET["error"] == "invaliduser") {
        echo "<p>Value must be alphanumeric!</p>";
    } else if ($_GET["error"] == "invalidcall") {
        echo "<p>Page was accessed incorrectly!</p>";
    } else if ($_GET["error"] == "none") {
        echo "<p>Successfully Added!</p>";
    } else if ($_GET["error"] == "usernametaken") {
        echo "<p>Username is already taken!</p>";
    } else {
        echo "<p>Something went wrong!</p>";
    }
}

include_once 'footer.php';
?>