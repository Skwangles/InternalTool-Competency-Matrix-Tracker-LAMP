<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

include_once 'error.php';

include_once 'admin.php';
?>
<!--
//
// COMPETENCY/GROUP ASSIGN
//
-->
<!--Defines all groups, and what their assigned competencies are in a table-->
<section id="AddRemoveCG">
<h3 class="centre"> Add Competencies To Groups, Roles and Users </h3>
<form class="centre" action="includes/actioncompetencies.inc.php" method="post">
    <table border="1" class="centre">
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
            echo "<td>" . "<input type=\"checkbox\" id=\"".$row["GroupID"]."-cbg\" name=\"groups[]\" value=\"" . $row["GroupID"] . "\">" . "</td>"; //Creates Checkbox with group ID
            echo "<td><label for=\"".$row["GroupID"]."-cbg\">" . $row["GName"] . "</label></td>";   //Gives name of the group
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
            echo "<td>" . "<input type=\"checkbox\" name=\"roles[]\" id=\"".$row["RoleID"]."-cbr\" value=\"" . $row["RoleID"] . "\">" . "</td>"; //Creates Checkbox with group ID
            echo "<td><label for=\"".$row["RoleID"]."-cbr\">" . $row["RName"] . "</label></td>";   //Gives name of the group
            echo "<td><ul>"; //Lists the competencies in a cell

            $competencyroles = CompetencyRolesFromRoles($conn, $row["RoleID"]);

            while ($competency = mysqli_fetch_array($competencyroles)) {
                echo "<li style=\"text-align:left;\"><p>" . $competency["CName"] . "</p></li>";
            }
            echo "</ul></td>";
            echo "</tr>";
        }
        ?>
    </table>
    

    <?php //list of Competencies in a table
    $result = mysqli_query($conn, "SELECT * FROM competencies");

    echo "<table border='1' class=\"centre\">
<tr>
<th>Select</th>
<th>Name</th>
</tr>";

    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . "<input type=\"checkbox\" id=\"".$row["CompetencyID"]."-cbc\" name=\"competencies[]\" value=\"" . $row["CompetencyID"] . "\">" . "</td>"; //Creates Checkbox
        echo "<td><label for=\"".$row["CompetencyID"]."-cbc\">" . $row["CName"] . "</label></td>"; //Gives name
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <br>
    <button class="actionbuttons addbuttons" type="submit" name="addC">Add Competencies To Groups</button>
    <button class="actionbuttons rembuttons" type="submit" name="removeC">Remove Competency From Groups</button>
    
    
</form>
</section>

<!--
//
// GROUP CREATION AND DELETION
//
-->
<section id="GroupManage">
<h3 class="centre">Add Department/Group</h3>
<form class="centre" action="includes/actiongroups.inc.php" method="post">
    <input type="text" name="groupname" placeholder="Group Name">
    <button class="actionbuttons addbuttons" type="submit" name="add">Add Group</button>
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
<button class="dangerous centre" type="submit" name="remove">Delete Selected PERMANENTLY</button>
</form>
</section>
<!--
//
// COMPETENCY CREATION
//
-->
<br>
<section id="CompetencyManage">
<h3 class="centre">Create Competency</h3>
<!--Gets name of a competency and allows the addition of it to the database-->
<form class="centre" action="includes/actioncompetencies.inc.php" method="post">
    <input type="text" name="competency" placeholder="Competency Name">
    <br>
    <button class="actionbuttons addbuttons" type="submit" name="create">Add Competency</button>
</form>

<!--
//
// COMPETENCY DELETION
//
-->
<br>

<h3 class="centre">Current Competencies</h3>
<form class="centre" action="includes/actioncompetencies.inc.php" method="post">
<?php //list of Groups in a table
$result = mysqli_query($conn, "SELECT * FROM competencies");

echo "<table border='1' class=\"centre\">
<tr>
<th>Select</th>
<th>Name</th>
</tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . "<input type=\"checkbox\" name=\"competencies[]\" value=\"" . $row["CompetencyID"] . "\">"."</td>"; //Creates Checkbox
    echo "<td>" . $row['CName'] . "</td>"; //Gives name
    echo "</tr>";
}
echo "</table>";
?>
<br>
<button class="dangerous centre" type="submit" name="permdelete">Delete Selected PERMANENTLY</button>
</form>
</section>

<br>
<?php
include_once 'footer.php';
?>