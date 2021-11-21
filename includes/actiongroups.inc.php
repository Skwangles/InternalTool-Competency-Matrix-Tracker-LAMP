<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (isset($_POST["add"])) {
        $name = $_POST["groupname"];
        require_once 'dbh.inc.php';
        require_once 'functions.inc.php';
        if (emptyInputs($name, $name, $name)) {
                header("location: ../gcedit.php?error=emptygroup#GroupManage");
                exit();
        }
        addGroup($conn, $name);
        header("location: ../gcedit.php?error=none#GroupManage");
                exit();
} elseif (isset($_POST["remove"])) {
        if (isset($_POST["groups"])) {
                $groups = $_POST["groups"];
                foreach ($groups as $groupid) {
                        $groupComp = CompetencyGroupFromGroup($conn, $groupid);
                        $userList = UserGroupFromGroup($conn, $groupid);
                        while ($row = mysqli_fetch_assoc($userList)) {
                                removeCompetenciesAssociatedWithGroup($conn, $groupComp, $row["UserID"], $groupid); //Remove any competencies
                        }
                        mysqli_query($conn, "DELETE FROM Groups WHERE GroupID =" . $groupid);
                }
        }
        header("location: ../gcedit.php?error=none#GroupManage");
        exit();
} else {

        header("location: ../gcedit.php?error=invalidacess");
        exit();
}
