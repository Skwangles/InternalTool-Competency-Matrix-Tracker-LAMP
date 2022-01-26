<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["createG"]) && isset($_POST["groupname"])) {
        $name = $_POST["groupname"];
        if (emptyInputs($name, $name, $name)) { //Checks if name is empty - could use *args
                header("location: ../admin.php?error=emptygroup#GroupManage");
                exit();
        }
        addGroup($conn, $name);
        header("location: ../admin.php?error=none#GroupManage");
        exit();
} elseif (isset($_POST["permdeleteG"]) && isset($_POST["groupradio"])) {
        mysqli_query($conn, "DELETE FROM groups WHERE GroupID =" . $_POST["groupradio"]);

        $users = getUsers($conn);
        while ($user = mysqli_fetch_assoc($users)) { //Updates all user's values
                updateUserCompetencies($conn, $user["UserID"]);
        }
        header("location: ../admin.php?error=none#GroupManage");
        exit();
} elseif (isset($_POST["changeGNameG"]) && isset($_POST["gnameChange"]) && isset($_POST["groupradio"])) {
        if ($_POST["gnameChange"] != "") {
                changeGName($conn, $_POST["groupradio"], $_POST["gnameChange"]);
        }
        
        header("location: ../admin.php?error=none#GroupManage");
        exit();
}
