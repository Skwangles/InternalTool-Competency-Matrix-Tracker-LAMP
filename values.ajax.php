<?php

require_once 'includes/functions.inc.php';
require_once 'includes/dbh.inc.php';
if(isset($_POST['UserID']) && isset($_POST['CompetencyID']) && isset($_POST['Value'])){
    $UserID = json_decode($_POST['UserID']);
    $CompetencyID = json_decode($_POST['CompetencyID']);
    $Value = json_decode($_POST['Value']);

    if($updatedValue = updateUserValue($conn, $UserID, $CompetencyID, $Value) !== false){
        error_log("Success: " . $Value . " AND " . $updatedValue);
        echo "Success";
    }
    else{
        error_log("Fail");
        echo "Fail";
    }

}