<?php
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["pwd"];
    $role = $_POST["role"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputs($username, $password, $name) !== false) { //checks for empty values
        header("location: ../staffedit.php?error=emptyinput#StaffManage");
        exit();
    }
    if (invalidUser($username) !== false) { //checks for non-alphanumerics
        header("location: ../staffedit.php?error=invaliduser#StaffManage");
        exit();
    }
    if (userExists($conn, $username) !== false) { //checks if username is already taken
        header("location: ../staffedit.php?error=usernametaken#StaffManage");
        exit();
    }
    createUser($conn, $name, $username, $password, $role); //Adds user to database
    header("location: ../staffedit.php?error=none#StaffManage"); //Sends back success message
    exit();
} else {
    header("location: ../staffedit.php?error=invalidcall#StaffManage");
    exit();
}