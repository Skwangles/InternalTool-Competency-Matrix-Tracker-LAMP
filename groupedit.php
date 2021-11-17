<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
    header("location: index.php?error=invalidcall");
    exit();
}
?>
<form action="admin.php" method="post"><button type="submit" name="submit">Go back</button></form>


<h3 class="banner">Add Department/Group</h3>
<form action="includes/addgroup.inc.php" method="post">
    <input type="text" name="groupname" placeholder="Group Name">
    <button type="submit" name="submit">Add Group</button>
</form>
<h3 class="banner">Current Departments/Groups</h3>


<?php //list of Groups in a table
$result = mysqli_query($conn, "SELECT * FROM groups");

echo "<table border='1'>
<tr>
<th>Select</th>
<th>Name</th>
</tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . "<br><input type=\"checkbox\" id=\"" . $row["GroupID"] . "\" name=\"" . $row["GroupID"] . "\" value=\"" . $row["GroupID"] . "\">" . "</td>"; //Creates Checkbox
    echo "<td>" . $row['GName'] . "</td>"; //Gives name
    echo "</tr>";
}
echo "</table>";
?>


<br>
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