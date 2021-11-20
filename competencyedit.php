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



<br>
<h3 class="banner">Create Competency</h3>
<!--Gets name of a competency and allows the addition of it to the database-->
<form action="includes/actioncompetencies.inc.php" method="post">
    <input type="text" name="competency" placeholder="Competency Name">
    <br>
    <button type="submit" name="submit">Add Competency</button>
</form>

<h3 class="banner"> Add Competencies To Groups, Roles and Users </h3>

<!--Defines all groups, and what their assigned competencies are in a table-->
<form action="includes/actioncompetencies.inc.php" method="post">
    <table border="1">
        <tr>
            <th>Select</th>
            <th>Name</th>
            <th>Competencies</th>
        </tr>
        <tr>
            <td>Groups</td>
        </tr>
        <?php
        $groups = mysqli_query($conn, "SELECT * FROM groups");
        while ($row = mysqli_fetch_array($groups)) {
            echo "<tr>";
            echo "<td>" . "<br><input type=\"checkbox\" name=\"groups[]\" value=\"" . $row["GroupID"] . "\">" . "</td>"; //Creates Checkbox with group ID
            echo "<td>" . $row["GName"] . "</td>";   //Gives name of the group
            echo "<td><ul>"; //Lists the competencies in a cell

            $competencygroups = CompetencyGroupFromGroup($conn, $row["GroupID"]);

            while ($competency = mysqli_fetch_array($competencygroups)) {
                echo "<li><p>" . $competency["CName"] . "</p></li>";//lists every associated competency
            }
            echo "</ul></td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <td>Roles</td>
        </tr>
        <?php
        $roles = mysqli_query($conn, "SELECT * FROM roles");
        while ($row = mysqli_fetch_array($roles)) {
            echo "<tr>";
            echo "<td>" . "<br><input type=\"checkbox\" name=\"roles[]\" value=\"" . $row["RoleID"] . "\">" . "</td>"; //Creates Checkbox with group ID
            echo "<td>" . $row["RName"] . "</td>";   //Gives name of the group
            echo "<td><ul>"; //Lists the competencies in a cell

            $competencyroles = CompetencyRolesFromRoles($conn, $row["RoleID"]);

            while ($competency = mysqli_fetch_array($competencyroles)) {
                echo "<li><p>" . $competency["CName"] . "</p></li>";
            }
            echo "</ul></td>";
            echo "</tr>";
        }
        ?>
    </table>
    

    <?php //list of Competencies in a table
    $result = mysqli_query($conn, "SELECT * FROM competencies");

    echo "<table border='1'>
<tr>
<th>Select</th>
<th>Name</th>
</tr>";

    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . "<br><input type=\"checkbox\"  name=\"competencies[]\" value=\"" . $row["CompetencyID"] . "\">" . "</td>"; //Creates Checkbox
        echo "<td>" . $row["CName"] . "</td>"; //Gives name
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <button type="submit" name="removeC">Remove Competency From Groups</button>
    <button type="submit" name="addC">Add Competencies To Groups</button>
    <br>
    <br>
    <br>
    <button type="submit" name="permdelete" class="dangerous">DELETE SELECTED PERMANENTLY</button>
</form>
<p>After adding any new competencies, make sure all users have had their values entered</p>
<?php
//Handles error tags
if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
        echo "<p>Some fields are empty!</p>";
    } else if ($_GET["error"] == "stmtfailure") {
        echo "<p>Processing failure occurred!</p>";
    } else if ($_GET["error"] == "invalidname") {
        echo "<p>Names must be alphanumeric!</p>";
    } else if ($_GET["error"] == "invalidcall") {
        echo "<p>Page was accessed incorrectly!</p>";
    } else if ($_GET["error"] == "none") {
        echo "<p>Successfully Added!</p>";
    } else {
        echo "<p>Something went wrong!</p>";
    }
}

include_once 'footer.php';
