<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["submit"]) && isset($_POST["competency"])) { //----------------Create Competency----------------
    $name = $_POST["competency"]; //This is where the name of the competency is stored
    if (emptyInputs($name, $name, $name)) {
        header("location: ../gcedit.php?error=emptyinput");
        exit();
    }
    if (!isset($_POST["competency"])) { //must be empty, if not set
        header("location: ../gcedit.php?error=emptyinput");
        exit();
    }

    addCompetency($conn, $name); //Adds competency to the database

    header("location: ../gcedit.php?error=none"); //Returns back to edit page - success
    exit();
} elseif (isset($_POST["addC"]) && isset($_POST["competencies"])) { //--------------------------------ADD Competency---------------------
    $selectedCompetencies = $_POST["competencies"];//Selected values in the form
    if (isset($_POST["groups"])) { //only if some groups are selected
        $selectedGroups = $_POST["groups"];
        foreach ($selectedGroups as $groupid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                if (mysqli_query($conn, "SELECT * FROM CompetencyGroups WHERE Competencies = ". $competencyid . " AND Groups = " . $groupid) === false) {//If no value is found, then insert
                    $sql = mysqli_query($conn, "INSERT INTO CompetencyGroups (Competencies, Groups) VALUES (" . $competencyid . ", " . $groupid . ")");
                }
            }
        }
    }
    if (isset($_POST["roles"])) { //Only if some roles are selected
        $selectedRoles = $_POST["roles"];
        foreach ($selectedRoles as $roleid) { //Loops through entries in array to apply to multiple roles
            foreach ($selectedCompetencies as $competencyid) {
                if (mysqli_query($conn, "SELECT * CompetencyRoles WHERE Competencies = ". $competencyid . " AND Roles = ". $roleid) === false) {
                    mysqli_query($conn, "INSERT INTO CompetencyRoles (Competencies, Roles) VALUES (" . $competencyid . ", " . $roleid . ")");
                }
            }
        }
    }
    header("location: ../gcedit.php?error=none");
    exit();
} elseif (isset($_POST["removeC"]) && isset($_POST["competencies"])) { //-------------------------Remove Competency------------------------
    $selectedCompetencies = $_POST["competencies"];
    if (isset($_POST["Groups"])) {
        $selectedGroups = $_POST["groups"];
        foreach ($selectedGroups as $groupid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                mysqli_query($conn, "DELETE FROM CompetencyGroups WHERE Competencies = " . $competencyid . " AND Groups = " . $groupid); //Deletes every occurence of the groups and competency together

            }
        }
    }
    if (isset($_POST["roles"])) { //Only if some roles are selected
        $roles = $_POST["roles"];
        foreach ($roles as $roleid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competency) {
                mysqli_query($conn, "DELETE FROM Competencyroles WHERE Competencies = " . $competency . " AND roles = " . $roleid); //Deletes every occurence of the rioles and competency together
            }
        }
    }
    header("location: ../gcedit.php?error=none");
    exit();
} elseif (isset($_POST["permdelete"]) && isset($_POST["competencies"])) { //--------------------------Delete Competency--------------------
    $selectedCompetencies = $_POST["competencies"];
    foreach ($selectedCompetencies as $competencyid) {
        mysqli_query($conn, "DELETE FROM competencies WHERE CompetencyID = " . $competencyid); //Deletes if it exists
    }
    header("location: ../gcedit.php?error=none");
} else {
    header("location: ../gcedit.php?error=invalidcall");
    exit();
}
