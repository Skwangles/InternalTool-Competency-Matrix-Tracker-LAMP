<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if(isset($_POST["updateC"])){
    $user = $_POST["updateC"];
    unset($_POST["updateC"]);//So we only have competency values left
    $competencyValues = $_POST[$user];
    $competencyIDs = $_POST[$user."-id"];
   $itemCount = count($_POST)/2;//There are 2 entries for every 1 competency value - a competency ID and its value
//    echo "<p>Ids</p>";
//    echo var_dump($competencyIDs);
//    echo "<p>Names</p>";
//    echo var_dump($competencyValues);
   for($x = 0; $x < $itemCount; $x++){
    //    echo "<p>Values</p>";
    //    echo $competencyValues[$x];
    //    echo $user;
    //    echo $competencyIDs[$x];
    mysqli_query($conn,"UPDATE UserCompetencies SET Rating = ". $competencyValues[$x]. " WHERE users = " . $user . " AND competencies = ". $competencyIDs[$x]);
   }

   header("location: ../userdefinitions.php?error=none");
   exit();
}
else{
    header("location: ../userdefinitions.php?error=invalidcall");
   exit();
}