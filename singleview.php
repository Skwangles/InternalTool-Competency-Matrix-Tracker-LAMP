<?php
include_once 'header.php';
if (!isset($_SESSION["username"])) {
    header("location: login.php?error=notlogged"); //If not logged in, kicks out
    exit();
}

require_once "includes/dbh.inc.php";
require_once "includes/functions.inc.php";
include_once "admin-inwindow-controls.php"; //gives ability to switch between edit and read-only mode


?>
<h1 class="centre">Your Personal Competencies</h1>
<table border="1" class="centre">
    <tr>
        <th></th>
        <th><?php echo $_SESSION["name"] . " (You)"; ?></th>
    </tr>
    <?php
    //Individual User Display
    echo "<tr><td><b>Individual User Competencies</b></td></tr>";
    $competencies = IndUserCompetenciesFromUser($conn, $_SESSION["userid"]);
    if (mysqli_num_rows($competencies) <= 0) {//Determines if the array is empty
        emptyArrayError();
    } else {
    while ($competency = mysqli_fetch_assoc($competencies)) {
        echo "<tr><td>" . displayCompetencyName($competency) . "</td>";
        echo "<td>".displayUserRatings($conn, $competency["CompetencyID"], $_SESSION["userid"])."</td>";
    }
}

    //Role Display
    $role = RoleFromUser($conn, $_SESSION["userid"]);
    displaySingleViewRoleAndGroup($conn, $role, 'CompetencyRolesFromRoles');

    //Group Display
    $groups = UserGroupFromUser($conn, $_SESSION["userid"]);
    displaySingleViewRoleAndGroup($conn, $groups, 'CompetencyGroupFromGroup');
    ?>
    <tr><td>--</td><th><?php $summaryInfo = getCompleteUserSummary($conn, $_SESSION["userid"]); 
        echo formatPercent($summaryInfo);
    ?></th></tr>
</table>
<?php


//
//Table Key
//
displayNumberKey();


function displaySingleViewRoleAndGroup($conn, $sets, $compGetFunction){
    while ($set = mysqli_fetch_row($sets)) {

        echo "<tr><td><b>" . $set[1] . "</b></td></tr>";
        $competencies = $compGetFunction($conn, $set[0]);
        if (mysqli_num_rows($competencies) <= 0) {
            emptyArrayError();
        } else {
            while ($competency = mysqli_fetch_assoc($competencies)) {
                echo "<tr><td>" . displayCompetencyName($competency) . "</td>";
                echo "<td>".displayUserRatings($conn, $competency["CompetencyID"], $_SESSION["userid"])."</td>";
                echo "</tr>";
            }
        }
    }
}


include_once 'footer.php';
?>