<?php
require_once "dbh.inc.php";
require_once "functions.inc.php";
session_start(); //Allows access to $_SESSION for the roleUpdate
if (isset($_POST["addG"])) { //If add group was selected - add group
    $users = $_POST["users"];
    $groups = $_POST["groups"];
    foreach ($groups as $groupid) { //Loops through entries in array to apply to multiple groups
        $itemsFromCG = CompetencyGroupFromGroup($conn, $groupid);
        foreach ($users as $userid) {
            $competencies = $itemsFromCG; //Renews the array - can probably just store another value and then reset it to save queries - but I am going for reliability first...
            if (!mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM UserGroups WHERE users = " . $userid . " AND groups = " . $groupid))) { //If entry is not present, add to user groups, and add the associated competencies
                mysqli_query($conn, "INSERT INTO UserGroups (Users, Groups) VALUES (" . $userid . ", " . $groupid . ")");
                //Add competencies associated witht he groups to user
                addCompetenciesAssociatedWithGroup($conn, $userid, $competencies);
            }
        }
    }
    header("location: ../staffedit.php?error=none");
    exit();
} else if (isset($_POST["removeG"])) { //If remove group was selected - remove group
    $selectedUsers = $_POST["users"];
    $selectedGroups = $_POST["groups"];

    foreach ($selectedGroups as $groupid) { //Loops through every entry
        $itemsFromCG = CompetencyGroupFromGroup($conn, $groupid);
        foreach ($selectedUsers as $userid) {
            mysqli_query($conn, "DELETE FROM UserGroups WHERE Groups = " . $groupid . " AND Users = " . $userid); //If it is in the table, add, otherwise skip
            removeCompetenciesAssociatedWithGroup($conn, $itemsFromCG, $userid, $groupid);
        }
    } //--foreach end--
    header("location: ../staffedit.php?error=none");
    exit();
} else if (isset($_POST["roleUpdate"])) { //If role update was selected - update role
    $users = $_POST["users"];
    foreach ($users as $userid) { //Foreach user selected, update the Role to be the desired
        if ($userid == $_SESSION["userid"]) continue; //Cannot update own role. 

        $oldRole =  mysqli_fetch_row(mysqli_query($conn, "SELECT URole FROM Users WHERE UserID = " . $userid))[0];
        
        mysqli_query($conn, "UPDATE Users SET URole = " . $_POST["role"] . " WHERE UserID = " . $userid); //Overwrites role with new one

        addCompetenciesAssociatedWithRole($conn, $userid, CompetencyRolesFromRoles($conn, $_POST["role"]));
        removeCompetenciesAssociatedWithRole($conn, CompetencyRolesFromRoles($conn, $oldRole), $userid, $oldRole);//removed after the change to ensure that any values shared between roles are preserved
    }

    header("location: ../staffedit.php?error=none");
    exit();
} elseif (isset($_POST["delete"])) {
    $users = $_POST["users"];
    foreach ($users as $userid) {
        if ($userid == $_SESSION["userid"]) continue; //Cannot delete yourself. 
        mysqli_query($conn, "DELETE FROM Users WHERE UserID = " . $userid);
    }
    header("location: ../staffedit.php?error=none");
    exit();
} else {
    header("location: ../staffedit.php?error=invalidcall");
    exit();
}
