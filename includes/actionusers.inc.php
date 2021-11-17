<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Competency Charts</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <h1>Modify selected user details - Bulk application</h1>
<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "3") {
    header("location: index.php?error=invalidcall");
    exit();
}
if (!isset($_POST["Users"])) {
    header("location: ../admin.php?error=noneselected");
}

require_once 'dbh.inc.php';
require_once 'functions.inc.php';
//
//Gives user the ability to modify the values - i.e. add, remove groups
//
if (isset($_POST["modify"])) {
    //list of Staff in a table
    $users = $_POST["Users"];
    $hiddenUsers = "";
?>


    <form action="includes/actionusers.inc.php" method="post">
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Groups</th>
            </tr>

            <?php

            foreach ($users as $user) {

                $userData = mysqli_query($conn, "SELECT * FROM Users WHERE UserID = " . $user);
                if ($userItem = mysqli_fetch_assoc($userData)) {
                    echo "<tr>";
                    echo "<td>" . $userItem["UName"] . "</td>";   //Gives name
                    echo "<td>" . $userItem["UUsername"] . "</td>";
                    echo "<td>" . $userItem["URole"] . "</td>";
                    echo "<td><ul>";
                }//Displays user if details can be found

                    //Prints the groups each user is assigned to
                    $usersgroups =  UserGroupFromUser($conn, $user); //Gets all groups the user is a part of

                    while ($group = mysqli_fetch_array($usersgroups)) {
                        echo "<li><p>" . $group["GName"] . "</p></li>";
                    }
                    echo "</ul></td>";
                    echo "</tr>";
                    $hiddenUsers .= '<input type="hidden" name="users[]" value="' . $user . '">';//Creates list of hidden inputs - saves later processing
            }
            echo "</table>

</form>";
            //list of Groups in a table
            $groups = getGroups($conn);

            echo "<h3 class=\"banner\">Add or Remove Groups</h3>
            <form action=\"modifyusers.inc.php\" method=\"post\"><table border='1'>
        <tr>
        <th>Select</th>
        <th>Name</th>
        </tr>";

            while ($group = mysqli_fetch_array($groups)) { //Loops through entries in array
                echo "<tr>";
                echo "<td>" . "<br><input type=\"checkbox\" id=\"" . $group["GroupID"] . "\" name=\"groups[]\" value=\"" . $group["GroupID"] . "\">" . "</td>"; //Creates Checkbox
                echo "<td>" . $group['GName'] . "</td>"; //Gives name
                echo "</tr>";
            }
            echo "</table>";
            echo $hiddenUsers; //adds the userIDs as hidden variables
            ?>
            <button type="submit" name="removeG">Remove Selected</button>
            <button type="submit" name="addG">Add Selected</button>
    </form>

    <h3 class="banner">Change Role</h3>
    <form action="modifyusers.inc.php" method="post">
        <?php echo $hiddenUsers; //Gives all participating users IDs 
        ?>
        <select name="role">
            <option value="1">Normal</option>
            <option value="2">Manager</option>
            <option value="3">Admin</option>
        </select>
        <button type="submit" name="roleUpdate">Update Role</button>
    </form>

    <br>
    <form action="../staff.php">
        <button type="submit">Back to Admin Page</button>
    </form>
<?php

    //
    //---Display individual user competencies -- do after single view----------------------------------
    //
}
//
//Deletes user from the database
//
else if (isset($_POST["remove"])) {
    deleteUser($conn, $_POST, $_SESSION);
} else {
    header("location: ../admin.php?error=invalidcall");
    exit();
}
?>
</body>