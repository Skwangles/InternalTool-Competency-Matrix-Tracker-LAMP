<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["add"]) && isset($_POST["groupname"])) {
        $name = $_POST["groupname"];
        if (emptyInputs($name, $name, $name)) {//Checks if name is empty - could use *args
                header("location: ../gcedit.php?error=emptygroup#GroupManage");
                exit();
        }
        addGroup($conn, $name);
        header("location: ../gcedit.php?error=none#GroupManage");
        exit();
} elseif (isset($_POST["remove"]) && isset($_POST["groupradio"])) {
          mysqli_query($conn, "DELETE FROM groups WHERE GroupID =" . $_POST["groupradio"]);
          
            $users = getUsers($conn);
            while($user = mysqli_fetch_assoc($users)){//Updates all user's values
                updateUserCompetencies($conn, $user["UserID"]);
                
            }
        header("location: ../gcedit.php?error=none#GroupManage");
        exit();
} elseif (isset($_POST["changeGName"]) && isset($_POST["gnameChange"]) && isset($_POST["groupradio"])) {
    if($_POST["gnameChange"] != ""){
changeGName($conn, $_POST["groupradio"], $_POST["gnameChange"]);
}
header("location: ../gcedit.php?error=none#GroupManage");
        exit();
}else {

        header("location: ../gcedit.php?error=invalidacess");
        exit();
}