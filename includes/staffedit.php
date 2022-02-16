<?php
//----------Not sure if this is secure, this is for the loading of the group list, have a look at alternative ways------
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

require_once 'modifyusers.inc.php';
require_once 'signup.inc.php';
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
    <form class="centre" action="admin.php" method="post">
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

                echo "<td><label>" . "<input type=\"checkbox\" id=\"" . $row["UserID"] . "-checkbox-users-staffedit\" name=\"users[]\" value=\"" . $row["UserID"] . "\">" . "</label></td>";     //Creates Checkbox
                echo "<td><label for=\"" . $row["UserID"] . "-checkbox-users-staffedit\">" . namePrint($_SESSION, $row) . "</label></td>";   //Gives name
                echo "<td>" . $row["UUsername"] . "</td>";
                echo "<td>" . ($row["URole"] == 2 ? "Manager" : "Staff") . "</td>"; //if is a 3 in the global role, is admin, otherwise is not
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
            echo "<td><label>" . "<input type=\"checkbox\" id=\"" . $group["GroupID"] . "-checkbox-groups-staffedit\" name=\"groups[]\" value=\"" . $group["GroupID"] . "\">" . "</label></td>"; //Creates Checkbox
            echo "<td><label for=\"" . $group["GroupID"] . "-checkbox-groups-staffedit\">" . $group['GName'] . "</label></td>"; //Gives name
            echo "</tr>";
        }
        echo "</table>";
        ?>
        <button class="centre actionbuttons addbuttons" type="submit" name="addG">Add Selected</button>
        <button class="centre actionbuttons rembuttons" type="submit" name="removeG">Remove Selected</button>

    </form>
</section>
<br>
<br>

<hr class="seperator2">
<section id="individualusers">
    <h1 class="centre">Modify Specific Users</h1>
    <table class="centre" border="1">
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Admin</th>
        </tr>

        <?php
        $users = mysqli_query($conn, "SELECT * FROM users");
        if (mysqli_num_rows($users) <= 0) {
            emptyArrayError();
        } else {
            while ($user = mysqli_fetch_array($users)) {
                echo "<tr>";
                echo "<td><p id=\"" . $user["UserID"] . "-name\">" . namePrint($_SESSION, $user) . "</p></td>";
                echo "<td><p id=\"" . $user["UserID"] . "-username\">" . $user["UUsername"] . "</p></td>";
                echo "<td><p id=\"" . $user["UserID"] . "-role\">" . ($user["UAdmin"] == 1  ? "✓" : "✕") . "</p></td>";
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
                            <input type=\"text\" placeholder=\"" . $user["UName"] . "\" name=\"name\" maxlength=\"20\" value=\"" . $user["UName"] . "\">

                            <label for=\"usr\"><b>Username</b></label>
                            <input type=\"text\" placeholder=\"" . $user["UUsername"] . "\" name=\"usr\" maxlength=\"20\" value=\"" . $user["UUsername"] . "\">

                            <label for=\"psw\"><b>Password</b></label>
                            <input type=\"password\" placeholder=\"New Password\" maxlength=\"25\" name=\"psw\">

                            <label for=\"role\"><b>Is Admin</b></label>
                            <input type=\"hidden\" value=\"0\" name=\"admin\">
                            <input type=\"checkbox\" value=\"1\" name=\"admin\" ".($user["UAdmin"] == 1 ? "checked":"") .">

                            <button type=\"button\" class=\"btn\" onclick=\"UpdateUserValuesFromForm('formID-" . $user["UserID"] . "')\">Update Entries</button>
                            <button type=\"button\" class=\"btn cancel\" onclick=\"closeForm('formDiv-" . $user["UserID"] . "')\">Close</button>
                            <br>
                            <br>
                            <button type=\"button\" class=\"dangerous\" onclick=\"deleteUser('" . $user["UserID"] . "')\">Delete user PERMANENTLY</button>
                        
                        </form>
                    </div>
                    </td>";

                echo "</tr>";
            }
        }
        ?>

    </table>
    <p class="centre"><i>A second page-reload may be required to show updated values</i></p>
    <script>
        function openForm(idValue) {//opens user edit form
            var element = document.getElementById(idValue);
            element.style.display = "inline-block";
            element.scrollIntoView(false);
        }

        function closeForm(idValue) {//closes user edit form
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
<hr class="seperator2">
<section id="StaffManage">
    <h3 class="centre">Add Staff Login</h3>
    <form class="centre" action="admin.php" method="post">
        <input type="text" name="name" placeholder="Name" maxlength="20">
        <input type="text" name="username" placeholder="Username" maxlength="20">
        <input type="password" name="pwd" placeholder="password" maxlength="25">
        <label for="role"><b>Is Admin:</b></label>
        <input type="hidden" value="0" name="admin">
        <input type="checkbox" value="1" name="admin">
        <button class="actionbuttons addbuttons" type="submit" name="createUser">Add User</button>
    </form>
    <br>
</section>
