<?php
session_start();
require_once '../includes/functions.inc.php';
require_once '../includes/dbh.inc.php';

if($_SESSION["role"] == "3"){
$_SESSION["editMode"] = $_SESSION["editMode"] == "1" ? "0" : "1";//Switches the values
echo json_encode(array("status" => "ok"));
}
else{
   echo json_encode(array("status" => "failed - Inccorect permissions"));
}