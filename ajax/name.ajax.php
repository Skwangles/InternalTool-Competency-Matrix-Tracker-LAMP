<?php 

require_once '../includes/functions.inc.php';
require_once '../includes/dbh.inc.php';

if(isset($_POST['UserID']) && isset($_POST['Value'])){
    $UserID = json_decode($_POST['UserID']);
    $Value = json_decode($_POST['Value']);

    if($updatedValue = changeName($conn, $UserID, $Value)){
        echo json_encode(array("status"=>"ok", "Value"=>$updatedValue[0]));
    }
    else{
        echo json_encode(array("status"=>"fail - couldn't update"));
    }
}
else{
    echo json_encode(array("status"=>"fail-wrong post values"));
}