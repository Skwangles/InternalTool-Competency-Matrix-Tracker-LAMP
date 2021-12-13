<?php

require_once '../includes/functions.inc.php';
require_once '../includes/dbh.inc.php';

if (isset($_POST['uname']) && isset($_POST["uusername"]) && isset($_POST["ids"])) {
    $names = $_POST['uname'];
    $usernames = $_POST["uusername"];
    $ids = $_POST["ids"];
    $idcount = count($ids);
    $namecount = count($names);
    $unamecount = count($usernames);

    if ($idcount != $namecount || $namecount != $unamecount) { //For the following logic, the arrays must link correctly between indexes - i.e. id[0] gives userid for name[0] - therefore all arrays must have the same count
        echo json_encode(array("status" => "fail - array sizes unequal"));
    }

    for ($index = 0; $index < count($ids); $index++) {
        if ($user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE UserID = '" . $ids[$index] . "';"))) { //If user exists, we know the user at that index can be found - then uses that index to update that user's values from the other arrays
            if (!changeName($conn, $ids[$index], $names[$index])) {
                echo json_encode(array("status" => "fail - a name value was invalid"));
            }
            if (!changeUsername($conn, $ids[$index], $usernames[$index])) {
                echo json_encode(array("status" => "fail - a username value was invalid"));
            }
        }
    }
    echo json_encode(array("status" => "ok"));
} else {
    echo json_encode(array("status" => "fail - failed POST values"));
}
