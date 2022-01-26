<?php

require_once '../includes/functions.inc.php';
require_once '../includes/dbh.inc.php';
if (isset($_POST["id"]) && isset($_POST["name"]) || isset($_POST["usr"]) || isset($_POST["psw"])) {
    $id = $_POST["id"];
    if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE UserID = '" . $id . "';"))) { //If user exists, we know the user at that index can be found - then uses that index to update that user's values from the other arrays
        if (isset($_POST['name']) && isset($_POST['name']) != "") {
            if (!changeName($conn, $id, $_POST['name'])) {
                echo json_encode(array("status" => "fail - name was unchanged, or was invalid"));
            }
        }
        if (isset($_POST['usr']) && isset($_POST['usr']) != "") {
            if (!changeUsername($conn, $id, $_POST['usr'])) {
                echo json_encode(array("status" => "fail - username was unchanged, or was invalid")); //May return error if the name is the exact same
            }
        }
        if (isset($_POST['psw']) && isset($_POST['psw']) != "") {
            if (!changePassword($conn, $id, $_POST['psw'])) {
                echo json_encode(array("status" => "fail - could not change the password"));
            }
        }
        if (isset($_POST['role']) && isset($_POST['role']) != "") {
            if ($id != $_SESSION["userid"]) { //Cannot update own role. 
                mysqli_query($conn, "UPDATE users SET URole = '" . mysqli_escape_string($conn, $_POST['role']) . "' WHERE UserID = '" . $id . "';"); //Overwrites role with new one
                managerRoleSwitch($conn, $id); //Juggles role if it is 1, to be manager if the user manages any roles - does nothing if the user is now an Admin
                updateUserCompetencies($conn, $id);
            } else {
                echo json_encode(array("status" => "fail - cannot change own role"));
            }
        }
    } else {
        echo json_encode(array("status" => "fail - user does not exist"));
        return;
    }
    echo json_encode(array("status" => "ok"));
} else {
    echo json_encode(array("status" => "fail - failed POST values"));
}
