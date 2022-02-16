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
                if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competencygroups WHERE Competencies = '" . mysqli_escape_string($conn,$competencyid) . "' AND Groups = '" . mysqli_escape_string($conn,$groupid)."';")) == false) { //If no value is found, then insert
                    mysqli_query($conn, "INSERT INTO competencygroups (Competencies, Groups) VALUES ('" . mysqli_escape_string($conn,$competencyid) . "', '" . mysqli_escape_string($conn,$groupid) . "')");
                }
            }
        }
    }
    if (isset($_POST["roles"])) { //Only if some roles are selected
        $selectedRoles = $_POST["roles"];
        foreach ($selectedRoles as $roleid) { //Loops through entries in array to apply to multiple roles
            foreach ($selectedCompetencies as $competencyid) {
                if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competencyroles WHERE Competencies = '" . mysqli_escape_string($conn,$competencyid) . "' AND Roles = '" . mysqli_escape_string($conn,$roleid)."';")) == false) {
                    mysqli_query($conn, "INSERT INTO competencyroles (Competencies, Roles) VALUES ('" . mysqli_escape_string($conn,$competencyid) . "', '" . mysqli_escape_string($conn,$roleid) . "')");
                }
            }
        }
    }
    if (isset($_POST["users"])) { //Only if some roles are selected
        $users = $_POST["users"];
        foreach ($users as $userid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM individualusercompetencies WHERE Competencies = '" . mysqli_escape_string($conn,$competencyid) . "' AND Users = '" . mysqli_escape_string($conn,$userid)."';")) == false) {
                    mysqli_query($conn, "INSERT INTO individualusercompetencies (Competencies, Users) VALUES ('" . mysqli_escape_string($conn,$competencyid) . "', '" . mysqli_escape_string($conn,$userid) . "')");
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
                mysqli_query($conn, "DELETE FROM competencygroups WHERE Competencies = '" . mysqli_escape_string($conn,$competencyid) . "' AND Groups = '" . mysqli_escape_string($conn,$groupid)."';"); //Deletes every occurence of the groups and competency together
            }
        }
    }
    if (isset($_POST["roles"])) { //Only if some roles are selected
        $roles = $_POST["roles"];
        foreach ($roles as $roleid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                mysqli_query($conn, "DELETE FROM competencyroles WHERE Competencies = '" . mysqli_escape_string($conn,$competencyid) . "' AND roles = '" . mysqli_escape_string($conn,$roleid)."';"); //Deletes every occurence of the rioles and competency together
            }
        }
    }
    if (isset($_POST["users"])) { //Only if some roles are selected
        $users = $_POST["users"];
        foreach ($users as $userid) { //Loops through entries in array to apply to multiple groups
            foreach ($selectedCompetencies as $competencyid) {
                mysqli_query($conn, "DELETE FROM individualusercompetencies WHERE Competencies = '" . mysqli_escape_string($conn,$competencyid) . "' AND Users = '" . mysqli_escape_string($conn,$userid)."';"); //Deletes every occurence of the rioles and competency together
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

        mysqli_query($conn, "DELETE FROM competencies WHERE CompetencyID = " . mysqli_escape_string($conn,$_POST["competencyradio"])); //Deletes if it exists
        
     $users = getUsers($conn);
            while($user = mysqli_fetch_assoc($users)){//Updates all user's values
                updateUserCompetencies($conn, $user["UserID"]);
                
            }
    header("location: ../admin.php?error=none#CompetencyManage");
    exit();
} 
elseif (isset($_POST["changeCNameC"]) && isset($_POST["cnameNewValue"]) && isset($_POST["competencyradio"])) {
     if($_POST["cnameNewValue"] != ""){
    changeCName($conn, $_POST["competencyradio"], $_POST["cnameNewValue"]);
     }
    header("location: ../admin.php?error=none#CompetencyManage");
    exit();
}
elseif (isset($_POST["changeCDescriptionC"]) && isset($_POST["descriptionNewValue"]) && isset($_POST["competencyradio"])) {
   changeCDescription($conn, $_POST["competencyradio"], $_POST["descriptionNewValue"]);
   header("location: ../admin.php?error=none#CompetencyManage");
   exit();
}