<?php
if (isset($_POST["createUser"]) && isset($_POST["name"]) && isset($_POST["username"]) && isset($_POST["pwd"]) && isset($_POST["role"])) {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["pwd"];
    $role = $_POST["role"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputs($username, $password, $name) !== false) { //checks for empty values
        header("location: ../admin.php?error=emptyinput#StaffManage");
        exit();
    }
    if (invalidUser($username) !== false) { //checks for non-alphanumerics
        header("location: ../admin.php?error=invaliduser#StaffManage");
        exit();
    }
    if (userExists($conn, $username) !== false) { //checks if username is already taken
        header("location: ../admin.php?error=usernametaken#StaffManage");
        exit();
    }
    createUser($conn, $name, $username, $password, $role); //Adds user to database
    header("location: ../admin.php?error=none#StaffManage"); //Sends back success message
    exit();
}
