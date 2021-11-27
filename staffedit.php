<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

include_once 'error.php';

include_once 'admin.php';
?>
<!----
//
// USER SIGNUP
//
//
-->
<section id="StaffManage">
    <h3 class="centre">Add Staff Login</h3>
    <form class="centre" action="includes/signup.inc.php" method="post">
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="pwd" placeholder="password">
        <label for="role">Account Type:</label>
        <select name="role" id="role">
            <option value="1">Non-Admin</option>
            <option value="3">Admin</option>
        </select>
        <button class="actionbuttons addbuttons" type="submit" name="submit">Add User</button>
    </form>
    <br>
</section>
<hr class="seperator">
<!----
//
//STAFF GROUP ADDING
//
//
-->
<section id="AddRemoveGR">
    <h3 class="centre"> Modify Staff Groups & Roles </h3>

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
                echo "<td>" . "<input type=\"checkbox\" id=\"".$row["UserID"]."-cbu\" name=\"users[]\" value=\"" . $row["UserID"] . "\">" . "</td>";     //Creates Checkbox
                echo "<td><label for=\"".$row["UserID"]."-cbu\">" . namePrint($_SESSION, $row) . "</label></td>";   //Gives name
                echo "<td>" . $row["UUsername"] . "</td>";
                echo "<td>" . ($row["URole"] == 3 ? "Admin" : ($row["URole"] == 2 ? "Manager" : "Staff")) . "</td>"; //if is a 3 in the global role, is admin, otherwise is not
                echo "<td><ul>";

                $usersgroups = UserGroupFromUser($conn, $row["UserID"]);

                while ($group = mysqli_fetch_array($usersgroups)) {
                    echo "<li><p style=\"text-align:left;\">" . $group["GName"] . (IsManagerOfGroup($conn, $row["UserID"], $group["GroupID"]) ? " (Manager)" : "") . "</p></li>"; //Prints out group name - includes (manager) if the user manages that group
                }
                echo "</ul></td>";
                echo "</tr>";
            }
            ?>

        </table>
     
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
            echo "<td>" . "<input type=\"checkbox\" id=\"".$group["GroupID"]."-cb\" name=\"groups[]\" value=\"" . $group["GroupID"] . "\">" . "</td>"; //Creates Checkbox
            echo "<td><label for=\"".$group["GroupID"]."-cb\">" . $group['GName'] . "</label></td>"; //Gives name
            echo "</tr>";
        }
        echo "</table>";
        ?>
        <button class="centre actionbuttons addbuttons" type="submit" name="addG">Add Selected</button>
        <button class="centre actionbuttons rembuttons" type="submit" name="removeG">Remove Selected</button>
        

        <h3 class="centre">Define Admin Users</h3>

        <select class="centre" name="role">
            <option value="1">Non-Admin</option>
            <option value="3">Admin</option>
        </select>
        <button class="centre actionbuttons addbuttons" type="submit" name="roleUpdate">Update Role</button>
  
    </form>
</section>
<br>
<br>

<hr class="seperator">
<section id="individualusers">
    <h1 class="centre">Modify Specific Users</h1>
    <form class="centre" action="includes/modifyusers.inc.php" method="post">
        <table class="centre" border="1">
            <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Username</th>
            </tr>

            <?php
            $result = mysqli_query($conn, "SELECT * FROM users");
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . "<input type=\"radio\" id=\"" . $row["UserID"] . "-radio\" name=\"userradio\" value=\"" . $row["UserID"] . "\">" . "</td>";     //Creates Checkbox
                echo "<td><label for=\"" . $row["UserID"] . "-radio\">" . namePrint($_SESSION, $row) . "</label></td>";   //Gives name
                echo "<td><label for=\"" . $row["UserID"] . "-radio\">" . $row["UUsername"] . "</label></td>";
                echo "</tr>";
            }
            ?>

        </table>



        <h3 class="centre">Change Name</h3>

        <input type="text" name="nameChange">
        <button class="centre actionbuttons addbuttons" type="submit" name="changeName">Update Name</button>

        <h3 class="centre">Change Username</h3>

        <input type="text" name="usernameChange">
        <button class="centre actionbuttons addbuttons" type="submit" name="changeUsername">Update Username</button>

        <h3 class="centre">Change Password</h3>

        <input type="text" name="passwordChange">
        <button class="centre actionbuttons addbuttons" type="submit" name="changePassword">Update Password</button>
        <br>
        <br>
        <button class="centre dangerous" type="submit" name="delete">Delete User PERMANENTLY</button>
        
    </form>
</section>

<br>
<br>
<?php
//Handles error tags

include_once 'footer.php';
?>