<?php 

require_once '../includes/functions.inc.php';
require_once '../includes/dbh.inc.php';

if(isset($_POST['passwordChange']) && isset($_POST["userradio"])){
    if ($_POST["passwordChange"] != "" && changePassword($conn, $_POST["userradio"], $_POST["passwordChange"])) { //Calls the change password method, which returns if it succeeds - first checks the value for an empty string
        echo json_encode(array("status"=>"ok"));
    }
    echo json_encode(array("status"=>"fail - failed updating, or password was empty"));
}
else{
    echo json_encode(array("status"=>"fail - failed POST values"));
}