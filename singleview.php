<?php
include_once 'header.php';
if (!isset($_SESSION["username"])) {
    header("location: login.php?error=notlogged"); //If not logged in, kicks out
    exit();
}

require_once "includes/dbh.inc.php";
require_once "includes/functions.inc.php";
include_once "admin-inwindow-controls.php"; //gives ability to switch between edit and read-only mode

if (isset($_GET["userid"])) {
    if ($_SESSION["role"] == "3"  ||  isManagerOfUser($conn, $_SESSION, $_GET["userid"]) && $_GET["userid"] != $_SESSION["userid"]) { //Checks if the logged-in-user managed the selected user-to-be-viewed, or If the user is Admin -- cannot view your own competencies in this manner, instead redirects you to the pure "singleview.php" page
        $uservalues = array("UName" => mysqli_fetch_row(mysqli_query($conn, "SELECT UName FROM users WHERE UserID = '" . mysqli_real_escape_string($conn, $_GET["userid"]) . "';"))[0], "UserID" => $_GET["userid"]);
        echo "
        <br>
        <form class=\"centre\" method=\"get\" action=\"managerview.php\">
        <button class=\"actionbuttons adminbuttons\" type=\"submit\">Go Back To Department View</button>
    </form>";
        echo "<h1 class=\"centre\">" . $uservalues["UName"] . "'s Competencies</h1>";
    } else {
        header("location: singleview.php?error=invalidcall");
        exit();
    }
} else {
    $uservalues = array("UName" => $_SESSION["name"], "UserID" => $_SESSION["userid"]);
    echo "<h1 class=\"centre\">Your Personal Competencies</h1>";
}
?>

<table border="1" class="centre">
    <tr>
        <th></th>
        <th><?php echo namePrint($_SESSION, $uservalues); ?></th>
    </tr>
    <?php
    //Individual User Display
    echo "<tr><td><b>Individual User Competencies</b></td></tr>";
    $competencies = IndUserCompetenciesFromUser($conn, $uservalues["UserID"]);
    if (mysqli_num_rows($competencies) <= 0) { //Determines if the array is empty
        emptyArrayError();
    } else {
        while ($competency = mysqli_fetch_assoc($competencies)) {
            echo "<tr><td>" . displayCompetencyName($competency) . "</td>";
            echo "<td>" . displayUserRatings($conn, $competency["CompetencyID"], $uservalues["UserID"]) . "</td>";
        }
    }

    //Role Display
    $role = RoleFromUser($conn, $uservalues["UserID"]);
    displaySingleViewRoleAndGroup($conn, $role, 'CompetencyRolesFromRoles', $uservalues);

    //Group Display
    $groups = UserGroupFromUser($conn, $uservalues["UserID"]);
    displaySingleViewRoleAndGroup($conn, $groups, 'CompetencyGroupFromGroup', $uservalues);
    ?>
    <tr>
        <td>--</td>
        <th><?php $summaryInfo = getCompleteUserSummary($conn, $uservalues["UserID"]);
            echo formatPercent($summaryInfo);
            ?></th>
    </tr>
</table>
<?php


//
//Table Key
//
displayNumberKey();


function displaySingleViewRoleAndGroup($conn, $sets, $compGetFunction, $uservalues)
{
    while ($set = mysqli_fetch_row($sets)) {

        echo "<tr><td><b>" . $set[1] . "</b></td></tr>";
        $competencies = $compGetFunction($conn, $set[0]);
        if (mysqli_num_rows($competencies) <= 0) {
            emptyArrayError();
        } else {
            while ($competency = mysqli_fetch_assoc($competencies)) {
                echo "<tr><td>" . displayCompetencyName($competency) . "</td>";
                echo "<td>" . displayUserRatings($conn, $competency["CompetencyID"], $uservalues["UserID"]) . "</td>";
                echo "</tr>";
            }
        }
    }
}


include_once 'footer.php';
?>