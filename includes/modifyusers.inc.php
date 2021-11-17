<?php
require_once "dbh.inc.php";
require_once "functions.inc.php";
session_start(); //Allows access to $_SESSION for the roleUpdate
if (isset($_POST["addG"])) { //If add group was selected - add group
    $users = $_POST["users"];
    $groups = $_POST["groups"];

    foreach ($groups as $group) { //Loops through entries in array to apply to multiple groups
        $resultData = UserGroupFromGroup($conn, $group);
        foreach ($users as $user) {
            $canAdd = true;
            while ($result = mysqli_fetch_array($resultData)) { //Checks if any users already exist in UserGroups table for that group
                if ($result["UserID"] == $user) {
                    $canAdd = false;
                }
            }
            //Skipping outside the while loop - using the false - could have used continue, but wouldn't have worked in While

            if ($canAdd) { //Only adds the users unselected
                $sql = mysqli_query($conn, "INSERT INTO UserGroups (Users, Groups) VALUES (" . $user . ", " . $group . ")");
            }
        }
    }
    //
    //--- Add Competencies Connected to the Role from the user..
    //
    header("location: ../staffedit.php?error=none");
    exit();
} else if (isset($_POST["removeG"])) { //If remove group was selected - remove group
    $users = $_POST["users"];
    $groups = $_POST["groups"];

    foreach ($groups as $group) { //Loops through every entry
        foreach ($users as $user) {
            mysqli_query($conn, "DELETE FROM UserGroups WHERE Groups = " . $group . " AND Users = " . $user); //If it is in the table, add, otherwise skip
        }
    } //--foreach end--

    //  
    //--- Remove Competencies Connected to the Group from the user..
    //

    header("location: ../staffedit.php?error=none");
    exit();
} else if (isset($_POST["roleUpdate"])) { //If role update was selected - update role
    $users = $_POST["users"];
    foreach ($users as $user) { //Foreach user selected, update the Role to be the desired
        if ($user == $_SESSION["userid"]) continue; //Cannot update own role. 
        mysqli_query($conn, "UPDATE Users SET URole = " . $_POST["role"] . " WHERE UserID = " . $user); //Overwrites role with new one
    }

    //--- Remove Competencies Connected to the Role from the user..

    header("location: ../staffedit.php?error=none");
    exit();
} elseif (isset($_POST["delete"])) {
    $users = $_POST["users"];
    foreach ($users as $User) {
        mysqli_query($conn, "DELETE FROM Users WHERE UserID = " . $User);
    }
    header("location: ../staffedit.php?error=none");
    exit();
} else {
    header("location: ../staffedit.php?error=invalidcall");
    exit();
}
