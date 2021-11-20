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

<h3 class="centre">Add Staff Login</h3>
<form class="centre" action="includes/signup.inc.php" method="post">
    <input type="text" name="name" placeholder="Name">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="pwd" placeholder="password">
    <label for="role">Role select:</label>
    <select name="role" id="role">
        <option value="1">Staff</option>
        <option value="2">Manager</option>
        <option value="3">Admin</option>
    </select>
    <button class="actionbuttons" type="submit" name="submit">Add User</button>
</form>
<br>


<h3 class="centre"> Modify Staff </h3>

<?php //list of Staff in a table
$result = mysqli_query($conn, "SELECT * FROM users");
//Table is inside a form
?>
<form class="centre" action="includes/modifyusers.inc.php" method="post"> 
    <table class="centre" border="1">
<tr>
<th>Select</th>
<th>Name</th>
<th>Username</th>
<th>Role</th>
<th>Groups</th>
</tr>

<?php
$roles = mysqli_query($conn, "SELECT * FROM roles");
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . "<input type=\"checkbox\" name=\"users[]\" value=\"" . $row["UserID"] . "\">" . "</td>";     //Creates Checkbox
    echo "<td>" . $row["UName"] . "</td>";   //Gives name
    echo "<td>" . $row["UUsername"] . "</td>";
    echo "<td>" . $row["URole"] . "</td>";
    echo "<td><ul>";

    $usersgroups = UserGroupFromUser($conn, $row["UserID"]);
    
    while ($group = mysqli_fetch_array($usersgroups)){
        echo "<li><p>".$group["GName"]."</p></li>";
    }
    echo "</ul></td>";
    echo "</tr>";
}
?>

</table>
<button class="centre dangerous" type="submit" name="delete">Delete User PERMANENTLY</button>

<?php 
//list of Groups in a table
$groups = getGroups($conn);

echo "<h3 class=\"centre\">Add or Remove Groups</h3>
<table border='1' class=\"centre\">
<tr>
<th>Select</th>
<th>Name</th>
</tr>";

while ($group = mysqli_fetch_array($groups)) { //Loops through entries in array
    echo "<tr>";
    echo "<td>" . "<input type=\"checkbox\" name=\"groups[]\" value=\"" . $group["GroupID"] . "\">" . "</td>"; //Creates Checkbox
    echo "<td>" . $group['GName'] . "</td>"; //Gives name
    echo "</tr>";
}
echo "</table>";
?>
<button class="centre actionbuttons" type="submit" name="removeG">Remove Selected</button>
<button class="centre actionbuttons" type="submit" name="addG">Add Selected</button>

<h3 class="centre">Change Role</h3>

<select class="centre" name="role">
<option value="1">Normal</option>
<option value="2">Manager</option>
<option value="3">Admin</option>
</select>
<button class="centre actionbuttons" type="submit" name="roleUpdate">Update Role</button>
</form>

<br>
<br>
<?php
//Handles error tags

include_once 'footer.php';
?>