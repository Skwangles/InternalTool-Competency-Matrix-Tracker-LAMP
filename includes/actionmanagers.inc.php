<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if(isset($_POST["update"]) && isset($_POST["update"]) && isset($_POST["managerValue"]) && isset($_POST["gid"])){//Ensures all the required variables are present
    $userid = $_POST["update"];
    $groupValues = $_POST["managerValue"];
    $groupIDs = $_POST["gid"];
   for($x = 0; $x < count($groupIDs); $x++){
       echo $groupValues[$x];
       echo $groupIDs[$x];
       echo "<br>";
        mysqli_query($conn,"UPDATE UserGroups SET isManager = ". $groupValues[$x]. " WHERE users = " . $userid . " AND groups = ". $groupIDs[$x]);
   }
   managerRoleSwitch($conn, $userid);
   header("location: ../manageredit.php?error=none");
   exit();
}
else{
    header("location: ../manageredit.php?error=invalidcall");
   exit();
}