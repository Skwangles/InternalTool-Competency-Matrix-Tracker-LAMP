<?php
include_once 'header.php';
if (!isset($_SESSION["username"])) {
    header("location: login.php?error=notlogged"); //If not logged in, kicks out
    exit();
}

require_once "includes/dbh.inc.php";
require_once "includes/functions.inc.php";
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
    $isNull = true; //Determines if the while ended before the first loop
    while ($competency = mysqli_fetch_array($competencies)) {
        $isNull = false;
        echo "<tr><td>" . $competency["CName"] . "</td>";
        displayUserRatings($conn, $competency["CompetencyID"], $_SESSION["userid"]);
    }
    if ($isNull) {
        $isNull = emptyArrayError($isNull); //Prints out the "No Competency Found" tile, done like this incase wanting to change the format
    }

    //Role Display
    $role = mysqli_fetch_assoc(RoleFromUser($conn, $_SESSION["userid"]));
    echo "<tr><td><b>" . $role["RName"] . "</b></td></tr>";
    $competencies = CompetencyRolesFromRoles($conn, $role["RoleID"]);
    $isNull = true;
    while ($competency = mysqli_fetch_array($competencies)) {
        $isNull = false;
        echo "<tr><td>" . $competency["CName"] . "</td>";
        displayUserRatings($conn, $competency["CompetencyID"], $_SESSION["userid"]);
        echo "</tr>";
    }
    if ($isNull) {
        $isNull = emptyArrayError($isNull);
    }

    //Group Display
    $groups = UserGroupFromUser($conn, $_SESSION["userid"]);
    while ($group = mysqli_fetch_array($groups)) {

        echo "<tr><td><b>" . $group["GName"] . "</b></td></tr>";
        $competencies = CompetencyGroupFromGroup($conn, $group["GroupID"]);
        $isNull = true;
        while ($competency = mysqli_fetch_array($competencies)) {
            $isNull = false;
            echo "<tr><td>" . $competency["CName"] . "</td>";
            displayUserRatings($conn, $competency["CompetencyID"], $_SESSION["userid"]);
        }
        if ($isNull) {
            $isNull = emptyArrayError($isNull);
        }
    }

    ?>
</table>
<?php
include_once 'footer.php';
?>