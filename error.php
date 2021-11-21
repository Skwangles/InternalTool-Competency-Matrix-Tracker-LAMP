<?php

if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
        echo "<p class=\"centre error\">Some fields are empty!</p>";
    } else if ($_GET["error"] == "stmtfailure") {
        echo "<p class=\"centre error\">Processing failure occurred!</p>";
    } else if ($_GET["error"] == "invaliduser") {
        echo "<p class=\"centre error \">Value must be alphanumeric!</p>";
    } else if ($_GET["error"] == "invalidcall") {
        echo "<p class=\"centre error\">Page did not have the required information!</p>";
    } else if ($_GET["error"] == "none") {
        echo "<p class=\"centre error\">Successfully Executed!</p>";
    } else if ($_GET["error"] == "usernametaken") {
        echo "<p class=\"centre error\">Username is already taken!</p>";
    }
    else if ($_GET["error"] == "login") {
        echo "<p class=\"centre error\">Successfully Logged In!</p>";
    }  else {
        echo "<p class=\"centre error\">Something went wrong!</p>";
    }
}