<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["submit"])) {
    $name = $_POST["competency"]; //This is where the name of the competency is stored
    if (emptyInputs($name, $name, $name)) {
        header("location: ../competencyedit.php?error=emptyinput");
        exit();
    }
    if (count($_POST) < 2) { //If the only value is "submit" there must be an empty value
        header("location: ../competencyedit.php?error=emptyinput");
        exit();
    }
    addCompetency($conn, $name);
    header("location: ../competencyedit.php?error=none"); //Returns back to page - success
    exit();
} elseif (isset($_POST["addC"])) {
    $competencies = $_POST["competencies"];
    if (isset($_POST["groups"])) {//only if some groups are selected
        $groups = $_POST["groups"];
        foreach ($groups as $group) { //Loops through entries in array to apply to multiple groups
            $resultData = CompetencyGroupFromGroup($conn, $group);
            foreach ($competencies as $competency) {
                $canAdd = true;
                while ($result = mysqli_fetch_array($resultData)) { //Checks if any users already exist in UserGroups table for that group
                    if ($result["CompetencyID"] == $competency) {
                        $canAdd = false; //Doesn't allow the item to be added if there exists a duplicate
                    }
                }
                //Skipping outside the while loop - using the false - could have used continue, but wouldn't have worked in While
                if ($canAdd) {
                    $sql = mysqli_query($conn, "INSERT INTO CompetencyGroups (Competencies, Groups) VALUES (" . $competency . ", " . $group . ")");
                }
            }
        }
    }
    if (isset($_POST["roles"])) { //Only if some roles are selected
        $roles = $_POST["roles"];
        foreach ($roles as $role) { //Loops through entries in array to apply to multiple roles
            $resultData = CompetencyRolesFromRoles($conn, $role);
            foreach ($competencies as $competency) {
                $canAdd = true;
                while ($result = mysqli_fetch_array($resultData)) { //Checks if any users already exist in UserGroups table for that group
                    if ($result["CompetencyID"] == $competency) {
                        $canAdd = false; //Doesn't allow the item to be added if there exists a duplicate
                    }
                }
                //Skipping outside the while loop - using the false - could have used continue, but wouldn't have worked in While
                if ($canAdd) {
                    $sql = mysqli_query($conn, "INSERT INTO CompetencyRoles (Competencies, Roles) VALUES (" . $competency . ", " . $role . ")");
                }
            }
        }
    }
    header("location: ../competencyedit.php?error=none");
    exit();
} elseif (isset($_POST["removeC"])) {
    $competencies = $_POST["competencies"];
    if (isset($_POST["Groups"])) {
        $groups = $_POST["groups"];
        foreach ($groups as $group) { //Loops through entries in array to apply to multiple groups
            foreach ($competencies as $competency) {
                $sql = mysqli_query($conn, "DELETE FROM CompetencyGroups WHERE Competencies = " . $competency . " AND Groups = " . $group); //Deletes every occurence of the groups, and each of the competencies together
            }
        }
    }
    if (isset($_POST["roles"])) { //Only if some roles are selected
        $roles = $_POST["roles"];
        foreach ($roles as $role) { //Loops through entries in array to apply to multiple groups
            foreach ($competencies as $competency) {
                $sql = mysqli_query($conn, "DELETE FROM Competencyroles WHERE Competencies = " . $competency . " AND roles = " . $roles); //Deletes every occurence of the groups, and each of the competencies together
            }
        }
    }
    header("location: ../competencyedit.php?error=none");
    exit();
} elseif (isset($_POST["permdelete"])) {
    $competencies = $_POST["competencies"];
    foreach ($competencies as $competency) {
        mysqli_query($conn, "DELETE FROM competencies WHERE CompetencyID = " . $competency); //Deletes if it exists
    }
    header("location: ../competencyedit.php?error=none");
} else {
    header("location: ../competencyedit.php?error=invalidcall");
    exit();
}
