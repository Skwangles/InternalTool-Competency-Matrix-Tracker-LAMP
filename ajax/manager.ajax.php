<?php
session_start();
require_once '../includes/functions.inc.php';
require_once '../includes/dbh.inc.php';
if(isset($_POST['UserID']) && isset($_POST['GroupID']) && isset($_POST['Value']) && $_SESSION["isAdmin"] == 1){
    $UserID = json_decode($_POST['UserID']);
    $GroupID = json_decode($_POST['GroupID']);
    $Value = json_decode($_POST['Value']);

    if($updatedValue = updateGroupManager($conn, $UserID, $GroupID, $Value)){
        echo json_encode(array("status"=>"ok", "Value"=>$updatedValue[0]));
    }
    else{
        echo json_encode(array("status"=>"fail"));
    }
}
else{
    echo json_encode(array("status"=>"fail"));
}