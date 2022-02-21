
<?php
//No use for index.php, so sends user to their personal view - or login, if not logged in. 
session_start();
if (isset($_SESSION["username"])) {

    header("location: ../singleview.php");
    exit();
} else {

    header("location: ../login.php");
    exit();
}


