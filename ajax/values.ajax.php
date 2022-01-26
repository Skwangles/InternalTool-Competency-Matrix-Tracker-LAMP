<?php

require_once '../includes/functions.inc.php';
require_once '../includes/dbh.inc.php';
if(isset($_POST['UserID']) && isset($_POST['CompetencyID']) && isset($_POST['Value'])){
    $UserID = json_decode($_POST['UserID']);
    $CompetencyID = json_decode($_POST['CompetencyID']);
    $Value = json_decode($_POST['Value']);

    if($updatedValue = updateUserValue($conn, $UserID, $CompetencyID, $Value)){
        echo json_encode(array("status"=>"ok", "uv"=>$updatedValue[0]));
    }
    else{
        echo json_encode(array("status"=>"fail"));
    }
}
else{
    echo json_encode(array("status"=>"fail"));
}