<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["changePassword"]) && isset($_POST["passwordNewValue"])) {
    if($_POST["passwordNewValue"]){
    changePassword($conn, $_SESSION["userid"], $_POST["passwordNewValue"]); //Updates the user's password, to whatever is defined
    updateSession($conn, $_SESSION["userid"]);
    }
    header("location: ../settings.php?error=none");
    exit();
} else if (isset($_POST["changeUsername"]) && isset($_POST["usernameNewValue"])) {
    if($_POST["usernameNewValue"] != ""){
    changeUsername($conn, $_SESSION["userid"], $_POST["usernameNewValue"]); //Updates the user's password, to whatever is defined
    updateSession($conn, $_SESSION["userid"]);
    }
    header("location: ../settings.php?error=none");
    exit();
} 