<?php

if (isset($_POST["add"])) {
        $name = $_POST["groupname"];
        require_once 'dbh.inc.php';
        require_once 'functions.inc.php';
        if (emptyInputs($name, $name, $name)) {
                header("location: ../admin.php?error=emptygroup");
                exit();
        }
        addGroup($conn, $name);
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
        header("location: ../groupedit.php?error=none");
        exit();
} else {

        header("location: ../index.php?error=invalidacess");
        exit();
}
