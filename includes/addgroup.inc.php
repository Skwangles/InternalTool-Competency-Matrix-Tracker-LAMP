<?php

if (isset($_POST["submit"])) {
        $name = $_POST["groupname"];
        require_once 'dbh.inc.php';
        require_once 'functions.inc.php';
        if (emptyInputs($name, $name, $name)) {
                header("location: ../admin.php?error=emptygroup");
                exit();
        }
        addGroup($conn, $name);
} else {
        header("location: ../index.php?error=invalidacess");
        exit();
}
