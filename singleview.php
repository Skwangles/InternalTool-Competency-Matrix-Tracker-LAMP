<?php
include_once 'includes/header.php';
if (!isset($_SESSION["username"])) {
    header("location: login.php?error=notlogged"); //If not logged in, kicks out
    exit();
}

require_once "includes/dbh.inc.php";
require_once "includes/functions.inc.php";
include_once "includes/admin-inwindow-controls.php"; //gives ability to switch between edit and read-only mode
include_once "includes/tablekey.php";

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


function displaySingleViewRoleAndGroup($conn, $sets, $compGetFunction, $uservalues)
{
    while ($set = mysqli_fetch_row($sets)) {
        if($_SESSION["editMode"] == '1' && isset($set[2])){//Determines if a marker needs to be added to control the Manager option for a user -- only the groups sets value contains isManager values
            $add = "<td><label>Is Manager<input type=\"checkbox\" onclick=\"updateManager(".$uservalues["UserID"].",". $set[0].", this)\" ".($set[2] == "1"?"checked":"")."></label></td>";//If is a group value, adds check box
        }else if(isset($set[2])){//Only groups contains a index 2
            $add = "<td>".($set[2] == "1" ? "(Manager)":"") ."</td>";
        }
        else{
            $add = "";
        }
        echo "<tr><td><b>" . $set[1] . "</b></td>".$add ."</tr>";
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


include_once 'includes/footer.php';
?>