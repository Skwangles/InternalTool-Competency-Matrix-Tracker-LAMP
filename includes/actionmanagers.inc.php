<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if(isset($_POST["update"]) && isset($_POST["managerValue"]) && isset($_POST["gid"])){//Ensures all the required variables are present
    $userid = $_POST["update"];
    $groupValues = $_POST["managerValue"];
    $groupIDs = $_POST["gid"];
   for($x = 0; $x < count($groupIDs); $x++){
        updateGroupManager($conn, $userid, $groupIDs[$x], $groupValues[$x]);
   }
   managerRoleSwitch($conn, $userid);
   updateUserCompetencies($conn, $userid);
   header("location: ../manageredit.php?error=none");
   exit();
}