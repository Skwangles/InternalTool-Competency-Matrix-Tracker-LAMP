<?php
require_once "dbh.inc.php";
require_once "functions.inc.php";
if (isset($_POST["addG"]) && isset($_POST["users"]) && isset($_POST["groups"])) { //If add group was selected - add group
    $users = $_POST["users"];
    $groups = $_POST["groups"];
    foreach ($groups as $groupid) { //Loops through entries in array to apply to multiple groups
        foreach ($users as $userid) {
            if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM usergroups WHERE Users = '" . $userid . "' AND Groups = '" . $groupid . "';")) == false) { //If entry is not present, add to user groups, and add the associated competencies
                mysqli_query($conn, "INSERT INTO usergroups (Users, Groups) VALUES ('" . $userid . "', '" . $groupid . "')");
                //Add competencies associated with the groups to user
                updateUserCompetencies($conn, $userid);
            }
        }
    }
    header("location: ../staffedit.php?error=none#AddRemoveGR");
    exit();
} else if (isset($_POST["removeG"]) && isset($_POST["users"]) && isset($_POST["groups"])) { //If remove group was selected - remove group
    $selectedUsers = $_POST["users"];
    $selectedGroups = $_POST["groups"];
    foreach ($selectedGroups as $groupid) { //Loops through every entry
        foreach ($selectedUsers as $userid) {
            mysqli_query($conn, "DELETE FROM usergroups WHERE Groups = '" . $groupid . "' AND Users = '" . $userid . "';"); //If it is in the table, add, otherwise skip
            updateUserCompetencies($conn, $userid);
        }
    } //--foreach end--
    header("location: ../staffedit.php?error=none#AddRemoveGR");
    exit();
} else if (isset($_POST["roleUpdate"]) && isset($_POST["users"]) && isset($_POST["role"])) { //If role update was selected - update role
    $users = $_POST["users"];
    $role = $_POST["role"];
    foreach ($users as $userid) { //Foreach user selected, update the Role to be the desired
        if ($userid == $_SESSION["userid"]) continue; //Cannot update own role. 
        mysqli_query($conn, "UPDATE users SET URole = '" . $role . "' WHERE UserID = '" . $userid . "';"); //Overwrites role with new one
        managerRoleSwitch($conn, $userid);
        updateUserCompetencies($conn, $userid);
    }
    updateSession($conn, $_SESSION["userid"]); //Makes sure if the user is updated, that they would have correct variables
    header("location: ../staffedit.php?error=none#AddRemoveGR");
    exit();
}
//
//
//INDIVIDUAL USER MODIFICATIONS
//
//
elseif (isset($_POST["delete"]) && isset($_POST["userradio"])) {
    if ($_POST["userradio"] != $_SESSION["userid"]) { //Cannot delete yourself. 
        mysqli_query($conn, "DELETE FROM users WHERE UserID = '" . $_POST["userradio"] . "';"); //Deletes user from the system
        header("location: ../staffedit.php?error=none#individualusers");
        exit();
    }
    header("location: ../staffedit.php?error=invalidcall#individualusers"); //Returns error
    exit();
} else if (isset($_POST["changePassword"]) && isset($_POST["passwordChange"]) && isset($_POST["userradio"])) { //Updates all selected user's passwords 
    if ($_POST["passwordChange"] != "" && changePassword($conn, $_POST["userradio"], $_POST["passwordChange"])) { //Calls the change password method, which returns if it succeeds - first checks the value for an empty string
        header("location: ../staffedit.php?error=none#individualusers");
        exit();
    } //Updates the user's password, to whatever is defined
    header("location: ../staffedit.php?error=invalidcall#individualusers");
    exit();
} else if (isset($_POST["changeName"]) && isset($_POST["nameChange"]) && isset($_POST["userradio"])) { //Updates all selected user's passwords
    $userid = $_POST["userradio"];
    $name = $_POST["nameChange"];
    if ($name != "" && changeName($conn, $userid, $name)) { //Makes sure the value is not empty, then calls the function which returns success or failure
        updateSession($conn, $_SESSION["userid"]); //Makes sure if the user is updated, that they would have correct variables
        header("location: ../staffedit.php?error=none#individualusers");
        exit();
    } //Updates the user's password, to whatever is defined
    header("location: ../staffedit.php?error=invalidcall#individualusers");
    exit(); //Updates the user's password, to whatever is defined
} else if (isset($_POST["changeUsername"]) && isset($_POST["usernameChange"]) && isset($_POST["userradio"])) { //Updates all selected user's passwords
    $userid = $_POST["userradio"];
    $username = $_POST["usernameChange"];
    if ($username != "" && changeUsername($conn, $userid, $username)) { //if true, then success, else failure - first checks the value for an empty string
        updateSession($conn, $_SESSION["userid"]); //Makes sure if the user is updated, that they would have correct variables
        header("location: ../staffedit.php?error=none#individualusers");
        exit();
    } //Updates the user's password, to whatever is defined
    header("location: ../staffedit.php?error=invalidcall#individualusers");
    exit(); //Updates the user's password, to whatever is defined
}
else{

}