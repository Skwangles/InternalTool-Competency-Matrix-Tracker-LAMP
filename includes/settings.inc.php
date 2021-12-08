<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["changePassword"]) && isset($_POST["passwordChange"])) {
    if($_POST["passwordChange"]){
    changePassword($conn, $_SESSION["userid"], $_POST["passwordChange"]); //Updates the user's password, to whatever is defined
    updateSession($conn, $_SESSION["userid"]);
    }
    
    header("location: ../settings.php?error=none");
    exit();
} else if (isset($_POST["changeUsername"]) && isset($_POST["usernameChange"])) {
    if($_POST["usernameChange"] != ""){
    changeUsername($conn, $_SESSION["userid"], $_POST["usernameChange"]); //Updates the user's password, to whatever is defined
    updateSession($conn, $_SESSION["userid"]);
    }
    header("location: ../settings.php?error=none");
    exit();
} 
