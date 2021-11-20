<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

// if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
//     header("location: index.php?error=invalidcall");
//     exit();
// }
include_once 'error.php';

include_once 'admin.php';
?>
<!-- <form class="centre" action="admin.php" method="post"><button class="block" type="submit" name="submit">Go back</button></form> -->


<h3 class="centre">Add Department/Group</h3>
<form class="centre" action="includes/actiongroups.inc.php" method="post">
    <input type="text" name="groupname" placeholder="Group Name">
    <button class="actionbuttons" type="submit" name="add">Add Group</button>
</form>
<h3 class="centre">Current Departments/Groups</h3>

<form class="centre" action="includes/actiongroups.inc.php" method="post">
<?php //list of Groups in a table
$result = mysqli_query($conn, "SELECT * FROM groups");

echo "<table border='1' class=\"centre\">
<tr>
<th>Select</th>
<th>Name</th>
</tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . "<input type=\"checkbox\" name=\"groups[]\" value=\"" . $row["GroupID"] . "\">"."</td>"; //Creates Checkbox
    echo "<td>" . $row['GName'] . "</td>"; //Gives name
    echo "</tr>";
}
echo "</table>";
?>
<br>
<button class="dangerous" type="submit" name="remove">Delete Selected PERMANENTLY</button>
</form>

<br>
<?php
include_once 'footer.php';
?>