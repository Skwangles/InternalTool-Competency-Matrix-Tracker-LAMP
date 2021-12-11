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
    } else if ($_GET["error"] == "login") {
        echo "<p class=\"centre error\">Successfully Logged In!</p>";
    } else {
        echo "<p class=\"centre error\">Something went wrong!</p>";
    }
}

//
// <div id="opacitylayer" style="display:none;z-index:10000;">
//     <div class="error" id="errorbox" style="display:none;"></div>
// </div>
// <script> -->
//     var urlParams = new URLSearchParams(window.location.search);//Checks the URL for Get Param, if found gives an error window
//     if (urlParams.get('error')) { //Checks if it exists
//         if (urlParams.get('error') == "emptyinput") {
//             showError("Some fields are empty!");
//         } else if (urlParams.get('error') == "stmtfailure") {
//             showError("Processing failure occurred!");
//         } else if (urlParams.get('error') == "invaliduser") {
//             showError("Value must be alphanumeric!");
//         } else if (urlParams.get('error') == "invalidcall") {
//             showError("Page did not have the required information!");
//         }
//         // else if (urlParams.get('error') == "none") {
//         //     showError("Successfully Executed!");
//         // } 
//         else if (urlParams.get('error') == "usernametaken") {
//             showError("Username is already taken!");
//         } else if (urlParams.get('error') == "login") {
//             showError("Successfully Logged In!");
//         } else {
//             showError("Something went wrong!");
//         }
//     }
// </script>