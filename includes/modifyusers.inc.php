<?php
require_once "dbh.inc.php";
require_once "functions.inc.php";
session_start(); //Allows access to $_SESSION for the roleUpdate
if (isset($_POST["addG"])) { //If add group was selected - add group
    $users = $_POST["users"];
    $groups = $_POST["groups"];

    foreach ($groups as $group) { //Loops through entries in array to apply to multiple groups
        foreach ($users as $user) {
            $competencies = CompetencyGroupFromGroup($conn, $group);//Renews the array - can probably just store another value and then reset it to save queries - but I am going for reliability first...
            if (!mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM UserGroups WHERE users = " . $user . " AND groups = " . $group))) { //If entry is not present, add to user groups, and add the associated competencies
                mysqli_query($conn, "INSERT INTO UserGroups (Users, Groups) VALUES (" . $user . ", " . $group . ")");
                //Add competencies associated witht he groups to user
                while ($competency = mysqli_fetch_array($competencies)) { //goes through list of competencies, finds ones which do not exist - and adds
                    if (!mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM UserCompetencies WHERE users = " . $user . " AND competencies = " . $competency["CompetencyID"]))) {
                        mysqli_query($conn, "INSERT INTO UserCompetencies (Users, Competencies) VALUES (" . $user . ", " . $competency["CompetencyID"] . ")");
                    }
                }
            }
        }
    }
    header("location: ../staffedit.php?error=none");
    exit();
} else if (isset($_POST["removeG"])) { //If remove group was selected - remove group
    $users = $_POST["users"];
    $groups = $_POST["groups"];

    foreach ($groups as $group) { //Loops through every entry
        foreach ($users as $user) {
            mysqli_query($conn, "DELETE FROM UserGroups WHERE Groups = " . $group . " AND Users = " . $user); //If it is in the table, add, otherwise skip
            $competencies = CompetencyGroupFromGroup($conn, $group);
            while ($competency = mysqli_fetch_array($competencies)) { //Deletes competencies associated with the group

                //
                //----------For Future, check that the competency is not associated with another group the user is a part of - for now, functionality
                //
                mysqli_query($conn, "DELETE FROM UserCompetencies WHERE Competencies = " . $competency["CompetencyID"] . " AND Users = " . $user);
            }
        }
    } //--foreach end--
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
