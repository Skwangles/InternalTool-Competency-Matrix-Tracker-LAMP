<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["changePassword"]) && isset($_POST["passwordChange"])) {
    $password = $_POST["passwordChange"];

    changePassword($conn, S_SESSION["userid"], $password); //Updates the user's password, to whatever is defined

    header("location: ../settings.php?error=none");
    exit();
} else if (isset($_POST["changeUsername"]) && isset($_POST["usernameChange"])) {
    $username = $_POST["usernameChange"];
    changeUsername($conn, S_SESSION["userid"], $password); //Updates the user's password, to whatever is defined

    header("location: ../settings.php?error=none");
    exit();
} else {
    header("location: ../settings.php?error=invalidcall");
    exit();
}
