<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["createC"]) && isset($_POST["competency"])) { //----------------Create Competency----------------
    $name = $_POST["competency"]; //This is where the name of the competency is stored
    if (emptyInputs($name, $name, $name)) {
        header("location: ../admin.php?error=emptyinput");
        exit();
    }
    if (!isset($_POST["competency"])) { //must be empty, if not set
        header("location: ../admin.php?error=emptyinput#CompetencyManage");
        exit();
    }
    addCompetency($conn, $name, isset($_POST["description"]) ? $_POST["description"] : ""); //Adds competency to the database - alsong with description

    header("location: ../admin.php?error=none#CompetencyManage"); //Returns back to edit page - success
    exit();
} elseif (isset($_POST["addC"]) && isset($_POST["competencies"])) { //--------------------------------ADD Competency To Group---------------------
    $selectedCompetencies = $_POST["competencies"]; //Selected values in the form
    echo var_dump($_POST);
    if (isset($_POST["groups"])) { //only if some groups are selected
        $selectedGroups = $_POST["groups"];
        echo "<p>Item</p><br>";
        echo var_dump($selectedGroups);
        echo "<br>";
        echo var_dump($selectedCompetencies);
        foreach ($selectedGroups as $groupid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competencygroups WHERE Competencies = " . $competencyid . " AND Groups = " . $groupid)) == false) { //If no value is found, then insert
                    mysqli_query($conn, "INSERT INTO competencygroups (Competencies, Groups) VALUES ('" . $competencyid . "', '" . $groupid . "')");
                }
            }
        }
    }
    if (isset($_POST["roles"])) { //Only if some roles are selected
        $selectedRoles = $_POST["roles"];
        foreach ($selectedRoles as $roleid) { //Loops through entries in array to apply to multiple roles
            foreach ($selectedCompetencies as $competencyid) {
                if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competencyroles WHERE Competencies = " . $competencyid . " AND Roles = " . $roleid)) == false) {
                    mysqli_query($conn, "INSERT INTO competencyroles (Competencies, Roles) VALUES ('" . $competencyid . "', '" . $roleid . "')");
                }
            }
        }
    }
    if (isset($_POST["users"])) { //Only if some roles are selected
        $users = $_POST["users"];
        foreach ($users as $userid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM individualusercompetencies WHERE Competencies = '" . $competencyid . "' AND Users = '" . $userid."';")) == false) {
                    mysqli_query($conn, "INSERT INTO individualusercompetencies (Competencies, Users) VALUES ('" . $competencyid . "', '" . $userid . "')");
                }
            }
        }
    }

    //
    //----------------------------------------Update All Users Competencies Upon Add-----------
    //
    $users = getUsers($conn);
    while ($user = mysqli_fetch_assoc($users)) {
        updateUserCompetencies($conn, $user["UserID"]);
    }

    header("location: ../admin.php?error=none#AddRemoveCG");
    exit();
} elseif (isset($_POST["removeC"]) && isset($_POST["competencies"])) { //-------------------------Remove Competency From Group------------------------
    $selectedCompetencies = $_POST["competencies"];
    if (isset($_POST["groups"])) {
        $selectedGroups = $_POST["groups"];
        foreach ($selectedGroups as $groupid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                mysqli_query($conn, "DELETE FROM competencygroups WHERE Competencies = " . $competencyid . " AND Groups = " . $groupid); //Deletes every occurence of the groups and competency together
            }
        }
    }
    if (isset($_POST["roles"])) { //Only if some roles are selected
        $roles = $_POST["roles"];
        foreach ($roles as $roleid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                mysqli_query($conn, "DELETE FROM competencyroles WHERE Competencies = " . $competencyid . " AND roles = " . $roleid); //Deletes every occurence of the rioles and competency together
            }
        }
    }
    if (isset($_POST["users"])) { //Only if some roles are selected
        $users = $_POST["users"];
        foreach ($users as $userid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                mysqli_query($conn, "DELETE FROM individualusercompetencies WHERE Competencies = " . $competencyid . " AND Users = " . $userid); //Deletes every occurence of the rioles and competency together
            }
        }
    }
    //
    //----------------------------------------Update All Users Competencies Upon Add-----------
    //
    $users = getUsers($conn);
    while ($user = mysqli_fetch_assoc($users)) {
        updateUserCompetencies($conn, $user["UserID"]);
    }
    header("location: ../admin.php#AddRemoveCG");
    exit();
} elseif (isset($_POST["permdeleteC"]) && isset($_POST["competencyradio"])) { //--------------------------Delete Competency--------------------

        mysqli_query($conn, "DELETE FROM competencies WHERE CompetencyID = " . $_POST["competencyradio"]); //Deletes if it exists
        
     $users = getUsers($conn);
            while($user = mysqli_fetch_assoc($users)){//Updates all user's values
                updateUserCompetencies($conn, $user["UserID"]);
                
            }
    header("location: ../admin.php?error=none#CompetencyManage");
    exit();
} 
elseif (isset($_POST["changeCNameC"]) && isset($_POST["cnameChange"]) && isset($_POST["competencyradio"])) {
     if($_POST["cnameChange"] != ""){
    changeCName($conn, $_POST["competencyradio"], $_POST["cnameChange"]);
     }
    header("location: ../admin.php?error=none#CompetencyManage");
    exit();
}
elseif (isset($_POST["changeCDescriptionC"]) && isset($_POST["description"]) && isset($_POST["competencyradio"])) {
   changeCDescription($conn, $_POST["competencyradio"], $_POST["description"]);
   header("location: ../admin.php?error=none#CompetencyManage");
   exit();
}
