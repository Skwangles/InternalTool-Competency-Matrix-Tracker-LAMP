<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if(isset($_POST["updateC"]) && isset($_POST["compVal"]) && isset($_POST["id"])){//Making sure the correct variables are present
    $userid = $_POST["updateC"];
    $competencyValues = $_POST["compVal"];
    $competencyIDs = $_POST["id"];
   for($x = 0; $x < count($competencyIDs); $x++){
    mysqli_query($conn,"UPDATE UserCompetencies SET Rating = ". $competencyValues[$x]. " WHERE users = " . $userid . " AND competencies = ". $competencyIDs[$x]);
   }
   header("location: ../uservalues.php?error=none#".$userid);
   exit();
}
else{
    header("location: ../uservalues.php?error=invalidcall");
   exit();
}