<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if(isset($_POST["updateC"])){
    $user = $_POST["updateC"];
    unset($_POST["updateC"]);//So we only have competency values left
    $competencyValues = $_POST[$user];
    $competencyIDs = $_POST[$user."-id"];
   $itemCount = count($competencyIDs);
   echo "<p>Ids</p>";
   echo var_dump($competencyIDs);
   echo "<p>Names</p>";
   echo var_dump($competencyValues);
   for($x = 0; $x < $itemCount; $x++){
       echo "<p>Values</p>";
       echo $competencyValues[$x] . "<br>";
       echo $user. "<br>";
       echo $competencyIDs[$x]. "<br>";
    mysqli_query($conn,"UPDATE UserCompetencies SET Rating = ". $competencyValues[$x]. " WHERE users = " . $user . " AND competencies = ". $competencyIDs[$x]);
   }
   header("location: ../userdefinitions.php?error=none");
   exit();
}
else{
    header("location: ../userdefinitions.php?error=invalidcall");
   exit();
}