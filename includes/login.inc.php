<?php
if (isset($_POST["submit"]) && isset($_POST["username"]) && isset($_POST["pwd"])) {
    $username = $_POST["username"];
    $password = $_POST["pwd"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputs($username) !== false) { //checks for empty values --- password can be empty
        header("location: ../login.php?error=emptyinput");
        exit();
    }
    else if (invalidUser($username) !== false) {
        header("location: ../login.php?error=invaliduser");
        exit();
    }
    else if (userExists($conn, $username) !== false) {
        loginUser($conn, $username, $password); //Sets session variables, after checking values
        header("location: ../index.php?error=login");
        exit();
    } else {
        header("location: ../login.php?error=incorrectlogin");
        exit();
    }
} 
