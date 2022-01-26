<?php
session_start();
require_once '../includes/functions.inc.php';
require_once '../includes/dbh.inc.php';
if (isset($_POST["UserID"])) {
    if (!deleteUser($conn, $_POST, $_SESSION)) {
        echo json_encode(array("status" => "fail - could not delete user"));
        return;
    }
    echo json_encode(array("status" => "ok"));
} else {
    echo json_encode(array("status" => "fail - failed POST values"));
}
