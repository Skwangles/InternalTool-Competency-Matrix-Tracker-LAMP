<?php
include_once 'header.php';

//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

include_once 'error.php';

include_once 'admin.php';
include_once 'includes/modifyusers.inc.php';
include_once 'includes/signup.inc.php';
?>
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
    <form class="centre" action="staffedit.php" method="post">
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

                echo "<td>" . "<input type=\"checkbox\" id=\"" . $row["UserID"] . "-cbu\" name=\"users[]\" value=\"" . $row["UserID"] . "\">" . "</td>";     //Creates Checkbox
                echo "<td><label for=\"" . $row["UserID"] . "-cbu\">" . namePrint($_SESSION, $row) . "</label></td>";   //Gives name
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
            echo "<td>" . "<input type=\"checkbox\" id=\"" . $group["GroupID"] . "-cb\" name=\"groups[]\" value=\"" . $group["GroupID"] . "\">" . "</td>"; //Creates Checkbox
            echo "<td><label for=\"" . $group["GroupID"] . "-cb\">" . $group['GName'] . "</label></td>"; //Gives name
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
    <table class="centre" border="1">
        <tr>
            <th>Select</th>
            <th>Name</th>
            <th>Username</th>
        </tr>

        <?php
        $users = mysqli_query($conn, "SELECT * FROM users");
        if (mysqli_num_rows($users) <= 0) {
            emptyArrayError();
        } else {
            echo "<script> items = []</script>";
            while ($user = mysqli_fetch_array($users)) {
                echo "<tr>";
                echo "<td><p id=\"" . $user["UserID"] . "-name\">" . $user["UName"] . "</p></td>";
                echo "<td><p id=\"" . $user["UserID"] . "-username\">" . $user["UUsername"] . "</p></td>";
                //Testing having a form popup to edit the values

                //The following provides the user values "edit" form
                echo " <script>
                    document.addEventListener('mouseup', function(e) {
                        var container = document.getElementById('formDiv-" . $user["UserID"] . "');
                        if (!container.contains(e.target)) {
                            container.style.display = 'none';
                        }
                    });
                </script>";

                echo "<td>
                    <button class=\"open-button\" onclick=\"openForm('formDiv-" . $user["UserID"] . "')\">Edit User</button>

                    <div class=\"form-popup\" id=\"formDiv-" . $user["UserID"] . "\">
                        <form class=\"form-container\" id=\"formID-" . $user["UserID"] . "\">
                            <h1>Change " . $user["UName"] . "'s Information</h1>
                            <input type=\"hidden\" value=\"" . $user["UserID"] . "\" name=\"id\">
                            <label for=\"name\"><b>Updated Name</b></label>
                            <input type=\"text\" placeholder=\"" . $user["UName"] . "\" name=\"name\" value=\"" . $user["UName"] . "\">

                            <label for=\"usr\"><b>Username</b></label>
                            <input type=\"text\" placeholder=\"" . $user["UUsername"] . "\" name=\"usr\" value=\"" . $user["UUsername"] . "\">

                            <label for=\"psw\"><b>Password</b></label>
                            <input type=\"password\" placeholder=\"New Password\" name=\"psw\">

                            <label for=\"role\"><b>Is Admin</b></label>
                            <input type=\"hidden\" value=\"1\" name=\"role\">
                            <input type=\"checkbox\" value=\"3\" name=\"role\" ".($user["URole"] == 3 ? "checked":"") .">

                            <button type=\"button\" class=\"btn\" onclick=\"UpdateUserValuesFromForm('formID-" . $user["UserID"] . "')\">Update Entries</button>
                            <button type=\"button\" class=\"btn cancel\" onclick=\"closeForm('formDiv-" . $user["UserID"] . "')\">Close</button>
                        </form>
                        
                    </div>
                    </td>";

                echo "</tr>";
            }
        }
        ?>

    </table>
    <script>
        function openForm(idValue) {
            var element = document.getElementById(idValue);
            element.style.display = "inline-block";
            element.scrollIntoView(false);
        }

        function closeForm(idValue) {
            document.getElementById(idValue).style.display = "none";
        }
    </script>

</section>
<!--
//
// USER SIGNUP
//
//
-->
<hr class="seperator">
<section id="StaffManage">
    <h3 class="centre">Add Staff Login</h3>
    <form class="centre" action="staffedit.php" method="post">
        <input type="text" name="name" placeholder="Name" maxlength="20">
        <input type="text" name="username" placeholder="Username" maxlength="20">
        <input type="password" name="pwd" placeholder="password" maxlength="25">
        <label for="role">Account Type:</label>
        <select name="role" id="role">
            <option value="1">Non-Admin</option>
            <option value="3">Admin</option>
        </select>
        <button class="actionbuttons addbuttons" type="submit" name="createUser">Add User</button>
    </form>
    <br>
</section>

<br>
<br>
<?php

include_once 'footer.php';
